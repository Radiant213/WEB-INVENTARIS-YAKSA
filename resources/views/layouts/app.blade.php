<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventaris Yaksa</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; 
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem; }
        input[type="checkbox"] { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
        input[type="checkbox"] { width: 1rem; height: 1rem; border: 2px solid #6b7280; border-radius: 9999px; background: transparent; cursor: pointer; transition: all 0.2s; position: relative; }
        input[type="checkbox"]:checked { background: #DC2626; border-color: #DC2626; }
        input[type="checkbox"]:checked::after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 0.35rem; height: 0.35rem; border-radius: 9999px; background: white; }
        input[type="checkbox"]:hover { border-color: #DC2626; }
    </style>
</head>
<body class="bg-yaksa-bg text-yaksa-text font-sans antialiased">
    
    <div class="flex h-screen overflow-hidden" x-data="appShell()">
        
        <!-- Sidebar -->
        <aside class="bg-gray-900 border-r border-gray-800 hidden md:flex flex-col text-white transition-all duration-300 ease-in-out"
               :class="collapsed ? 'w-[72px]' : 'w-64'">
            
            <!-- Brand -->
            <div class="h-16 flex items-center border-b border-gray-800 transition-all duration-300"
                 :class="collapsed ? 'px-4 justify-center' : 'px-5 gap-3'">
                <img src="/images/logo.png" alt="Yaksa" class="w-9 h-9 object-contain flex-shrink-0">
                <span x-show="!collapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-lg font-bold tracking-wider whitespace-nowrap">Inventaris<span class="text-yaksa-red">Yaksa</span></span>
            </div>
            
            <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center rounded-xl transition-all duration-200
                          {{ request()->routeIs('dashboard') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Dashboard' : ''">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Dashboard</span>
                </a>
                
                <!-- Master Barang -->
                <a href="{{ route('items.index') }}" 
                   class="group flex items-center rounded-xl transition-all duration-200
                          {{ request()->routeIs('items.*') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Master Barang' : ''">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('items.*') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Master Barang</span>
                </a>

                <!-- Log Transaksi -->
                <a href="{{ route('transactions.index') }}" 
                   class="group flex items-center rounded-xl transition-all duration-200
                          {{ request()->routeIs('transactions.*') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Log Transaksi' : ''">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('transactions.*') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Log Transaksi</span>
                </a>

                <!-- Laporan -->
                <a href="{{ route('laporan.index') }}" 
                   class="group flex items-center rounded-xl transition-all duration-200
                          {{ request()->routeIs('laporan.*') ? 'bg-gray-800/80 text-white border-l-4 border-yaksa-red shadow-lg shadow-black/10' : 'text-gray-400 hover:bg-gray-800/70 hover:text-white hover:translate-x-1' }}"
                   :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'" :title="collapsed ? 'Laporan' : ''">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('laporan.*') ? 'text-yaksa-red' : 'group-hover:scale-110' }} transition-transform duration-200" :class="collapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="!collapsed" x-transition class="whitespace-nowrap">Laporan</span>
                </a>

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
            <div class="px-3 py-3 border-t border-gray-800 space-y-1">
                <!-- Collapse Toggle -->
                <button @click="collapsed = !collapsed" 
                        class="w-full flex items-center rounded-xl text-gray-500 hover:bg-gray-800/70 hover:text-gray-300 transition-all duration-200"
                        :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'">
                    <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    <span x-show="!collapsed" x-transition class="ml-3 text-sm whitespace-nowrap">Tutup Sidebar</span>
                </button>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full group flex items-center text-gray-500 hover:bg-red-500/10 hover:text-red-400 rounded-xl transition-all duration-200"
                            :class="collapsed ? 'px-3 py-3 justify-center' : 'px-4 py-3'">
                        <svg class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span x-show="!collapsed" x-transition class="ml-3 text-sm whitespace-nowrap">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-yaksa-border flex items-center justify-between px-6 z-20 shadow-sm">
                <!-- Search with Realtime Suggestions -->
                <div class="flex-1 max-w-xl" x-data="searchBar()">
                    <div class="relative w-full group">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 group-focus-within:text-yaksa-red transition-colors duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" x-model="query" @input.debounce.250ms="search()" @focus="showResults = results.length > 0" @click.outside="showResults = false"
                               class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red sm:text-sm transition-all duration-300"
                               placeholder="Cari perangkat, serial number...">
                        
                        <!-- Search Results Dropdown -->
                        <div x-show="showResults && results.length > 0" x-cloak x-transition
                             class="absolute top-full left-0 right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden z-50">
                            <template x-for="item in results" :key="item.id">
                                <a :href="'/items?q=' + item.nama_perangkat" class="flex items-center px-4 py-3 hover:bg-red-50/50 transition-colors no-underline border-b border-gray-50 last:border-0">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3 flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="item.nama_perangkat"></p>
                                        <p class="text-xs text-gray-400 font-mono" x-text="item.serial_number"></p>
                                    </div>
                                    <span class="text-xs px-2 py-0.5 rounded-md font-medium ml-2"
                                          :class="{
                                              'bg-emerald-50 text-emerald-700': item.status === 'Ready',
                                              'bg-amber-50 text-amber-700': item.status === 'Barang Keluar',
                                              'bg-red-50 text-red-700': item.status === 'Barang RMA',
                                          }" x-text="item.status"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="ml-6 flex items-center space-x-3">
                    <!-- Notification Bell -->
                    <div x-data="notificationBell()" x-init="fetchNotifications()" class="relative">
                        <button @click="open = !open" class="relative text-gray-400 border border-gray-200 bg-gray-50 p-2 rounded-xl hover:text-yaksa-red hover:border-red-200 hover:bg-red-50 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            <span x-show="unreadCount > 0" x-cloak class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </button>

                        <div x-show="open" x-cloak @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden z-50">
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
                                                     :class="{'bg-emerald-500': notif.type==='item_created','bg-amber-500': notif.type==='transaction_out','bg-blue-500': notif.type==='transaction_in','bg-purple-500': notif.type==='user_created'}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <template x-if="notif.type==='item_created'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></template>
                                                        <template x-if="notif.type==='transaction_out'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></template>
                                                        <template x-if="notif.type==='transaction_in'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></template>
                                                        <template x-if="notif.type==='user_created'"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></template>
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
        </div>
    </div>

    <script>
        function appShell() {
            return {
                collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
                init() {
                    this.$watch('collapsed', val => localStorage.setItem('sidebar_collapsed', val));
                }
            };
        }

        function searchBar() {
            return {
                query: '',
                results: [],
                showResults: false,
                search() {
                    if (this.query.length < 1) { this.results = []; this.showResults = false; return; }
                    fetch('/api/items/search?q=' + encodeURIComponent(this.query), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(data => { this.results = data; this.showResults = data.length > 0; })
                    .catch(() => {});
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
    </script>
</body>
</html>
