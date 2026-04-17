@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        
        <!-- Total Perangkat -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-gray-300 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Perangkat</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Ready -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-emerald-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Ready / Tersedia</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $readyItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-amber-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang Keluar</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $keluarItems }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
            </div>
        </div>

        <!-- RMA -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:border-red-200 hover:-translate-y-1 transition-all duration-300 cursor-default"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-[400ms]" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">RMA / Rusak</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $rmaItems }}</p>
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
                                'Barang RMA' => 'bg-red-500',
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
                        <th class="px-6 py-3 text-left font-semibold">Pengirim</th>
                        <th class="px-6 py-3 text-left font-semibold">Penerima</th>
                        <th class="px-6 py-3 text-left font-semibold">Oleh</th>
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
                        <td class="px-6 py-4 text-gray-500">{{ $tx->pengirim ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->penerima ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $tx->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi tercatat</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
