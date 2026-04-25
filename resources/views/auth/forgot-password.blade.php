<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Inventaris Yaksa</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0B0E14; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-[#131822] rounded-2xl shadow-2xl border border-gray-800 p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white tracking-tight mb-2">Lupa Password</h1>
            <p class="text-gray-400 text-sm">Masukkan email Gmail Anda, kami akan mengirimkan kode OTP untuk reset password.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@gmail.com"
                       class="w-full bg-[#0B0E14] border @error('email') border-yaksa-red @else border-gray-700 @enderror rounded-xl px-4 py-3 text-white focus:outline-none focus:border-yaksa-red focus:ring-1 focus:ring-yaksa-red transition-all">
                @error('email')
                    <p class="mt-2 text-sm text-yaksa-red">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-yaksa-red hover:bg-red-700 text-white font-semibold rounded-xl px-4 py-3 transition-colors duration-200">
                Kirim Kode Reset
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-400">
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-300 transition-colors">Kembali ke halaman login</a>
            </p>
        </div>
    </div>
</body>
</html>
