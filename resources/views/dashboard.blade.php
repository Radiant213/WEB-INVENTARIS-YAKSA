@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 600)">
    
    <!-- ===== SKELETON LOADING ===== -->
    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Skeleton Header -->
        <div class="mb-8">
            <div class="skeleton skeleton-title" style="width: 160px;"></div>
            <div class="skeleton skeleton-text-sm" style="width: 320px;"></div>
        </div>

        <!-- Skeleton Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
            @for($i = 0; $i < 6; $i++)
            <div class="skeleton-card">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="skeleton skeleton-text-sm" style="width: 70%;"></div>
                        <div class="skeleton skeleton-title" style="width: 50%; margin-top: 0.75rem;"></div>
                    </div>
                    <div class="skeleton skeleton-avatar hidden sm:block"></div>
                </div>
            </div>
            @endfor
        </div>

        <!-- Skeleton Distribution Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="skeleton-card">
                <div class="flex items-center gap-2 mb-4">
                    <div class="skeleton" style="width:1rem; height:1rem; border-radius:0.25rem;"></div>
                    <div class="skeleton skeleton-text" style="width: 130px; margin-bottom:0;"></div>
                </div>
                <div class="space-y-4">
                    @for($i = 0; $i < 4; $i++)
                    <div>
                        <div class="flex justify-between mb-1">
                            <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 120) }}px; margin-bottom:0;"></div>
                            <div class="skeleton skeleton-text-sm" style="width: 25px; margin-bottom:0;"></div>
                        </div>
                        <div class="skeleton skeleton-bar" style="width: {{ rand(30, 90) }}%;"></div>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="skeleton-card">
                <div class="flex items-center gap-2 mb-4">
                    <div class="skeleton" style="width:1rem; height:1rem; border-radius:0.25rem;"></div>
                    <div class="skeleton skeleton-text" style="width: 160px; margin-bottom:0;"></div>
                </div>
                <div class="space-y-3">
                    @for($i = 0; $i < 5; $i++)
                    <div class="flex items-center justify-between py-2 px-3">
                        <div class="flex items-center gap-3">
                            <div class="skeleton" style="width:2rem; height:2rem; border-radius:0.5rem;"></div>
                            <div class="skeleton skeleton-text" style="width: {{ rand(80, 150) }}px; margin-bottom:0;"></div>
                        </div>
                        <div class="skeleton" style="width:2.5rem; height:1.5rem; border-radius:0.5rem;"></div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Skeleton Table -->
        <div class="skeleton-card" style="padding:0;">
            <div style="padding:1rem 1.5rem; border-bottom:1px solid #f3f4f6;">
                <div class="skeleton skeleton-text" style="width: 140px; margin-bottom:0;"></div>
            </div>
            <div>
                <div class="skeleton-table-row" style="background:#fafafa;">
                    @for($i = 0; $i < 6; $i++)
                    <div class="skeleton skeleton-text-sm" style="width: {{ rand(50, 80) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    @endfor
                </div>
                @for($i = 0; $i < 5; $i++)
                <div class="skeleton-table-row">
                    <div class="skeleton skeleton-badge" style="flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(100, 180) }}px; margin-bottom:0; flex:1;"></div>
                    <div class="skeleton skeleton-text" style="width: 70px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 60px; margin-bottom:0; flex-shrink:0;"></div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- ===== REAL CONTENT ===== -->

    <!-- Header -->
    <div class="mb-8"
         x-show="loaded"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Ringkasan data inventaris PT Yaksa Ersada Solusindo</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">
        
        <!-- Total Perangkat -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-gray-300 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Perangkat</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 hidden sm:flex">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Barang Masuk (Transactions In) -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-[150ms]" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Masuk</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $masukItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 hidden sm:flex">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                </div>
            </div>
        </div>

        <!-- Ready -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-emerald-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Ready</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $readyItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 hidden sm:flex">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Barang Keluar (Status) -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-amber-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Keluar</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $keluarItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 hidden sm:flex">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
            </div>
        </div>

        <!-- RMA -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-purple-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-[400ms]" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">RMA / Spare</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $rmaItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <!-- Rusak -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-red-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-[500ms]" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rusak</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $rusakItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Section: Status Distribution & Lokasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-500" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        
        <!-- Status Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Distribusi Status
            </h3>
            <div class="space-y-3">
                @foreach($statusDistribution as $status)
                <div class="group/bar">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="font-medium text-gray-600">{{ $status->status ?? 'Tidak Diketahui' }}</span>
                        <span class="font-bold text-gray-800">{{ $status->total }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        @php
                            $percentage = $totalItems > 0 ? ($status->total / $totalItems) * 100 : 0;
                            $barColor = match($status->status) {
                                'Ready' => 'bg-emerald-500',
                                'Barang Keluar' => 'bg-amber-500',
                                'Barang RMA' => 'bg-purple-500',
                                'Barang Rusak' => 'bg-red-500',
                                default => 'bg-blue-500',
                            };
                        @endphp
                        <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-1000 ease-out group-hover/bar:opacity-80" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Lokasi Distribution -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
            <h3 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Distribusi Lokasi (Top 5)
            </h3>
            <div class="space-y-3">
                @foreach($lokasiDistribution as $lokasi)
                <div class="flex items-center justify-between py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 group/loc">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center group-hover/loc:bg-red-50 transition-colors duration-200">
                            <svg class="w-4 h-4 text-gray-400 group-hover/loc:text-yaksa-red transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ $lokasi->lokasi_device }}</span>
                    </div>
                    <span class="text-sm font-bold text-gray-900 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $lokasi->total }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-[600ms]" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Transaksi Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold">Tipe</th>
                        <th class="px-6 py-3 text-left font-semibold">Perangkat</th>
                        <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold text-center">Bukti</th>
                        <th class="px-6 py-3 text-left font-semibold">Pengirim</th>
                        <th class="px-6 py-3 text-left font-semibold">Penerima</th>
                        <th class="px-6 py-3 text-left font-semibold">Oleh</th>
                        @if(Auth::user() && Auth::user()->isAdmin())
                        <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions as $tx)
                    <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                        <td class="px-6 py-4">
                            @if($tx->tipe_transaksi == 'in')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200/50">↓ IN</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/50">↑ OUT</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $tx->item->nama_perangkat ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($tx->bukti_foto)
                                @if(str_ends_with(strtolower($tx->bukti_foto), '.pdf'))
                                    <a href="{{ url('file/' . $tx->bukti_foto) }}" target="_blank" class="group relative inline-flex transition-transform hover:scale-110 items-center justify-center w-10 h-10 bg-red-50 rounded-lg border border-red-200 text-red-500 shadow-sm mx-auto" title="Lihat PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity flex items-center justify-center">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </div>
                                    </a>
                                @else
                                    <button type="button" @click="Swal.fire({
                                        title: 'Bukti Transaksi',
                                        imageUrl: '{{ url('file/' . $tx->bukti_foto) }}',
                                        imageAlt: 'Bukti Foto',
                                        showCloseButton: true,
                                        confirmButtonColor: '#ef4444',
                                        confirmButtonText: 'Tutup',
                                        customClass: { popup: 'rounded-2xl' }
                                    })" class="group relative inline-block transition-transform hover:scale-110">
                                        <img src="{{ url('file/' . $tx->bukti_foto) }}" class="w-10 h-10 object-cover rounded-lg border border-gray-200 shadow-sm mx-auto">
                                        <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                    </button>
                                @endif
                            @else
                                <span class="text-gray-300 text-[10px] italic">No file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->pengirim ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->penerima ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->user->name ?? '-' }}</td>
                        @if(Auth::user() && Auth::user()->isAdmin())
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('transactions.edit', $tx) }}" class="inline-flex items-center justify-center text-gray-400 hover:text-yaksa-red transition-all duration-200 hover:scale-110" title="Edit Log">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form id="delete-tx-{{ $tx->id }}" method="POST" action="{{ route('transactions.destroy', $tx) }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDeleteTx({{ $tx->id }})" class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition-all duration-200 hover:scale-110" title="Hapus Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (Auth::user() && Auth::user()->isAdmin()) ? '8' : '7' }}" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi tercatat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteTx(id) {
        Swal.fire({
            title: 'Hapus Log Transaksi?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-4 py-2 font-medium',
                cancelButton: 'rounded-xl px-4 py-2 font-medium'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-tx-' + id).submit();
            }
        })
    }
</script>
@endpush
@endsection
