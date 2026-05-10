@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">

    <!-- ===== SKELETON LOADING ===== -->
    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Skeleton Header -->
        <div class="mb-8">
            <div class="skeleton skeleton-title" style="width: 200px;"></div>
            <div class="skeleton skeleton-text-sm" style="width: 400px;"></div>
        </div>

        <!-- Skeleton Section 1 -->
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <div class="skeleton" style="width:2rem; height:2rem; border-radius:0.5rem;"></div>
                <div class="skeleton skeleton-text" style="width: 220px; margin-bottom:0;"></div>
            </div>
            <div class="skeleton-card" style="padding:0;">
                <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                    @for($i = 0; $i < 6; $i++)
                    <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    @endfor
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="skeleton-table-row">
                    <div class="skeleton skeleton-text" style="width: {{ rand(120, 200) }}px; margin-bottom:0; flex:1;"></div>
                    <div class="skeleton skeleton-text" style="width: 100px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 80px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 80px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 3.5rem;"></div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Skeleton Section 2 -->
        <div>
            <div class="flex items-center gap-2 mb-3">
                <div class="skeleton" style="width:2rem; height:2rem; border-radius:0.5rem;"></div>
                <div class="skeleton skeleton-text" style="width: 200px; margin-bottom:0;"></div>
            </div>
            <div class="skeleton-card" style="padding:0;">
                <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                    @for($i = 0; $i < 6; $i++)
                    <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    @endfor
                </div>
                @for($i = 0; $i < 3; $i++)
                <div class="skeleton-table-row">
                    <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 4rem;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(120, 200) }}px; margin-bottom:0; flex:1;"></div>
                    <div class="skeleton skeleton-text" style="width: 100px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 80px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 3.5rem;"></div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- ===== REAL CONTENT ===== -->

    <!-- Header -->
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Approval Center</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola request persetujuan dari user: transaksi barang dan penambahan master barang.</p>
        </div>
    </div>

    <!-- Section 1: Approval Penambahan Barang -->
    <div class="mb-8"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Request Penambahan Barang</h2>
            @if($pendingItems->total() > 0)
            <span class="ml-2 px-2.5 py-0.5 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">{{ $pendingItems->total() }}</span>
            @endif
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Nama Perangkat</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Serial Number</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Gudang</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Dicatat Oleh</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingItems as $index => $item)
                        <tr class="hover:bg-purple-50/30 transition-all duration-200"
                            style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama_perangkat }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $item->serial_number }}</td>
                            <td class="px-6 py-4 text-gray-600 capitalize">{{ $item->gudang ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->status ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-gray-600 text-xs">{{ $item->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <form method="POST" action="{{ route('approvals.approveItem', $item) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1" title="Terima">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terima
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('approvals.rejectItem', $item) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="font-medium text-gray-600">Tidak ada request penambahan barang</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingItems->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $pendingItems->appends(request()->except('item_page'))->links('pagination::tailwind') }}
            </div>
            @else
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $pendingItems->total() }}</span> pending
            </div>
            @endif
        </div>
    </div>

    <!-- Section 2: Approval Transaksi -->
    <div x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800">Request Transaksi Barang</h2>
            @if($pendingTransactions->total() > 0)
            <span class="ml-2 px-2.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">{{ $pendingTransactions->total() }}</span>
            @endif
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-yaksa-border">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tipe</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Perangkat</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Serial Number</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Bukti</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Pihak Terkait</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Dicatat Oleh</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendingTransactions as $index => $tx)
                        <tr class="hover:bg-amber-50/30 transition-all duration-200"
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
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ strtoupper(substr($tx->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-gray-600 text-xs">{{ $tx->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Approve Form -->
                                    <form method="POST" action="{{ route('approvals.approve', $tx) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1" title="Terima">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Terima
                                        </button>
                                    </form>
                                    <!-- Reject Form -->
                                    <form method="POST" action="{{ route('approvals.reject', $tx) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-colors shadow-sm flex items-center gap-1" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="font-medium text-gray-600">Tidak ada request transaksi</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pendingTransactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $pendingTransactions->appends(request()->except('tx_page'))->links('pagination::tailwind') }}
            </div>
            @else
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-700">{{ $pendingTransactions->total() }}</span> pending
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
