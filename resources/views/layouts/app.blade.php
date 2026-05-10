<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventaris Yaksa</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        input[type="checkbox"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            border: 2px solid #6b7280;
            border-radius: 9999px;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        input[type="checkbox"]:checked {
            background: #DC2626;
            border-color: #DC2626;
        }

        input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0.35rem;
            height: 0.35rem;
            border-radius: 9999px;
            background: white;
        }

        input[type="checkbox"]:hover {
            border-color: #DC2626;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* ===== Skeleton Loading ===== */
        @keyframes skeletonShimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 37%, #e5e7eb 63%);
            background-size: 200% 100%;
            animation: skeletonShimmer 1.8s ease-in-out infinite;
            border-radius: 0.5rem;
        }

        .skeleton-text {
            height: 0.875rem;
            margin-bottom: 0.5rem;
            border-radius: 0.375rem;
        }

        .skeleton-text-sm {
            height: 0.625rem;
            margin-bottom: 0.375rem;
            border-radius: 0.25rem;
        }

        .skeleton-title {
            height: 1.5rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
        }

        .skeleton-avatar {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
        }

        .skeleton-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1.5rem;
            overflow: hidden;
        }

        .skeleton-table-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .skeleton-badge {
            height: 1.5rem;
            width: 4.5rem;
            border-radius: 0.5rem;
        }

        .skeleton-bar {
            height: 0.625rem;
            border-radius: 9999px;
        }

        /* ===== Idle Warning Modal ===== */
        .idle-warning-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .idle-warning-overlay.show {
            opacity: 1;
        }

        .idle-warning-card {
            background: white;
            border-radius: 1.25rem;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
            transform: translateY(20px) scale(0.95);
            transition: transform 0.3s ease;
        }

        .idle-warning-overlay.show .idle-warning-card {
            transform: translateY(0) scale(1);
        }

        .idle-countdown-ring {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
        }

        .idle-countdown-ring circle {
            fill: none;
            stroke-width: 4;
        }

        .idle-countdown-ring .bg {
            stroke: #f3f4f6;
        }

        .idle-countdown-ring .progress {
            stroke: #DC2626;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: center;
            transition: stroke-dashoffset 1s linear;
        }
    </style>
</head>

<body class="bg-yaksa-bg text-yaksa-text font-sans antialiased">
    @php
        $gudangs = [
            'jakarta' => ['label' => 'Gudang Jakarta', 'color' => 'text-yaksa-red', 'border' => 'border-yaksa-red'],
            'bali' => ['label' => 'Gudang Bali', 'color' => 'text-blue-400', 'border' => 'border-blue-500'],
            'sfp' => ['label' => 'Small Form-factor Pluggable', 'color' => 'text-emerald-400', 'border' => 'border-emerald-500'],
        ];
        $currentGudang = request('gudang');
    @endphp

    <div class="flex h-screen overflow-hidden" x-data="appShell()">
        <!-- Desktop Sidebar (CSS-only responsive: hidden on mobile, flex on md+) -->
        <aside class="bg-gray-900 border-r border-gray-800 hidden md:flex flex-col text-white transition-all duration-300 ease-in-out shadow-2xl"
               :class="collapsed ? 'w-[72px]' : 'w-64'">
            
            <!-- Brand -->
            <div class="h-16 flex items-center border-b border-gray-800 transition-all duration-300"
                 :class="collapsed ? 'px-4 justify-center' : 'px-5 gap-3'">
                <img src="/images/logo.png" alt="Yaksa" class="w-9 h-9 object-contain flex-shrink-0">
                <span x-show="!collapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-lg font-bold tracking-wider whitespace-nowrap">Inventaris<span class="text-yaksa-red">Yaksa</span></span>
            </div>
            
            <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto hide-scrollbar">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center rounded-xl transition-all duration-200
                          {{ request()->routeIs('dashboard') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Dashboard</span>
                </a>


                @foreach($gudangs as $key => $g)
                    <div class="pt-4 mt-4 border-t border-gray-800" x-data="{ open: '{{ $currentGudang }}' === '{{ $key }}' }">
                        <button @click="open = !open" class="w-full flex items-start justify-between px-4 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-600 hover:text-gray-400 transition-colors focus:outline-none group">
                            <span x-show="!collapsed" class="text-left leading-relaxed pr-2">{{ $g['label'] }}</span>
                            <svg x-show="!collapsed" class="w-3 h-3 flex-shrink-0 mt-0.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open || collapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-1.5">
                            <!-- Barang -->
                            <a href="{{ route('items.index', ['gudang' => $key]) }}" 
                               class="group flex items-center rounded-xl transition-all duration-200
                                      {{ request()->routeIs('items.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                               :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Barang '.($key == 'sfp' ? 'SFP' : ucfirst($key)) : ''">
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('items.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <span x-show="!collapsed" x-transition class="whitespace-nowrap">Barang {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                            </a>

                            @if(Auth::user() && Auth::user()->isAdmin())
                                <!-- Kategori -->
                                <a href="{{ route('categories.index', ['gudang' => $key]) }}" 
                                   class="group flex items-center rounded-xl transition-all duration-200
                                          {{ request()->routeIs('categories.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Kategori '.($key == 'sfp' ? 'SFP' : ucfirst($key)) : ''">
                                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('categories.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Kategori {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                                </a>
                            @endif

                            <!-- Transaksi Gudang -->
                            <a href="{{ route('transactions.index', ['gudang' => $key]) }}" 
                               class="group flex items-center rounded-xl transition-all duration-200
                                      {{ request()->routeIs('transactions.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                               :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Transaksi '.($key == 'sfp' ? 'SFP' : ucfirst($key)) : ''">
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('transactions.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                <span x-show="!collapsed" x-transition class="whitespace-nowrap">Transaksi {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                            </a>

                            <!-- Laporan Gudang -->
                            <a href="{{ route('laporan.index', ['gudang' => $key]) }}" 
                               class="group flex items-center rounded-xl transition-all duration-200
                                      {{ request()->routeIs('laporan.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                               :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Laporan '.($key == 'sfp' ? 'SFP' : ucfirst($key)) : ''">
                                <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('laporan.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span x-show="!collapsed" x-transition class="whitespace-nowrap">Laporan {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
                
                <div class="pt-4 mt-4 border-t border-gray-800">
                    <p x-show="!collapsed" class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 mb-2">Global</p>
                    <!-- Log Transaksi Universal -->
                    <a href="{{ route('transactions.index', ['gudang' => 'universal']) }}" 
                       class="group flex items-center rounded-xl transition-all duration-200
                              {{ request()->routeIs('transactions.*') && request('gudang') == 'universal' ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                       :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Semua Transaksi' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('transactions.*') && request('gudang') == 'universal' ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Semua Transaksi</span>
                    </a>

                    <!-- Laporan Universal -->
                    <a href="{{ route('laporan.index', ['gudang' => 'universal']) }}" 
                       class="group flex items-center rounded-xl transition-all duration-200
                              {{ request()->routeIs('laporan.*') && request('gudang') == 'universal' ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                       :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Semua Laporan' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('laporan.*') && request('gudang') == 'universal' ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Semua Laporan</span>
                    </a>

                    <!-- Riwayat Aktivitas -->
                    <a href="{{ route('riwayat.index') }}" 
                       class="group flex items-center rounded-xl transition-all duration-200
                              {{ request()->routeIs('riwayat.*') ? 'bg-gray-800/80 text-white border-l-4 border-blue-400 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                       :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Riwayat Aktivitas' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('riwayat.*') ? 'text-blue-400' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-show="!collapsed" x-transition class="whitespace-nowrap">Riwayat Aktivitas</span>
                    </a>

                    @if(Auth::user() && Auth::user()->isAdmin())
                        <!-- Approval Transaksi -->
                        <a href="{{ route('approvals.index') }}" 
                           class="group flex items-center rounded-xl transition-all duration-200
                                  {{ request()->routeIs('approvals.*') ? 'bg-gray-800/80 text-white border-l-4 border-amber-400 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                           :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Approval Transaksi' : ''">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('approvals.*') ? 'text-amber-400' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-show="!collapsed" x-transition class="whitespace-nowrap">Approval Transaksi</span>
                        </a>
                    @endif
                </div>

                {{-- Super Admin Only --}}
                @if(Auth::user() && Auth::user()->isSuperAdmin())
                    <div class="pt-4 mt-4 border-t border-gray-800">
                        <p x-show="!collapsed" class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 mb-2">Super Admin</p>
                        <a href="{{ route('users.index') }}" 
                           class="group flex items-center rounded-xl transition-all duration-200
                                  {{ request()->routeIs('users.*') ? 'bg-gray-800/80 text-white border-l-4 border-purple-500 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                           :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'User Management' : ''">
                            <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-purple-400' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span x-show="!collapsed" x-transition class="whitespace-nowrap">User Management</span>
                        </a>
                    </div>
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-3 border-t border-gray-800 space-y-1">
                <!-- Collapse Button -->
                <button @click="toggleCollapse()" 
                        class="w-full group flex items-center text-gray-500 hover:bg-gray-800/70 hover:text-white rounded-xl transition-all duration-200"
                        :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'"
                        :title="collapsed ? (collapsed ? 'Expand Sidebar' : 'Collapse Sidebar') : ''">
                    <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!collapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        <path x-show="collapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span x-show="!collapsed" x-transition class="ml-3 text-sm font-medium whitespace-nowrap">Tutup Sidebar</span>
                </button>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full group flex items-center text-gray-500 hover:bg-red-500/10 hover:text-red-400 rounded-xl transition-all duration-200"
                            :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'"
                            :title="collapsed ? 'Logout' : ''">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="!collapsed" x-transition class="ml-3 text-sm font-medium whitespace-nowrap">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-yaksa-border flex items-center justify-between px-4 sm:px-6 z-20 shadow-sm">
                <!-- Hamburger Toggle (CSS-only responsive: visible on mobile, hidden on md+) -->
                <button @click.stop="toggleMobileMenu()" 
                        id="hamburger-button"
                        class="flex md:hidden p-2.5 rounded-xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-yaksa-red/20 mr-2 border border-transparent hover:border-gray-200"
                        style="transition: color 0.4s ease"
                        :style="mobileMenuOpen ? 'color: #DC2626' : 'color: #6b7280'"
                        aria-label="Toggle navigation menu">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="4" y1="6" x2="20" y2="6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              style="transition: all 0.4s cubic-bezier(0.68, -0.6, 0.32, 1.6); transform-origin: center"
                              :style="mobileMenuOpen ? 'transform: translateY(6px) rotate(45deg)' : 'transform: translateY(0) rotate(0)'"/>
                        <line x1="4" y1="12" x2="17" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              style="transition: opacity 0.3s ease, transform 0.3s ease"
                              :style="mobileMenuOpen ? 'opacity: 0; transform: translateX(-8px)' : 'opacity: 1; transform: translateX(0)'"/>
                        <line x1="4" y1="18" x2="20" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              style="transition: all 0.4s cubic-bezier(0.68, -0.6, 0.32, 1.6); transform-origin: center"
                              :style="mobileMenuOpen ? 'transform: translateY(-6px) rotate(-45deg)' : 'transform: translateY(0) rotate(0)'"/>
                    </svg>
                </button>
                <!-- Search with Realtime Suggestions -->
                <form method="GET" action="{{ route('items.index') }}" x-data="globalSearch()" @submit.prevent="submitSearch()" class="flex-1 max-w-xl">
                    <div class="relative w-full group">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 group-focus-within:text-yaksa-red transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="q" x-model="query" @input.debounce.500ms="liveSearch()"
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red sm:text-sm transition-all duration-300"
                               placeholder="Cari perangkat, serial number... (Tekan Enter)">
                    </div>
                </form>

                <div class="ml-6 flex items-center space-x-3">
                    <!-- Notification Bell -->
                    <div x-data="notificationBell()" x-init="fetchNotifications()" class="relative">
                        <button @click="open = !open" class="relative text-gray-400 border border-gray-200 bg-gray-50 p-2 rounded-xl hover:text-yaksa-red hover:border-red-200 hover:bg-red-50 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span x-show="unreadCount > 0" x-cloak class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </button>

                        <div x-show="open" x-cloak @click.outside="open = false" x-transition
                             class="fixed left-1/2 -translate-x-1/2 top-16 w-[calc(100vw-2rem)] sm:absolute sm:left-auto sm:right-0 sm:translate-x-0 sm:top-auto sm:w-96 bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-gray-800">Notifikasi</h3>
                                <button @click="markAllRead()" x-show="unreadCount > 0" class="text-xs text-yaksa-red hover:text-red-700 transition-colors font-medium">Tandai semua dibaca</button>
                            </div>
                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <a :href="'/notifications/' + notif.id + '/read'" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-150 no-underline" :class="notif.read_at ? 'opacity-60' : ''">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs"
                                                     :class="{'bg-emerald-500': notif.type==='item_created','bg-amber-500': notif.type==='transaction_out','bg-blue-500': notif.type==='transaction_in','bg-purple-500': notif.type==='user_created','bg-orange-500': notif.type==='approval_request'}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <template x-if="notif.type==='item_created'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></template>
                                                        <template x-if="notif.type==='transaction_out'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></template>
                                                        <template x-if="notif.type==='transaction_in'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></template>
                                                        <template x-if="notif.type==='user_created'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></template>
                                                        <template x-if="notif.type==='approval_request'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></template>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate" x-text="notif.title"></p>
                                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="notif.message"></p>
                                                <p class="text-[10px] text-gray-400 mt-1" x-text="timeAgo(notif.created_at)"></p>
                                            </div>
                                            <div x-show="!notif.read_at" class="flex-shrink-0 mt-2"><span class="w-2 h-2 bg-red-500 rounded-full block"></span></div>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifications.length === 0" class="px-4 py-8 text-center text-gray-400 text-sm">Tidak ada notifikasi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center group cursor-pointer focus:outline-none">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-red-500/20 group-hover:shadow-red-500/40 group-hover:scale-105 transition-all duration-300">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="ml-3 hidden sm:block text-left">
                                <span class="text-sm font-semibold text-gray-800 block">{{ Auth::user()->name ?? 'User' }}</span>
                                <span class="text-xs text-gray-400 capitalize">{{ Auth::user()->role ?? 'user' }}</span>
                            </div>
                            <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform duration-200 hidden sm:block" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="profileOpen" x-cloak @click.outside="profileOpen = false" x-transition
                             class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('riwayat.index') }}" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Riwayat
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                @if(Auth::user() && Auth::user()->isAdmin())
                                    <a href="{{ route('approvals.index') }}" class="w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Approval Transaksi
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-yaksa-bg p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 shadow-sm" 
                         x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-end="opacity-0 -translate-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button @click="show = false" class="text-green-400 hover:text-green-600">&times;</button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm" x-data="{ show: true }" x-show="show">
                        <div class="flex justify-between items-center">
                            <span>{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-400 hover:text-red-600">&times;</button>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </main>

            <!-- Mobile Sidebar Overlay (CSS-only responsive: never shows on desktop) -->
            <div class="md:hidden fixed inset-0 z-[9999]"
                 :class="mobileMenuOpen ? 'pointer-events-auto' : 'pointer-events-none'"
                 @keydown.escape.window="if(mobileMenuOpen) closeMobileMenu()">
                
                <!-- Backdrop (inline CSS transition — no Tailwind class dependency) -->
                <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm"
                     :style="mobileMenuOpen 
                         ? 'transition: opacity 0.3s ease-out; opacity: 1; pointer-events: auto' 
                         : 'transition: opacity 0.3s ease-out; opacity: 0; pointer-events: none'"
                     @click="closeMobileMenu()"></div>

                <!-- Sidebar Drawer (inline CSS slide transition — guaranteed animation) -->
                <div class="absolute inset-y-0 left-0 w-80 bg-gray-900 flex flex-col shadow-2xl border-r border-gray-800"
                     :style="mobileMenuOpen 
                         ? 'transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1); transform: translateX(0)' 
                         : 'transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1); transform: translateX(-100%)'">
                        
                        <!-- Header Mobile Sidebar -->
                        <div class="h-16 flex items-center justify-between px-5 border-b border-gray-800">
                            <div class="flex items-center gap-3">
                                <img src="/images/logo.png" alt="Yaksa" class="w-9 h-9 object-contain flex-shrink-0">
                                <span class="text-lg font-bold tracking-wider whitespace-nowrap text-white">Inventaris<span class="text-yaksa-red">Yaksa</span></span>
                            </div>
                            <button @click="closeMobileMenu()" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <!-- Content Mobile Sidebar -->
                        <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1.5 hide-scrollbar" @click="if($event.target.tagName === 'A' || $event.target.closest('a')) closeMobileMenu()">
                            <!-- Dashboard -->
                            <a href="{{ route('dashboard') }}" 
                               class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                      {{ request()->routeIs('dashboard') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span class="whitespace-nowrap font-medium text-sm">Dashboard</span>
                            </a>

                            @foreach($gudangs as $key => $g)
                                <div class="pt-4 mt-4 border-t border-gray-800" x-data="{ open: '{{ $currentGudang }}' === '{{ $key }}' }">
                                    <button @click="open = !open" class="w-full flex items-start justify-between px-4 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-600 hover:text-gray-400 transition-colors focus:outline-none group">
                                        <span class="text-left leading-relaxed pr-2">{{ $g['label'] }}</span>
                                        <svg class="w-3 h-3 mt-0.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    <div x-show="open" x-collapse class="space-y-1">
                                        <!-- Barang Gudang -->
                                        <a href="{{ route('items.index', ['gudang' => $key]) }}" 
                                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200
                                                  {{ request()->routeIs('items.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                            <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('items.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            <span class="text-xs">Barang {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                                        </a>

                                        @if(Auth::user() && Auth::user()->isAdmin())
                                            <a href="{{ route('categories.index', ['gudang' => $key]) }}" 
                                               class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200
                                                      {{ request()->routeIs('categories.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                                <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('categories.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                <span class="text-xs">Kategori {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('transactions.index', ['gudang' => $key]) }}" 
                                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200
                                                  {{ request()->routeIs('transactions.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                            <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('transactions.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                            <span class="text-xs">Transaksi {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                                        </a>

                                        <a href="{{ route('laporan.index', ['gudang' => $key]) }}" 
                                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200
                                                  {{ request()->routeIs('laporan.*') && $currentGudang == $key ? 'bg-gray-800/80 text-white border-l-4 ' . $g['border'] . ' shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                            <svg class="w-4 h-4 mr-3 flex-shrink-0 {{ request()->routeIs('laporan.*') && $currentGudang == $key ? $g['color'] : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-xs">Laporan {{ $key == 'sfp' ? 'SFP' : ucfirst($key) }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-4 mt-4 border-t border-gray-800">
                                <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 mb-2">Global</p>
                                <a href="{{ route('transactions.index', ['gudang' => 'universal']) }}" 
                                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                          {{ request()->routeIs('transactions.*') && request('gudang') == 'universal' ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('transactions.*') && request('gudang') == 'universal' ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <span class="whitespace-nowrap font-medium text-sm">Semua Transaksi</span>
                                </a>
                                <a href="{{ route('laporan.index', ['gudang' => 'universal']) }}" 
                                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                          {{ request()->routeIs('laporan.*') && request('gudang') == 'universal' ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('laporan.*') && request('gudang') == 'universal' ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="whitespace-nowrap font-medium text-sm">Semua Laporan</span>
                                </a>
                                <a href="{{ route('riwayat.index') }}" 
                                   class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                          {{ request()->routeIs('riwayat.*') ? 'bg-gray-800/80 text-white border-l-4 border-blue-400 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                    <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('riwayat.*') ? 'text-blue-400' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="whitespace-nowrap font-medium text-sm">Riwayat Aktivitas</span>
                                </a>

                                @if(Auth::user() && Auth::user()->isAdmin())
                                    <a href="{{ route('approvals.index') }}" 
                                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                              {{ request()->routeIs('approvals.*') ? 'bg-gray-800/80 text-white border-l-4 border-amber-400 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('approvals.*') ? 'text-amber-400' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="whitespace-nowrap font-medium text-sm">Approval Transaksi</span>
                                    </a>
                                @endif

                                @if(Auth::user() && Auth::user()->isSuperAdmin())
                                    <p class="px-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 mt-6 mb-2">Super Admin</p>
                                    <a href="{{ route('users.index') }}" 
                                       class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200
                                              {{ request()->routeIs('users.*') ? 'bg-gray-800/80 text-white border-l-4 border-purple-500 shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}">
                                        <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('users.*') ? 'text-purple-400' : 'group-hover:scale-110' }} transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <span class="whitespace-nowrap font-medium text-sm">User Management</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Mobile Sidebar -->
                        <div class="p-4 border-t border-gray-800">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-xl transition-all">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    <span class="text-sm font-medium">Logout System</span>
                                </button>
                            </form>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Idle Warning Modal -->
    <div id="idleWarningModal" class="idle-warning-overlay" style="display:none;">
        <div class="idle-warning-card">
            <div class="idle-countdown-ring">
                <svg viewBox="0 0 36 36">
                    <circle class="bg" cx="18" cy="18" r="15.5"></circle>
                    <circle class="progress" id="idleCountdownCircle" cx="18" cy="18" r="15.5"
                            stroke-dasharray="97.39" stroke-dashoffset="0"></circle>
                </svg>
                <div style="margin-top:-58px; font-size:1.25rem; font-weight:700; color:#1f2937;" id="idleCountdownText">60</div>
            </div>
            <h3 style="font-size:1.125rem; font-weight:700; color:#111827; margin-bottom:0.5rem;">Sesi Akan Berakhir</h3>
            <p style="font-size:0.875rem; color:#6b7280; margin-bottom:1.5rem; line-height:1.5;">Kamu tidak aktif selama beberapa menit.<br>Sesi akan otomatis logout.</p>
            <button id="idleStayBtn" style="width:100%; padding:0.75rem 1rem; background:linear-gradient(to right,#DC2626,#ef4444); color:white; border:none; border-radius:0.75rem; font-size:0.875rem; font-weight:600; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 14px rgba(220,38,38,0.25);">
                Tetap Aktif
            </button>
        </div>
    </div>

    <script>
        function appShell() {
            return {
                // State initialization
                mobileMenuOpen: false,
                collapsed: false,

                // Initialize state from localStorage with error handling
                init() {
                    // Read collapsed state from localStorage with fallback
                    try {
                        const stored = localStorage.getItem('sidebarCollapsed');
                        this.collapsed = stored === 'true';
                    } catch (e) {
                        console.warn('localStorage unavailable, using default collapsed state');
                        this.collapsed = false;
                    }

                    // Watch for mobile menu changes to handle body scroll lock
                    this.$watch('mobileMenuOpen', (isOpen) => {
                        if (isOpen) {
                            document.body.style.overflow = 'hidden';
                        } else {
                            document.body.style.overflow = '';
                        }
                    });

                    // Close mobile menu when viewport crosses md breakpoint (768px)
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 768 && this.mobileMenuOpen) {
                            this.mobileMenuOpen = false;
                        }
                    });
                },

                // Toggle mobile menu open/closed
                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                },

                // Close mobile menu
                closeMobileMenu() {
                    this.mobileMenuOpen = false;
                },

                // Toggle desktop collapse state and save to localStorage
                toggleCollapse() {
                    this.collapsed = !this.collapsed;
                    try {
                        localStorage.setItem('sidebarCollapsed', this.collapsed);
                    } catch (e) {
                        console.warn('Failed to save sidebar state to localStorage');
                    }
                },

                // Legacy method for backward compatibility
                toggleSidebar() {
                    if (window.innerWidth < 768) {
                        this.toggleMobileMenu();
                    } else {
                        this.toggleCollapse();
                    }
                }
            };
        }


        function globalSearch() {
            return {
                query: '{{ request('q') }}',
                liveSearch() {
                    // Hanya auto-update (AJAX) kalau di halaman Master Barang
                    if (window.location.pathname !== '{{ route('items.index', [], false) }}') return;
                    this.fetchData();
                },
                submitSearch() {
                    // Kalau di halaman lain, submit form standar ke Master Barang
                    if (window.location.pathname !== '{{ route('items.index', [], false) }}') {
                        this.$el.submit();
                        return;
                    }
                    this.fetchData();
                },
                fetchData() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('q', this.query);
                    window.history.pushState({}, '', url);

                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newTable = doc.querySelector('#items-table-container');
                        if (newTable) {
                            document.querySelector('#items-table-container').innerHTML = newTable.innerHTML;
                        }
                    });
                }
            };
        }

        function notificationBell() {
            return {
                open: false, notifications: [], unreadCount: 0,
                fetchNotifications() {
                    const doFetch = () => {
                        fetch('/api/notifications', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then(r => r.json())
                        .then(data => { this.notifications = data.notifications; this.unreadCount = data.unread_count; })
                        .catch(() => {});
                    };
                    doFetch();
                    setInterval(doFetch, 30000);
                },
                markAllRead() {
                    fetch('/notifications/mark-all-read', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(() => { this.notifications.forEach(n => n.read_at = new Date().toISOString()); this.unreadCount = 0; });
                },
                timeAgo(dateStr) {
                    const diff = Math.floor((new Date() - new Date(dateStr)) / 1000);
                    if (diff < 60) return 'Baru saja';
                    if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
                    if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
                    return Math.floor(diff / 86400) + ' hari lalu';
                }
            };
        }

        /* ============================================
         *  IDLE AUTO-LOGOUT (5 Menit / 300 detik)
         *  Warning muncul di 60 detik terakhir
         * ============================================ */
        (function() {
            const IDLE_TIMEOUT    = 5 * 60; // 300 detik = 5 menit
            const WARNING_AT      = 4 * 60; // Warning muncul setelah 4 menit idle (60 detik sebelum logout)
            const CIRCUMFERENCE   = 2 * Math.PI * 15.5; // ~97.39

            let idleSeconds       = 0;
            let warningShown      = false;
            let idleInterval      = null;

            const modal           = document.getElementById('idleWarningModal');
            const countdownText   = document.getElementById('idleCountdownText');
            const countdownCircle = document.getElementById('idleCountdownCircle');
            const stayBtn         = document.getElementById('idleStayBtn');

            function resetIdleTimer() {
                idleSeconds = 0;
                if (warningShown) {
                    hideWarning();
                }
            }

            function showWarning() {
                warningShown = true;
                modal.style.display = 'flex';
                requestAnimationFrame(() => {
                    modal.classList.add('show');
                });
            }

            function hideWarning() {
                warningShown = false;
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }

            function doLogout() {
                // Submit logout form
                const logoutForm = document.querySelector('form[action*="logout"]');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    window.location.href = '/login';
                }
            }

            function tick() {
                idleSeconds++;

                if (idleSeconds >= IDLE_TIMEOUT) {
                    clearInterval(idleInterval);
                    doLogout();
                    return;
                }

                if (idleSeconds >= WARNING_AT && !warningShown) {
                    showWarning();
                }

                if (warningShown) {
                    const remaining = IDLE_TIMEOUT - idleSeconds;
                    const totalWarning = IDLE_TIMEOUT - WARNING_AT; // 60 detik
                    countdownText.textContent = remaining;
                    const offset = CIRCUMFERENCE * (1 - remaining / totalWarning);
                    countdownCircle.style.strokeDashoffset = offset;
                }
            }

            // Events yang di-track sebagai "aktif"
            const activityEvents = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart', 'mousedown', 'input'];
            activityEvents.forEach(evt => {
                document.addEventListener(evt, resetIdleTimer, { passive: true });
            });

            // Tombol "Tetap Aktif"
            if (stayBtn) {
                stayBtn.addEventListener('click', function() {
                    resetIdleTimer();
                });
            }

            // Mulai interval
            idleInterval = setInterval(tick, 1000);
        })();
    </script>
</body>
</html>
