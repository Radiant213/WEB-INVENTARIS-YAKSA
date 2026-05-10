@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">
    <div class="flex justify-between items-center mb-6"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Barang</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $item->nama_perangkat }} — {{ $item->serial_number }}</p>
        </div>
        <a href="{{ route('items.index', ['gudang' => $item->gudang]) }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 flex items-center gap-2 no-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm max-w-3xl"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <form method="POST" action="{{ route('items.update', $item) }}" class="space-y-6">
            @csrf @method('PUT')
            <input type="hidden" name="gudang" value="{{ $item->gudang }}">
            <div class="space-y-2 mb-6">
                <label for="category_id" class="block text-sm font-semibold text-gray-700">Kategori Barang (Opsional)</label>
                <select name="category_id" id="category_id" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm text-gray-900 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 cursor-pointer">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nama_perangkat" class="block text-sm font-semibold text-gray-700">Nama Perangkat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perangkat" id="nama_perangkat" value="{{ old('nama_perangkat', $item->nama_perangkat) }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200"
                           placeholder="contoh: FortiGate-100F">
                    @error('nama_perangkat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label for="serial_number" class="block text-sm font-semibold text-gray-700">Serial Number <span class="text-red-500">*</span></label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $item->serial_number) }}" required
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 font-mono focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
                    @error('serial_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label for="status" class="block text-sm font-semibold text-gray-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 cursor-pointer">
                        <option value="Ready" {{ old('status', $item->status) == 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Barang Keluar" {{ old('status', $item->status) == 'Barang Keluar' ? 'selected' : '' }}>Barang Keluar</option>
                        <option value="Barang RMA" {{ old('status', $item->status) == 'Barang RMA' ? 'selected' : '' }}>Barang RMA</option>
                        <option value="Barang Rusak" {{ old('status', $item->status) == 'Barang Rusak' ? 'selected' : '' }}>Barang Rusak</option>
                        <option value="Milik Internal" {{ old('status', $item->status) == 'Milik Internal' ? 'selected' : '' }}>Milik Internal</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="status_barang" class="block text-sm font-semibold text-gray-700">Status Barang</label>
                    <select name="status_barang" id="status_barang" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 cursor-pointer">
                        <option value="">-- Pilih --</option>
                        <option value="Demo" {{ old('status_barang', $item->status_barang) == 'Demo' ? 'selected' : '' }}>Demo</option>
                        <option value="Forsale" {{ old('status_barang', $item->status_barang) == 'Forsale' ? 'selected' : '' }}>Forsale</option>
                        <option value="RMA" {{ old('status_barang', $item->status_barang) == 'RMA' ? 'selected' : '' }}>RMA</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label for="os_version" class="block text-sm font-semibold text-gray-700">OS Version</label>
                    <input type="text" name="os_version" id="os_version" value="{{ old('os_version', $item->os_version) }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200">
                </div>
                <div class="space-y-2">
                    <label for="lokasi_device" class="block text-sm font-semibold text-gray-700">Lokasi Device</label>
                    <select name="lokasi_device" id="lokasi_device" class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 cursor-pointer">
                        <option value="">-- Pilih --</option>
                        <option value="Gudang ATS" {{ old('lokasi_device', $item->lokasi_device) == 'Gudang ATS' ? 'selected' : '' }}>Gudang ATS</option>
                        <option value="Gudang YES-Jkt" {{ old('lokasi_device', $item->lokasi_device) == 'Gudang YES-Jkt' ? 'selected' : '' }}>Gudang YES-Jkt</option>
                        <option value="Yes Bali" {{ old('lokasi_device', $item->lokasi_device) == 'Yes Bali' ? 'selected' : '' }}>Yes Bali</option>
                        <option value="Delivered" {{ old('lokasi_device', $item->lokasi_device) == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
            </div>
            <div class="space-y-2">
                <label for="description" class="block text-sm font-semibold text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm bg-gray-50 focus:bg-white focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red hover:border-gray-400 transition-all duration-200 resize-none">{{ old('description', $item->description) }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('items.index', ['gudang' => $item->gudang]) }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 no-underline">Batal</a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl text-sm font-semibold hover:from-red-500 hover:to-red-400 hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
