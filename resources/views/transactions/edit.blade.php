@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ 
    loaded: false,
    init() { setTimeout(() => this.loaded = true, 300); }
}" x-init="init()">
    
    <div class="mb-6 flex items-center justify-between" x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Transaksi</h1>
            <p class="text-sm text-gray-500 mt-1">Ubah detail transaksi barang masuk atau keluar.</p>
        </div>
        <a href="{{ route('transactions.index', ['gudang' => $transaction->item->gudang ?? 'universal']) }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 hover:text-gray-900 transition-all shadow-sm">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Perangkat -->
                <div class="space-y-1.5" x-data="searchableSelect()" x-init="initItems()">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Perangkat <span class="text-red-500">*</span></label>
                    <input type="hidden" name="item_id" :value="selectedId" required>
                    <div class="relative">
                        <div class="relative">
                            <input type="text" x-model="search" @focus="dropOpen = true" @click="dropOpen = true" @input="dropOpen = true"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red outline-none shadow-sm transition-all text-gray-700"
                                placeholder="Cari perangkat..." autocomplete="off">
                            <button type="button" x-show="selectedId" @click="clearSelection()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div x-show="selectedId && !dropOpen" x-cloak class="mt-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 font-medium flex items-center justify-between">
                            <span x-text="selectedLabel"></span>
                        </div>
                        <div x-show="dropOpen" x-cloak @click.outside="dropOpen = false"
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto">
                            <template x-for="item in filteredItems" :key="item.id">
                                <button type="button" @click="selectItem(item)" class="w-full text-left px-4 py-2.5 text-sm hover:bg-red-50 hover:text-red-700 transition-colors border-b border-gray-50 last:border-0"
                                        :class="selectedId == item.id ? 'bg-red-50 text-red-700 font-semibold' : 'text-gray-700'">
                                    <span>
                                        <span x-text="item.nama" class="font-medium"></span>
                                        <span x-text="'(' + item.sn + ')'" class="text-gray-400 text-xs ml-1 font-mono"></span>
                                    </span>
                                </button>
                            </template>
                            <div x-show="filteredItems.length === 0" class="px-4 py-4 text-center text-gray-400 text-sm">
                                Perangkat tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipe Transaksi -->
                <div class="space-y-1.5" x-data="{ tipe: '{{ old('tipe_transaksi', $transaction->tipe_transaksi) }}' }">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Tipe Transaksi <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 h-[42px]">
                        <label class="flex-1 relative cursor-pointer group">
                            <input type="radio" name="tipe_transaksi" value="in" x-model="tipe" class="peer sr-only" required>
                            <div class="h-full flex items-center justify-center gap-2 rounded-xl border-2 transition-all"
                                 :class="tipe === 'in' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 bg-gray-50 text-gray-500 group-hover:border-blue-300'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                <span class="text-sm font-semibold">Masuk (IN)</span>
                            </div>
                        </label>
                        <label class="flex-1 relative cursor-pointer group">
                            <input type="radio" name="tipe_transaksi" value="out" x-model="tipe" class="peer sr-only">
                            <div class="h-full flex items-center justify-center gap-2 rounded-xl border-2 transition-all"
                                 :class="tipe === 'out' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-200 bg-gray-50 text-gray-500 group-hover:border-amber-300'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                <span class="text-sm font-semibold">Keluar (OUT)</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_transaksi" required value="{{ old('tanggal_transaksi', $transaction->tanggal_transaksi->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Status Approval <span class="text-red-500">*</span></label>
                    <div class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 font-medium">
                        {{ ucfirst($transaction->status) }}
                    </div>
                </div>

                <!-- Pengirim -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Pengirim</label>
                    <input type="text" name="pengirim" value="{{ old('pengirim', $transaction->pengirim) }}" placeholder="Nama / Vendor pengirim"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>

                <!-- Penerima -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Penerima</label>
                    <input type="text" name="penerima" value="{{ old('penerima', $transaction->penerima) }}" placeholder="Nama / Vendor penerima"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none">
                </div>
            </div>

            <!-- Bukti File -->
            <div class="space-y-2 mt-6">
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Bukti File <span class="text-gray-400 font-normal lowercase">(Opsional jika tidak diubah)</span></label>
                
                @if($transaction->bukti_foto)
                    <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-xl flex items-center gap-4">
                        @if(str_ends_with(strtolower($transaction->bukti_foto), '.pdf'))
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center text-red-500 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                        @else
                            <img src="{{ url('file/' . $transaction->bukti_foto) }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">File Saat Ini</p>
                            <p class="text-xs text-gray-500 truncate">{{ basename($transaction->bukti_foto) }}</p>
                        </div>
                        <a href="{{ url('file/' . $transaction->bukti_foto) }}" target="_blank" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-colors">
                            Lihat
                        </a>
                    </div>
                @endif

                <div x-data="{ 
                    fileName: '', 
                    preview: null,
                    isPdf: false,
                    handleFile(e) { 
                        const file = e.target.files[0]; 
                        if (!file) return;
                        this.fileName = file.name;
                        if (file.type === 'application/pdf' || file.name.endsWith('.pdf')) {
                            this.isPdf = true;
                            this.preview = null;
                        } else {
                            this.isPdf = false;
                            const url = URL.createObjectURL(file);
                            this.preview = url;
                        }
                    },
                    clearPhoto() {
                        this.fileName = '';
                        this.preview = null;
                        this.isPdf = false;
                        this.$refs.realInput.value = '';
                    }
                }">
                    <input type="file" name="bukti_foto" accept="image/*,application/pdf" x-ref="realInput" class="hidden" @change="handleFile($event)">
                    
                    <!-- Preview Baru -->
                    <template x-if="preview && !isPdf">
                        <div class="relative mb-3 rounded-xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50 max-w-sm">
                            <img :src="preview" class="w-full h-40 object-contain">
                            <button type="button" @click="clearPhoto()" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    <template x-if="isPdf">
                        <div class="relative mb-3 rounded-xl p-4 border border-blue-200 bg-blue-50 flex items-center shadow-sm max-w-sm">
                            <svg class="w-8 h-8 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <div class="flex-1 min-w-0 pr-2">
                                <p class="text-sm font-semibold text-blue-700 truncate" x-text="fileName"></p>
                                <p class="text-[10px] text-blue-500">Dokumen PDF terpilih (Baru)</p>
                            </div>
                            <button type="button" @click="clearPhoto()" class="flex-shrink-0 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>

                    <button type="button" x-show="!preview && !isPdf" @click="$refs.realInput.click()" 
                            class="px-4 py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm font-semibold text-gray-600 hover:border-yaksa-red hover:text-yaksa-red hover:bg-red-50 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Upload Bukti Baru
                    </button>
                    <p class="text-[10px] text-gray-400 mt-1.5">Format: JPG, PNG, PDF. Maks 5MB. Biarkan kosong jika tidak ingin mengubah.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('transactions.index', ['gudang' => $transaction->item->gudang ?? 'universal']) }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-yaksa-red hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function searchableSelect() {
        return {
            search: '',
            selectedId: '{{ old('item_id', $transaction->item_id) }}',
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
                
                // Set initial label
                if (this.selectedId) {
                    const found = this.allItems.find(i => i.id == this.selectedId);
                    if (found) {
                        this.selectedLabel = found.nama + ' (' + found.sn + ')';
                    }
                }
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
@endsection
