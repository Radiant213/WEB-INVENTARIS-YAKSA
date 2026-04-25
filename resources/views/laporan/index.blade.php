@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false, tab: 'items' }" x-init="setTimeout(() => loaded = true, 50)">
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan {{ $activeGudang == 'universal' ? 'Semua Gudang' : 'Gudang ' . ucfirst($activeGudang) }}</h1>
            <p class="text-sm text-gray-500 mt-1">Ringkasan inventaris dan riwayat transaksi barang.</p>
        </div>
        <a href="{{ route('laporan.export') }}" class="group px-4 py-2.5 bg-gradient-to-r from-green-600 to-emerald-500 text-white rounded-xl text-sm font-semibold hover:from-green-500 hover:to-emerald-400 hover:shadow-lg hover:shadow-green-500/25 transition-all duration-200 active:scale-95 no-underline">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export to Excel
            </span>
        </a>
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
    <div x-show="tab === 'items'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
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
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">Total: <span class="font-semibold text-gray-700">{{ $items->count() }}</span></div>
        </div>
    </div>

    <!-- Transactions Tab -->
    <div x-show="tab === 'transactions'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
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
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-3">
                            @if($tx->tipe_transaksi == 'in')<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-600">↓ Masuk</span>
                            @else<span class="px-2 py-0.5 rounded-md text-xs font-semibold bg-amber-50 text-amber-600">↑ Keluar</span>@endif
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-900">{{ $tx->item->nama_perangkat ?? '-' }}</td>
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
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">Total: <span class="font-semibold text-gray-700">{{ $transactions->count() }}</span></div>
        </div>
    </div>
</div>
<style>[x-cloak] { display: none !important; }</style>
@endsection
