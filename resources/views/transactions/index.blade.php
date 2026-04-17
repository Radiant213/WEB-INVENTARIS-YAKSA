@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false, modalOpen: false, modalType: 'in' }" x-init="setTimeout(() => loaded = true, 50)">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log Transaksi</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat pergerakan barang masuk dan keluar.</p>
        </div>
        <div class="flex space-x-3">
            <button @click.prevent="modalOpen = true; modalType = 'in'" class="group px-4 py-2 bg-white text-blue-600 hover:bg-blue-50 hover:text-blue-700 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center gap-2 shadow-sm border border-blue-200">
                <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                Barang Masuk
            </button>
            <button @click.prevent="modalOpen = true; modalType = 'out'" class="group px-4 py-2 bg-white text-amber-600 hover:bg-amber-50 hover:text-amber-700 rounded-xl text-sm font-semibold transition-all duration-300 flex items-center gap-2 shadow-sm border border-amber-200">
                <svg class="w-4 h-4 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                Barang Keluar
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-yaksa-border">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-700">Tipe</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Perangkat</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Serial Number</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Pengirim</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Penerima</th>
                        <th class="px-6 py-4 font-semibold text-gray-700">Dicatat Oleh</th>
                        @if(Auth::user() && Auth::user()->isAdmin())
                        <th class="px-6 py-4 font-semibold text-gray-700 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $index => $tx)
                    <tr class="hover:bg-red-50/30 transition-all duration-200"
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
                        <td class="px-6 py-4 text-gray-600">{{ $tx->pengirim ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $tx->penerima ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ strtoupper(substr($tx->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="text-gray-600 text-xs">{{ $tx->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        @if(Auth::user() && Auth::user()->isAdmin())
                        <td class="px-6 py-4 text-center">
                            <form id="delete-form-tx-{{ $tx->id }}" method="POST" action="{{ route('transactions.destroy', $tx) }}" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" onclick="confirmDeleteTx({{ $tx->id }})" class="text-gray-300 hover:text-red-500 transition-all duration-200 hover:scale-110" title="Delete Log">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p class="font-medium text-gray-600">Belum ada transaksi</p>
                            <p class="text-sm mt-1">Log akan muncul saat ada barang masuk / keluar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $transactions->links('pagination::tailwind') }}
        </div>
        @else
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 text-sm text-gray-500">
            Total: <span class="font-semibold text-gray-700">{{ $transactions->total() }}</span> transaksi
        </div>
        @endif
    </div>

    <!-- Transaction Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
        <div x-show="modalOpen" @click="modalOpen = false" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="relative bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg x-show="modalType === 'in'" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    <svg x-show="modalType === 'out'" class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    <span x-text="modalType === 'in' ? 'Catat Barang Masuk' : 'Catat Barang Keluar'"></span>
                </h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-red-500 transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('transactions.store') }}" class="p-6 space-y-4 flex-1">
                @csrf
                <input type="hidden" name="tipe_transaksi" :value="modalType">

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700">Pilih Perangkat <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="item_id" required class="appearance-none w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red outline-none shadow-sm transition-all text-gray-700">
                            <option value="">-- Pilih --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_perangkat }} ({{ $item->serial_number }})</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" required value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>

                <div class="space-y-1.5" x-show="modalType === 'in'">
                    <label class="block text-xs font-semibold text-gray-700">Pengirim (Opsional)</label>
                    <input type="text" name="pengirim" placeholder="Nama / Vendor / Instansi pengirim"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>

                <div class="space-y-1.5" x-show="modalType === 'out'">
                    <label class="block text-xs font-semibold text-gray-700">Penerima (Opsional)</label>
                    <input type="text" name="penerima" placeholder="Nama / Vendor / Instansi penerima"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>

                <div class="pt-4 mt-2 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 text-white rounded-lg text-xs font-bold transition-all hover:shadow-lg focus:outline-none"
                            :class="modalType === 'in' ? 'bg-blue-600 hover:bg-blue-500 shadow-blue-500/20' : 'bg-amber-600 hover:bg-amber-500 shadow-amber-500/20'">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDeleteTx(txId) {
        Swal.fire({
            title: 'Hapus Log Transaksi?',
            text: "Log transaksi akan dihapus secara permanen dari history!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-tx-' + txId).submit();
            }
        })
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection
