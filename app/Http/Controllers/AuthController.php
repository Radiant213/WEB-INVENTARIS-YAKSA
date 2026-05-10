<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Mail\AuthOtpMail;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // --- REGISTRATION FLOW ---

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email ini sudah terdaftar.',
        ]);

        // Generate OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        DB::table('otp_codes')->where('email', $request->email)->where('type', 'register')->delete();
        DB::table('otp_codes')->insert([
            'email' => $request->email,
            'otp' => $otp,
            'type' => 'register',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Save registration data temporarily in session
        $request->session()->put('register_data', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            Mail::to($request->email)->send(new AuthOtpMail($otp, 'register'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Silakan hubungi admin atau coba lagi nanti.'])->withInput();
        }

        return redirect()->route('register.verify')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function showVerifyRegister(Request $request)
    {
        if (!$request->session()->has('register_data')) {
            return redirect()->route('register');
        }
        return view('auth.verify-register');
    }

    public function verifyRegister(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $registerData = $request->session()->get('register_data');
        if (!$registerData) {
            return redirect()->route('register')->withErrors(['email' => 'Sesi pendaftaran habis, silakan daftar ulang.']);
        }

        $email = $registerData['email'];

        $otpRecord = DB::table('otp_codes')
            ->where('email', $email)
            ->where('type', 'register')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak ditemukan atau sudah kadaluarsa.']);
        }

        if (Carbon::now()->isAfter($otpRecord->expires_at)) {
            DB::table('otp_codes')->where('id', $otpRecord->id)->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan daftar ulang.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // Create User
        $user = User::create([
            'name' => $registerData['name'],
            'email' => $email,
            'password' => $registerData['password'],
            'role' => 'user', // Default role for public registration
        ]);

        // Clear OTP and Session
        DB::table('otp_codes')->where('id', $otpRecord->id)->delete();
        $request->session()->forget('register_data');

        // Auto login
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }

    // --- FORGOT PASSWORD FLOW ---

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.'
        ]);

        $otp = sprintf("%06d", mt_rand(1, 999999));

        DB::table('otp_codes')->where('email', $request->email)->where('type', 'reset_password')->delete();
        DB::table('otp_codes')->insert([
            'email' => $request->email,
            'otp' => $otp,
            'type' => 'reset_password',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $request->session()->put('reset_email', $request->email);

        try {
            Mail::to($request->email)->send(new AuthOtpMail($otp, 'reset_password'));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email OTP. Pastikan konfigurasi SMTP benar.'])->withInput();
        }

        return redirect()->route('password.verify')->with('success', 'Kode OTP reset password telah dikirim ke email Anda.');
    }

    public function showVerifyReset(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-reset');
    }

    public function processResetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->session()->get('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi habis, silakan ulangi permintaan reset.']);
        }

        $otpRecord = DB::table('otp_codes')
            ->where('email', $email)
            ->where('type', 'reset_password')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak ditemukan atau sudah kadaluarsa.']);
        }

        if (Carbon::now()->isAfter($otpRecord->expires_at)) {
            DB::table('otp_codes')->where('id', $otpRecord->id)->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan ulangi.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // Update Password
        User::where('email', $email)->update([
            'password' => Hash::make($request->password)
        ]);

        // Clear OTP and Session
        DB::table('otp_codes')->where('id', $otpRecord->id)->delete();
        $request->session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
