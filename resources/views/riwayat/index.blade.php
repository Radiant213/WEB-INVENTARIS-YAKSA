@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false, activeTab: 'transaksi' }" x-init="setTimeout(() => loaded = true, 500)">

    <!-- ===== SKELETON LOADING ===== -->
    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <div class="skeleton skeleton-title" style="width: 200px;"></div>
                <div class="skeleton skeleton-text-sm" style="width: 380px;"></div>
            </div>
        </div>

        <div class="skeleton" style="width: 320px; height: 42px; border-radius: 0.75rem; mb-6"></div>
        
        <div class="mt-6 skeleton-card" style="padding:0;">
            <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                @for($i = 0; $i < 7; $i++)
                <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                @endfor
            </div>
            @for($i = 0; $i < 5; $i++)
            <div class="skeleton-table-row">
                <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 4.5rem;"></div>
                <div class="skeleton skeleton-text" style="width: {{ rand(120, 200) }}px; margin-bottom:0; flex:1;"></div>
                <div class="skeleton skeleton-text" style="width: 100px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-text" style="width: 80px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-text" style="width: 150px; margin-bottom:0; flex-shrink:0;"></div>
                <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 4rem;"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- ===== REAL CONTENT ===== -->

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Aktivitas</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat riwayat pencatatan transaksi dan penambahan barang Anda.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <button @click="activeTab = 'transaksi'" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                :class="activeTab === 'transaksi' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Transaksi Barang
            </span>
        </button>
        <button @click="activeTab = 'master'" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200"
                :class="activeTab === 'master' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Pembuatan Barang
            </span>
        </button>
    </div>

    <!-- Tab: Riwayat Transaksi -->
    <div x-show="activeTab === 'transaksi' && loaded" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tipe</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Perangkat</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Serial Number</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Bukti</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Pihak Terkait</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $index => $tx)
                        <tr class="hover:bg-blue-50/30 transition-all duration-200"
                            style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                            <td class="px-6 py-4">
                                @if($tx->tipe_transaksi == 'in')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-200/50">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/50">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $tx->item->nama_perangkat ?? '-' }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $tx->item->serial_number ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $tx->tanggal_transaksi->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
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
                            <td class="px-6 py-4 text-gray-600">
                                @if($tx->tipe_transaksi == 'in')
                                    Pengirim: {{ $tx->pengirim ?? '-' }}
                                @else
                                    Penerima: {{ $tx->penerima ?? '-' }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tx->status == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span>Disetujui
                                    </span>
                                @elseif($tx->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full animate-pulse"></span>Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 border border-red-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                <p class="font-medium text-gray-600">Belum ada riwayat transaksi</p>
                                <p class="text-sm mt-1">Transaksi barang yang Anda catat akan muncul di sini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $transactions->appends(request()->except('tx_page'))->links('pagination::tailwind') }}
            </div>
            @else
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $transactions->total() }}</span> transaksi
            </div>
            @endif
        </div>
    </div>

    <!-- Tab: Riwayat Pembuatan Barang -->
    <div x-show="activeTab === 'master' && loaded" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Nama Perangkat</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Serial Number</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Gudang</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tanggal Dibuat</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status Approval</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($createdItems as $index => $item)
                        <tr class="hover:bg-purple-50/30 transition-all duration-200"
                            style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama_perangkat }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $item->serial_number }}</td>
                            <td class="px-6 py-4 text-gray-600 capitalize">{{ $item->gudang ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @if($item->status_approval == 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span>Disetujui
                                    </span>
                                @elseif($item->status_approval == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600 border border-amber-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full animate-pulse"></span>Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-600 border border-red-200/50">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="font-medium text-gray-600">Belum ada riwayat pembuatan barang</p>
                                <p class="text-sm mt-1">Barang yang Anda tambahkan akan muncul di sini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($createdItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $createdItems->appends(request()->except('item_page'))->links('pagination::tailwind') }}
            </div>
            @else
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $createdItems->total() }}</span> barang
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection
