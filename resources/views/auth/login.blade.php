<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Inventaris Yaksa</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        input[type="checkbox"] { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
        input[type="checkbox"] { width: 1.1rem; height: 1.1rem; border: 2px solid rgba(255,255,255,0.25); border-radius: 9999px; background: rgba(255,255,255,0.05); cursor: pointer; transition: all 0.2s; position: relative; }
        input[type="checkbox"]:checked { background: #DC2626; border-color: #DC2626; }
        input[type="checkbox"]:checked::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 0.35rem; height: 0.35rem; border-radius: 9999px; background: white; }
        input[type="checkbox"]:hover { border-color: #DC2626; }
        input[type="checkbox"]:focus { outline: none; box-shadow: 0 0 0 3px rgba(220,38,38,0.2); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center font-sans antialiased relative overflow-hidden"
      style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);">

    {{-- Animated Background Particles --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-red-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-red-600/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-slate-500/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    {{-- Login Card --}}
    <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)"
         x-show="show"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="relative z-10 w-full max-w-md mx-4">

        {{-- Glass Card --}}
        <div class="backdrop-blur-xl bg-white/[0.07] border border-white/[0.12] rounded-2xl shadow-2xl p-8 space-y-8">
            
            {{-- Logo / Brand --}}
            <div class="text-center space-y-3">
                <div class="inline-block mb-1 transition-transform duration-300 hover:scale-110">
                    <img src="/images/logo.png" alt="Yaksa Logo" class="w-20 h-20 object-contain drop-shadow-lg mx-auto">
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Inventaris<span class="text-red-500">Yaksa</span></h1>
                <p class="text-sm text-slate-400">Masukkan kredensial untuk melanjutkan</p>
            </div>

            {{-- Error Messages --}}
            @if($errors->any())
            <div x-data="{ showErr: true }" x-show="showErr"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm rounded-xl px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                <button @click="showErr = false" class="text-red-400/60 hover:text-red-400 transition-colors">&times;</button>
            </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-300">Email</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 group-focus-within:text-red-400 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="block w-full pl-11 pr-4 py-3 bg-white/[0.05] border border-white/[0.1] rounded-xl text-white placeholder-slate-500 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:border-red-500/50 focus:bg-white/[0.08]
                                      hover:border-white/[0.2] hover:bg-white/[0.06]
                                      transition-all duration-300"
                               placeholder="admin@gmail.com">
                    </div>
                </div>

                {{-- Password --}}
                <div class="space-y-2" x-data="{ showPw: false }">
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 group-focus-within:text-red-400 transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input :type="showPw ? 'text' : 'password'" name="password" id="password" required
                               class="block w-full pl-11 pr-12 py-3 bg-white/[0.05] border border-white/[0.1] rounded-xl text-white placeholder-slate-500 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:border-red-500/50 focus:bg-white/[0.08]
                                      hover:border-white/[0.2] hover:bg-white/[0.06]
                                      transition-all duration-300"
                               placeholder="••••••••">
                        <button type="button" @click="showPw = !showPw" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors duration-200">
                            <svg x-show="!showPw" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="showPw" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me & Lupa Password --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember"
                               class="w-4 h-4 rounded-full border-white/20 bg-white/5 text-red-500 focus:ring-red-500/30 focus:ring-offset-0 transition-colors">
                        <span class="text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-slate-300 hover:text-red-400 transition-colors duration-200">Lupa Password?</a>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white font-semibold rounded-xl text-sm
                               shadow-lg shadow-red-500/25 hover:shadow-red-500/40
                               transform hover:-translate-y-0.5 active:translate-y-0 active:shadow-md
                               transition-all duration-300 ease-out
                               focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-slate-900">
                    Masuk ke Dashboard
                </button>
            </form>

            {{-- Footer Branding --}}
            <div class="text-center">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} PT Yaksa Ersada Solusindo</p>
            </div>
        </div>
    </div>

</body>
</html>
