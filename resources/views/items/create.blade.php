@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Baru</h1>
            <p class="text-sm text-gray-500 mt-1">Isi data perangkat baru ke dalam inventaris.</p>
        </div>
        <a href="{{ route('items.index', ['gudang' => $activeGudang]) }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow duration-300 max-w-3xl"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        
        <form method="POST" action="{{ route('items.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="gudang" value="{{ $activeGudang }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Perangkat -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="nama_perangkat" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Nama Perangkat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perangkat" id="nama_perangkat" value="{{ old('nama_perangkat') }}" required
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none"
                           placeholder="contoh: FortiGate-100F">
                    @error('nama_perangkat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Serial Number -->
                <div class="space-y-1.5">
                    <label for="serial_number" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Serial Number <span class="text-red-500">*</span></label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" required
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none"
                           placeholder="contoh: FG100FTK23000055">
                    @error('serial_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none cursor-pointer bg-white">
                        <option value="">-- Pilih Status --</option>
                        <option value="Ready" {{ old('status') == 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Barang Keluar" {{ old('status') == 'Barang Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        <option value="Barang RMA" {{ old('status') == 'Barang RMA' ? 'selected' : '' }}>Barang RMA</option>
                        <option value="Barang Rusak" {{ old('status') == 'Barang Rusak' ? 'selected' : '' }}>Barang Rusak</option>
                        <option value="Milik Internal" {{ old('status') == 'Milik Internal' ? 'selected' : '' }}>Milik Internal</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                
                <!-- Kategori Barang -->
                <div class="space-y-1.5">
                    <label for="category_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Kategori Barang</label>
                    <select name="category_id" id="category_id" class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none cursor-pointer bg-white">
                        <option value="">-- Opsional --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Keterangan Barang -->
                <div class="space-y-1.5">
                    <label for="status_barang" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Keterangan (Jenis/Seri)</label>
                    <input type="text" name="status_barang" id="status_barang" value="{{ old('status_barang') }}"
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none"
                           placeholder="contoh: SFP LAN, dll">
                </div>

                <!-- OS Version -->
                <div class="space-y-1.5">
                    <label for="os_version" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">OS Version</label>
                    <input type="text" name="os_version" id="os_version" value="{{ old('os_version') }}"
                           class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none"
                           placeholder="contoh: 7.4.8">
                </div>

                <!-- Lokasi Device -->
                <div class="space-y-1.5">
                    <label for="lokasi_device" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Lokasi Device</label>
                    <select name="lokasi_device" id="lokasi_device"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none cursor-pointer bg-white">
                        <option value="">-- Opsional --</option>
                        <option value="Gudang ATS" {{ old('lokasi_device') == 'Gudang ATS' ? 'selected' : '' }}>Gudang ATS</option>
                        <option value="Gudang YES-Jkt" {{ old('lokasi_device') == 'Gudang YES-Jkt' ? 'selected' : '' }}>Gudang YES-Jkt</option>
                        <option value="Yes Bali" {{ old('lokasi_device') == 'Yes Bali' ? 'selected' : '' }}>Yes Bali</option>
                        <option value="Delivered" {{ old('lokasi_device') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1.5 mt-6">
                <label for="description" class="block text-xs font-semibold text-gray-700 uppercase tracking-wide">Description / Catatan</label>
                <textarea name="description" id="description" rows="3"
                          class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all text-gray-700 outline-none resize-none"
                          placeholder="Catatan tambahan (opsional)...">{{ old('description') }}</textarea>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-gray-100 flex justify-end gap-3 mt-6">
                <a href="{{ route('items.index', ['gudang' => $activeGudang]) }}" class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-yaksa-red hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-500/30 transition-all transform hover:-translate-y-0.5">
                    Simpan Barang
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
