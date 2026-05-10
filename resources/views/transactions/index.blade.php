    @extends('layouts.app')

    @section('content')
    <div x-data="{ loaded: false, modalOpen: false, modalType: 'in' }" x-init="setTimeout(() => loaded = true, 600)">

        <!-- ===== SKELETON LOADING ===== -->
        <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <div class="skeleton skeleton-title" style="width: 220px;"></div>
                    <div class="skeleton skeleton-text-sm" style="width: 280px;"></div>
                </div>
                <div class="flex space-x-3">
                    <div class="skeleton" style="width: 120px; height: 38px; border-radius: 0.75rem;"></div>
                    <div class="skeleton" style="width: 130px; height: 38px; border-radius: 0.75rem;"></div>
                </div>
            </div>
            <div class="flex justify-between items-center mb-4">
                <div class="skeleton skeleton-text" style="width: 130px; margin-bottom:0;"></div>
                <div class="flex gap-2">
                    <div class="skeleton" style="width: 110px; height: 38px; border-radius: 0.75rem;"></div>
                    <div class="skeleton" style="width: 130px; height: 38px; border-radius: 0.75rem;"></div>
                    <div class="skeleton" style="width: 100px; height: 38px; border-radius: 0.75rem;"></div>
                    <div class="skeleton" style="width: 100px; height: 38px; border-radius: 0.75rem;"></div>
                </div>
            </div>
            <div class="skeleton-card" style="padding:0;">
                <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                    @for($i = 0; $i < 9; $i++)
                    <div class="skeleton skeleton-text-sm" style="width: {{ rand(45, 75) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    @endfor
                </div>
                @for($i = 0; $i < 7; $i++)
                <div class="skeleton-table-row">
                    <div class="skeleton skeleton-badge" style="flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(100, 160) }}px; margin-bottom:0; flex:1;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(70, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 65px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 40px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(60, 90) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(60, 90) }}px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-text" style="width: 60px; margin-bottom:0; flex-shrink:0;"></div>
                    <div class="skeleton skeleton-badge" style="flex-shrink:0; width:3.5rem;"></div>
                </div>
                @endfor
            </div>
        </div>

        <!-- ===== REAL CONTENT ===== -->

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4"
            x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Log Transaksi {{ $activeGudang == 'universal' ? 'Semua Gudang' : 'Gudang ' . ucfirst($activeGudang) }}</h1>
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

        <!-- Filters Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-3 sm:space-y-0"
            x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="text-sm text-gray-500">
                Total: <span class="font-semibold text-gray-800">{{ $transactions->total() }}</span> transaksi
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-2 w-full sm:w-auto items-center" id="txFilterForm">
                    
                    <input type="hidden" name="gudang" value="{{ $activeGudang }}">
                    
                    {{-- Tipe Transaksi --}}
                    <select name="tipe" onchange="document.getElementById('txFilterForm').submit()"
                            class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2.5 bg-white hover:border-gray-400 transition-all duration-200 cursor-pointer">
                        <option value="all" {{ request('tipe') == 'all' || !request('tipe') ? 'selected' : '' }}>Semua Tipe</option>
                        <option value="in" {{ request('tipe') == 'in' ? 'selected' : '' }}>↓ Barang Masuk</option>
                        <option value="out" {{ request('tipe') == 'out' ? 'selected' : '' }}>↑ Barang Keluar</option>
                    </select>

                    {{-- Kategori Barang --}}
                    <select name="category_id" onchange="document.getElementById('txFilterForm').submit()"
                            class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2.5 bg-white hover:border-gray-400 transition-all duration-200 cursor-pointer">
                        <option value="all" {{ request('category_id') == 'all' || !request('category_id') ? 'selected' : '' }}>Semua Kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" onchange="document.getElementById('txFilterForm').submit()"
                            class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2 bg-white transition-all duration-200 cursor-pointer" title="Mulai Tanggal">
                        <span class="text-gray-400">-</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" onchange="document.getElementById('txFilterForm').submit()"
                            class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2 bg-white transition-all duration-200 cursor-pointer" title="Sampai Tanggal">
                    </div>
                </form>
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
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Bukti</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Pengirim</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Penerima</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Dicatat Oleh</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Status</th>
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
                            <td class="px-6 py-4 text-center">
                                @if($tx->bukti_foto)
                                    @if(str_ends_with(strtolower($tx->bukti_foto), '.pdf'))
                                        <a href="{{ url('file/' . $tx->bukti_foto) }}" target="_blank" class="group relative inline-flex transition-transform hover:scale-110 items-center justify-center w-10 h-10 bg-red-50 rounded-lg border border-red-200 text-red-500" title="Lihat PDF">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
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
                                    <span class="text-gray-300 text-xs italic">No file</span>
                                @endif
                            </td>
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
                            <td class="px-6 py-4 text-center">
                                @if($tx->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Approved
                                    </span>
                                @elseif($tx->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            @if(Auth::user() && Auth::user()->isAdmin())
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('transactions.edit', $tx) }}" class="inline-flex items-center justify-center text-gray-400 hover:text-yaksa-red transition-all duration-200 hover:scale-110" title="Edit Log">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form id="delete-form-tx-{{ $tx->id }}" method="POST" action="{{ route('transactions.destroy', $tx) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDeleteTx({{ $tx->id }})" class="inline-flex items-center justify-center text-gray-400 hover:text-red-500 transition-all duration-200 hover:scale-110" title="Delete Log">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
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
                class="relative bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col max-h-[90vh]">
                
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

                <form method="POST" action="{{ route('transactions.store') }}" class="p-6 space-y-4 flex-1 overflow-y-auto min-h-0" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tipe_transaksi" :value="modalType">

                    <div class="space-y-1.5" x-data="searchableSelect()" x-init="initItems()">
                        <label class="block text-xs font-semibold text-gray-700">Pilih Perangkat <span class="text-red-500">*</span></label>
                        <input type="hidden" name="item_id" :value="selectedId" required>
                        <div class="relative">
                            <!-- Search Input -->
                            <div class="relative">
                                <input type="text" x-model="search" @focus="dropOpen = true" @click="dropOpen = true"
                                    @input="dropOpen = true"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red outline-none shadow-sm transition-all text-gray-700"
                                    placeholder="Ketik untuk mencari barang..."
                                    autocomplete="off">
                                <button type="button" x-show="selectedId" @click="clearSelection()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <!-- Selected Badge -->
                            <div x-show="selectedId && !dropOpen" x-cloak class="mt-1.5 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 font-medium flex items-center justify-between">
                                <span x-text="selectedLabel"></span>
                                <button type="button" @click="clearSelection()" class="text-gray-400 hover:text-red-500 ml-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <!-- Dropdown List -->
                            <div x-show="dropOpen" x-cloak @click.outside="dropOpen = false"
                                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                                <template x-for="item in filteredItems" :key="item.id">
                                    <button type="button" @click="selectItem(item)" class="w-full text-left px-3 py-2 text-sm hover:bg-red-50 hover:text-red-700 transition-colors duration-100 flex items-center justify-between border-b border-gray-50 last:border-0"
                                            :class="selectedId == item.id ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700'">
                                        <span>
                                            <span x-text="item.nama" class="font-medium"></span>
                                            <span x-text="'(' + item.sn + ')'" class="text-gray-400 text-xs ml-1 font-mono"></span>
                                        </span>
                                        <svg x-show="selectedId == item.id" class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    </button>
                                </template>
                                <div x-show="filteredItems.length === 0" class="px-3 py-4 text-center text-gray-400 text-xs">
                                    Perangkat tidak ditemukan
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Tanggal Transaksi <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_transaksi" required value="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Bukti File <span class="text-red-500">*</span></label>
                            <div x-data="{ 
                                fileName: '', 
                                preview: null,
                                isPdf: false,
                                handleFile(e) { 
                                    const file = e.target.files[0]; 
                                    if (!file) return;
                                    this.fileName = file.name;
                                    
                                    const dt = new DataTransfer();
                                    dt.items.add(file);
                                    this.$refs.realInput.files = dt.files;
                                    
                                    if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
                                        this.isPdf = true;
                                        this.preview = null;
                                    } else {
                                        this.isPdf = false;
                                        const img = new Image();
                                        const url = URL.createObjectURL(file);
                                        img.onload = () => {
                                            const MAX = 800;
                                            let w = img.width, h = img.height;
                                            if (w > MAX || h > MAX) {
                                                if (w > h) { h = Math.round(h * MAX / w); w = MAX; }
                                                else { w = Math.round(w * MAX / h); h = MAX; }
                                            }
                                            const canvas = document.createElement('canvas');
                                            canvas.width = w; canvas.height = h;
                                            canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                                            this.preview = canvas.toDataURL('image/jpeg', 0.7);
                                            URL.revokeObjectURL(url);
                                        };
                                        img.src = url;
                                    }
                                },
                                clearPhoto() {
                                    this.fileName = '';
                                    this.preview = null;
                                    this.isPdf = false;
                                    this.$refs.realInput.value = '';
                                    if(this.$refs.cameraInput) this.$refs.cameraInput.value = '';
                                    if(this.$refs.galleryInput) this.$refs.galleryInput.value = '';
                                    if(this.$refs.pdfInput) this.$refs.pdfInput.value = '';
                                }
                            }">
                                <!-- Hidden real input for form submission -->
                                <input type="file" name="bukti_foto" accept="image/*,application/pdf" required x-ref="realInput" class="hidden">
                                
                                <!-- Image Preview -->
                                <template x-if="preview && !isPdf">
                                    <div class="relative mb-2 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50">
                                        <img :src="preview" class="w-full h-32 object-contain">
                                        <button type="button" @click="clearPhoto()" 
                                                class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600"
                                                style="transition: all 0.2s ease">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-3 py-1.5">
                                            <span class="text-[10px] text-white truncate block" x-text="fileName"></span>
                                        </div>
                                    </div>
                                </template>

                                <!-- PDF Preview -->
                                <template x-if="isPdf">
                                    <div class="relative mb-2 rounded-xl p-3 border border-gray-200 bg-red-50 flex items-center shadow-sm">
                                        <svg class="w-8 h-8 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <div class="flex-1 min-w-0 pr-2">
                                            <p class="text-sm font-semibold text-red-700 truncate" x-text="fileName"></p>
                                            <p class="text-[10px] text-red-500">Dokumen PDF terpilih</p>
                                        </div>
                                        <button type="button" @click="clearPhoto()" 
                                                class="flex-shrink-0 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                            <!-- Upload Buttons -->
                            <div x-show="!preview && !isPdf" class="grid grid-cols-2 gap-2">
                                <!-- Camera -->
                                <label class="relative cursor-pointer">
                                    <input type="file" accept="image/*" capture="environment" x-ref="cameraInput"
                                        class="hidden" @change="handleFile($event)">
                                    <div class="flex flex-col items-center justify-center gap-1.5 px-3 py-3 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition-all">
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-[11px] font-semibold text-gray-600">Kamera</span>
                                    </div>
                                </label>
                                <!-- Gallery / PDF -->
                                <label class="relative cursor-pointer">
                                    <input type="file" accept="image/*,application/pdf" x-ref="galleryInput"
                                        class="hidden" @change="handleFile($event)">
                                    <div class="flex flex-col items-center justify-center gap-1.5 px-3 py-3 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 hover:bg-green-50 hover:border-green-300 transition-all">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-gray-300 text-xs">/</span>
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-[11px] font-semibold text-gray-600">Galeri / File</span>
                                    </div>
                                </label>
                            </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG, PDF. Maks 5MB.</p>
                        </div>
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

        function searchableSelect() {
            return {
                search: '',
                selectedId: null,
                selectedLabel: '',
                dropOpen: false,
                allItems: [],
                get filteredItems() {
                    if (!this.search) return this.allItems;
                    const q = this.search.toLowerCase();
                    return this.allItems.filter(i => {
                        const namaMatch = i.nama && i.nama.toLowerCase().includes(q);
                        const snMatch = i.sn && i.sn.toLowerCase().includes(q);
                        return namaMatch || snMatch;
                    });
                },
                initItems() {
                    this.allItems = @json($items->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_perangkat, 'sn' => $i->serial_number]));
                },
                selectItem(item) {
                    this.selectedId = item.id;
                    this.selectedLabel = item.nama + ' (' + item.sn + ')';
                    this.search = '';
                    this.dropOpen = false;
                },
                clearSelection() {
                    this.selectedId = null;
                    this.selectedLabel = '';
                    this.search = '';
                }
            };
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
