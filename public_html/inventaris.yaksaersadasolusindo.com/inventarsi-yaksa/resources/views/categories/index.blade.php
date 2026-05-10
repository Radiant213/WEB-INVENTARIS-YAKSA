@extends('layouts.app')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4" style="animation: fadeInUp 0.4s ease-out;">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar tipe/kategori barang inventaris</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Kategori -->
    <div class="lg:col-span-1" style="animation: fadeInUp 0.4s ease-out 0.1s both;">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yaksa-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Kategori Baru
            </h2>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="gudang" value="{{ $activeGudang }}">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: SFP / Alat Kantor" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all outline-none">
                    @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" placeholder="Keterangan singkat kategori..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yaksa-red/20 focus:border-yaksa-red shadow-sm transition-all outline-none"></textarea>
                    @error('description') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm focus:ring-4 focus:ring-red-100">
                    Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Kategori -->
    <div class="lg:col-span-2" style="animation: fadeInUp 0.4s ease-out 0.2s both;" x-data="{ editModalOpen: false, editId: '', editName: '', editDesc: '', editUrl: '' }">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Deskripsi</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Total Barang</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded-md">{{ $category->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $category->description ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center bg-blue-50 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    {{ $category->items_count }} item
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button @click="editModalOpen = true; editId = {{ $category->id }}; editName = '{{ addslashes($category->name) }}'; editDesc = '{{ addslashes($category->description ?? '') }}'; editUrl = '{{ route('categories.update', $category->id) }}'" 
                                            class="text-gray-400 hover:text-yaksa-red transition-colors" title="Edit">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form id="delete-form-cat-{{ $category->id }}" method="POST" action="{{ route('categories.destroy', $category) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button type="button" onclick="confirmDeleteCat({{ $category->id }})" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Belum ada kategori tersimpan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $categories->links('pagination::tailwind') }}
            </div>
            @endif
        </div>

        <!-- Edit Modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center">
            <div x-show="editModalOpen" @click="editModalOpen = false" x-transition.opacity class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div x-show="editModalOpen" x-transition class="relative bg-white w-full max-w-sm mx-4 rounded-2xl shadow-2xl p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Edit Kategori</h3>
                <form :action="editUrl" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" x-model="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:border-yaksa-red outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="editDesc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:border-yaksa-red outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 border border-gray-300 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDeleteCat(catId) {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Kategori akan dihapus. Perangkat dalam kategori ini tidak akan terhapus, namun kolom kategorinya akan dikosongkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg', cancelButton: 'rounded-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-cat-' + catId).submit();
            }
        })
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
