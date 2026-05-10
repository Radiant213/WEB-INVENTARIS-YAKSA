<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        Notification::create([
            'type' => 'user_created',
            'title' => 'User Baru Ditambahkan',
            'message' => "User {$user->name} ({$user->role}) telah ditambahkan.",
            'link' => route('users.index'),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Tidak bisa menghapus Super Admin!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
