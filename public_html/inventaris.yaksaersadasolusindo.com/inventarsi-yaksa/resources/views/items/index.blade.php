@extends('layouts.app')

@section('content')
<div x-data="masterBarang()" x-init="init()">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Master Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data inventaris, serial number, dan status ketersediaan.</p>
        </div>
        <div class="flex space-x-3">
            @if(Auth::user() && Auth::user()->isAdmin())
            <a href="{{ route('items.create') }}" class="group px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl text-sm font-semibold hover:from-red-500 hover:to-red-400 hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 active:scale-95 transform hover:-translate-y-0.5 no-underline">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Barang
                </span>
            </a>
            @endif
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-3 sm:space-y-0"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="text-sm text-gray-500 w-full sm:w-auto">
            Total: <span class="font-semibold text-gray-800">{{ $items->total() }}</span> perangkat
        </div>
        <div class="flex flex-wrap w-full sm:w-auto gap-2">
            <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap gap-2 w-full sm:w-auto" id="filterForm">
                
                {{-- Kategori Filter --}}
                <select name="category_id" onchange="document.getElementById('filterForm').submit()"
                        class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2.5 bg-white hover:border-gray-400 transition-all duration-200 cursor-pointer">
                    <option value="all" {{ request('category_id') == 'all' || !request('category_id') ? 'selected' : '' }}>Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2.5 bg-white hover:border-gray-400 transition-all duration-200 cursor-pointer">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                    @foreach($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                <select name="lokasi" onchange="document.getElementById('filterForm').submit()"
                        class="border border-gray-300 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red p-2.5 bg-white hover:border-gray-400 transition-all duration-200 cursor-pointer">
                    <option value="all" {{ request('lokasi') == 'all' || !request('lokasi') ? 'selected' : '' }}>Semua Lokasi</option>
                    @foreach($lokasis as $l)
                    <option value="{{ $l }}" {{ request('lokasi') == $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div id="items-table-container">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300"
             x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-4 w-10"></th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-700">Nama Perangkat</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-700">S/N</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-700">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-700">Keterangan</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-gray-700">Lokasi</th>
                        <th scope="col" class="px-4 py-4 text-center font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $index => $item)
                    <tbody x-data="{ expanded: false }" class="border-b border-gray-100">
                    <tr class="group hover:bg-red-50/30 transition-all duration-200 cursor-pointer"
                        :class="expanded ? 'bg-gray-50/50' : ''"
                        style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                        
                        <td class="px-4 py-4 text-center" @click="expanded = !expanded">
                            <button class="text-gray-300 group-hover:text-yaksa-red transition-all duration-300 focus:outline-none hover:scale-125">
                                <svg class="w-5 h-5 transform transition-transform duration-300 ease-out" :class="{'rotate-180 text-yaksa-red': expanded}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap group-hover:text-yaksa-red transition-colors duration-200" @click="expanded = !expanded">
                            {{ $item->nama_perangkat }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500" @click="expanded = !expanded">{{ $item->serial_number }}</td>
                        <td class="px-6 py-4" @click="expanded = !expanded">
                            @if($item->status == 'Ready')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/50">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Ready
                                </span>
                            @elseif($item->status == 'Barang Keluar')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/50">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-amber-500 rounded-full"></span> Keluar
                                </span>
                            @elseif($item->status == 'Barang RMA')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/50">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-purple-500 rounded-full"></span> RMA / Spare
                                </span>
                            @elseif($item->status == 'Barang Rusak')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-red-50 text-red-700 border border-red-200/50">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span> Rusak
                                </span>
                            @elseif($item->status == 'Milik Internal' || $item->status == 'Digunakan Internal')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/50">
                                    <span class="w-1.5 h-1.5 mr-1.5 bg-blue-500 rounded-full"></span> Internal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-200/50">{{ $item->status ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500" @click="expanded = !expanded">{{ $item->status_barang ?? '-' }}</td>
                        <td class="px-6 py-4" @click="expanded = !expanded">
                            @if($item->lokasi_device == 'Gudang ATS')
                                <span class="inline-flex items-center text-xs font-medium text-gray-600">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $item->lokasi_device }}
                                </span>
                            @elseif($item->lokasi_device == 'Delivered')
                                <span class="inline-flex items-center text-xs font-medium text-green-600">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ $item->lokasi_device }}
                                </span>
                            @else
                                <span class="text-xs text-gray-500">{{ $item->lokasi_device ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if(Auth::user() && Auth::user()->isAdmin())
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('items.edit', $item) }}" class="text-gray-300 hover:text-yaksa-red transition-all duration-200 hover:scale-110 inline-block" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form id="delete-form-item-{{ $item->id }}" method="POST" action="{{ route('items.destroy', $item) }}" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button" onclick="confirmDeleteItem({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition-all duration-200 hover:scale-110" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>

                    <!-- Expanded Row -->
                    <tr x-show="expanded" x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="bg-gradient-to-r from-gray-50/80 to-white">
                        <td colspan="7" class="px-10 py-6 border-b border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                                <div class="space-y-4">
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">OS Version</span>
                                        <span class="text-gray-900 font-medium text-base">{{ $item->os_version ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tanggal Terdaftar</span>
                                        <span class="text-gray-700 font-medium">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    @if($item->status == 'Barang Keluar' && $item->transactions->where('tipe_transaksi', 'out')->count() > 0)
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 text-amber-600">Tanggal Keluar Terakhir</span>
                                        <span class="text-gray-700">{{ $item->transactions->where('tipe_transaksi', 'out')->sortByDesc('tanggal_transaksi')->first()->tanggal_transaksi->format('d/m/Y') }}</span>
                                    </div>
                                    @endif
                                    <div class="pt-2">
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Description</span>
                                        <span class="text-gray-700">{{ $item->description ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="space-y-3 md:col-span-2">
                                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Riwayat Transaksi Terbaru</span>
                                    @if($item->transactions->count() > 0)
                                        <div class="border border-gray-200 rounded-xl text-xs bg-white overflow-hidden">
                                            <table class="w-full">
                                                <tr class="bg-gray-50 text-gray-500">
                                                    <th class="p-3 text-left font-semibold">Tipe</th>
                                                    <th class="p-3 text-left font-semibold">Tanggal</th>
                                                    <th class="p-3 text-left font-semibold">Pengirim</th>
                                                    <th class="p-3 text-left font-semibold">Penerima</th>
                                                </tr>
                                                @foreach($item->transactions->sortByDesc('tanggal_transaksi')->take(3) as $tx)
                                                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors duration-150">
                                                    <td class="p-3">
                                                        @if($tx->tipe_transaksi == 'in')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 font-semibold">↓ IN</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 text-amber-600 font-semibold">↑ OUT</span>
                                                        @endif
                                                    </td>
                                                    <td class="p-3">{{ $tx->tanggal_transaksi->format('d/m/Y') }}</td>
                                                    <td class="p-3">{{ $tx->pengirim ?? '-' }}</td>
                                                    <td class="p-3">{{ $tx->penerima ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 text-gray-400 italic py-3">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Belum ada riwayat transaksi
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3 flex space-x-2">
                                        <button @click.prevent="openModal('in', {{ $item->id }}, '{{ addslashes($item->nama_perangkat) }}')" class="group/btn px-3 py-2 bg-white border border-gray-300 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-300 rounded-lg text-xs transition-all duration-200 active:scale-95">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 group-hover/btn:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                                Catat Barang Masuk
                                            </span>
                                        </button>
                                        <button @click.prevent="openModal('out', {{ $item->id }}, '{{ addslashes($item->nama_perangkat) }}')" class="group/btn px-3 py-2 bg-white border border-gray-300 text-gray-600 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-300 rounded-lg text-xs transition-all duration-200 active:scale-95">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 group-hover/btn:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                                Catat Barang Keluar
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    @empty
                    <tbody>
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                            <h3 class="mt-3 text-sm font-semibold text-gray-900">Belum ada data barang</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan master barang baru.</p>
                        </td>
                    </tr>
                    </tbody>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $items->links('pagination::tailwind') }}
        </div>
        @else
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <span>Menampilkan <span class="font-semibold text-gray-700">{{ $items->count() }}</span> perangkat</span>
        </div>
        @endif
        </div>
    </div>

    <!-- Transaction Modal -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
        <!-- Backdrop -->
        <div x-show="modalOpen" @click="modalOpen = false" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

        <!-- Modal Dialog -->
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
                <input type="hidden" name="item_id" :value="modalItemId">
                <input type="hidden" name="tipe_transaksi" :value="modalType">

                <div class="p-3 bg-gray-50 border border-gray-200 rounded-xl mb-4">
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-1">Perangkat yang dipilih</p>
                    <p class="font-semibold text-sm text-gray-800" x-text="modalItemName"></p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" required value="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red">
                </div>

                <div class="space-y-1.5" x-show="modalType === 'in'">
                    <label class="block text-xs font-semibold text-gray-700">Pengirim (Opsional)</label>
                    <input type="text" name="pengirim" placeholder="Nama / Vendor / Instansi pengirim"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red">
                </div>

                <div class="space-y-1.5" x-show="modalType === 'out'">
                    <label class="block text-xs font-semibold text-gray-700">Penerima (Opsional)</label>
                    <input type="text" name="penerima" placeholder="Nama / Vendor / Instansi penerima"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red">
                </div>

                <div class="pt-4 mt-2 border-t border-gray-100 flex justify-end gap-2">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
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
    function confirmDeleteItem(itemId) {
        Swal.fire({
            title: 'Hapus Barang?',
            text: "Barang beserta riwayat transaksinya akan dihapus selamanya!",
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
                document.getElementById('delete-form-item-' + itemId).submit();
            }
        })
    }
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    [x-cloak] { display: none !important; }
</style>

<script>
function masterBarang() {
    return {
        loaded: false,
        modalOpen: false,
        modalType: 'in',
        modalItemId: null,
        modalItemName: '',
        init() {
            setTimeout(() => this.loaded = true, 50);
        },
        openModal(type, itemId, itemName) {
            this.modalType = type;
            this.modalItemId = itemId;
            this.modalItemName = itemName;
            this.modalOpen = true;
        }
    };
}
</script>
@endsection
