@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false, tab: 'items' }" x-init="setTimeout(() => loaded = true, 500)">

    <!-- ===== SKELETON LOADING ===== -->
    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="skeleton skeleton-title" style="width: 280px;"></div>
                <div class="skeleton skeleton-text-sm" style="width: 350px;"></div>
            </div>
            <div class="flex gap-3">
                <div class="skeleton" style="width: 300px; height: 38px; border-radius: 0.75rem;"></div>
                <div class="skeleton" style="width: 100px; height: 38px; border-radius: 0.75rem;"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            @for($i = 0; $i < 6; $i++)
            <div class="skeleton-card" style="padding:1rem; text-align:center;">
                <div class="skeleton skeleton-text-sm mx-auto" style="width: 50%;"></div>
                <div class="skeleton skeleton-title mx-auto" style="width: 40%; margin-top:0.5rem; margin-bottom:0;"></div>
            </div>
            @endfor
        </div>

        <div class="skeleton" style="width: 250px; height: 42px; border-radius: 0.75rem; mb-6"></div>
        <div class="mt-6 skeleton-card" style="padding:0;">
            <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                @for($i = 0; $i < 7; $i++)
                <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                @endfor
            </div>
            @for($i = 0; $i < 5; $i++)
            <div class="skeleton-table-row">
                <div class="skeleton skeleton-text" style="width: {{ rand(120, 200) }}px; margin-bottom:0; flex:1;"></div>
                <div class="skeleton skeleton-text" style="width: 100px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 4rem;"></div>
                <div class="skeleton skeleton-text" style="width: 120px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-text" style="width: 80px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-text" style="width: 60px; margin-bottom:0; flex-shrink:0;"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- ===== REAL CONTENT ===== -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan {{ $activeGudang == 'universal' ? 'Semua Gudang' : 'Gudang ' . ucfirst($activeGudang) }}</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan inventaris dan riwayat transaksi barang.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
            <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-2 items-center" id="filterForm">
                <input type="hidden" name="gudang" value="{{ $activeGudang }}">
                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('filterForm').submit()"
                           class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2 bg-white transition-all duration-200 cursor-pointer" title="Mulai Tanggal">
                    <span class="text-gray-400">-</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('filterForm').submit()"
                           class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2 bg-white transition-all duration-200 cursor-pointer" title="Sampai Tanggal">
                </div>
            </form>
            <a href="{{ route('laporan.export', ['gudang' => $activeGudang, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="group px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-500 text-white rounded-xl text-sm font-semibold hover:from-green-500 hover:to-emerald-400 hover:shadow-lg hover:shadow-green-500/25 transition-all duration-200 active:scale-95 no-underline flex items-center h-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">Total</p><p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalItems }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">Masuk</p><p class="text-2xl font-bold text-blue-600 mt-1">{{ $masukItems }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">Ready</p><p class="text-2xl font-bold text-emerald-600 mt-1">{{ $readyItems }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">Keluar</p><p class="text-2xl font-bold text-amber-600 mt-1">{{ $keluarItems }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">RMA</p><p class="text-2xl font-bold text-purple-600 mt-1">{{ $rmaItems }}</p></div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center"><p class="text-xs text-gray-400 uppercase font-semibold">Rusak</p><p class="text-2xl font-bold text-red-600 mt-1">{{ $rusakItems }}</p></div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl mb-6 w-fit"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <button @click="tab = 'items'" :class="tab === 'items' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 text-sm font-medium rounded-lg transition-all duration-200">Data Inventaris</button>
        <button @click="tab = 'transactions'" :class="tab === 'transactions' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 text-sm font-medium rounded-lg transition-all duration-200">Log Transaksi</button>
    </div>

    <!-- Items Tab -->
    <div x-show="tab === 'items' && loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[800px]">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Perangkat</th>
                            <th class="px-6 py-3 text-left font-semibold">S/N</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Keterangan</th>
                            <th class="px-6 py-3 text-left font-semibold">Lokasi</th>
                            <th class="px-6 py-3 text-left font-semibold">OS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $item->nama_perangkat }}</td>
                            <td class="px-6 py-3 font-mono text-xs text-gray-500">{{ $item->serial_number }}</td>
                            <td class="px-6 py-3">
                                @if($item->status == 'Ready')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-700">Ready</span>
                                @elseif($item->status == 'Barang Keluar')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-700">Keluar</span>
                                @elseif($item->status == 'Barang RMA')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-purple-50 text-purple-700">RMA</span>
                                @elseif($item->status == 'Barang Rusak')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-red-50 text-red-700">Rusak</span>
                                @else<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-gray-50 text-gray-700">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $item->status_barang ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $item->lokasi_device ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $item->os_version ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">Total: <span class="font-semibold text-gray-700">{{ $items->count() }}</span></div>
        </div>
    </div>

    <!-- Transactions Tab -->
    <div x-show="tab === 'transactions' && loaded" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Tipe</th>
                            <th class="px-6 py-3 text-left font-semibold">Perangkat</th>
                            <th class="px-6 py-3 text-left font-semibold text-center">Bukti</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-6 py-3 text-left font-semibold">Pengirim</th>
                            <th class="px-6 py-3 text-left font-semibold">Penerima</th>
                            <th class="px-6 py-3 text-left font-semibold">Oleh</th>
                        </tr>
                    </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">
                            @if($tx->tipe_transaksi == 'in')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-600">↓ Masuk</span>
                            @else<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-600">↑ Keluar</span>@endif
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $tx->item->nama_perangkat ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($tx->bukti_foto)
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
                            @else
                                <span class="text-gray-300 text-[10px] italic">No file</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $tx->tanggal_transaksi->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $tx->pengirim ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $tx->penerima ?? '-' }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $tx->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">Total: <span class="font-semibold text-gray-700">{{ $transactions->count() }}</span></div>
    </div>
</div>
    </div>
</div>
<style>[x-cloak] { display: none !important; }</style>
@endsection
