@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah User Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Buat akun baru untuk sistem inventaris.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 flex items-center gap-2 no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm max-w-xl"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label for="role" class="block text-sm font-semibold text-gray-700">Role <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="role" id="role" required
                            class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 appearance-none cursor-pointer">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Operator)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" required
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
                @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 no-underline">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl text-sm font-semibold hover:from-red-500 hover:to-red-400 hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 active:scale-95">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
