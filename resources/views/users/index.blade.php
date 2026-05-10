@extends('layouts.app')

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 500)">
    
    <!-- ===== SKELETON LOADING ===== -->
    <div x-show="!loaded" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="flex justify-between items-center mb-6">
            <div>
                <div class="skeleton skeleton-title" style="width: 200px;"></div>
                <div class="skeleton skeleton-text-sm" style="width: 320px;"></div>
            </div>
            <div class="skeleton" style="width: 130px; height: 42px; border-radius: 0.75rem;"></div>
        </div>

        <div class="skeleton-card" style="padding:0;">
            <div class="skeleton-table-row" style="background:#fafafa; border-bottom:1px solid #e5e7eb;">
                @for($i = 0; $i < 4; $i++)
                <div class="skeleton skeleton-text-sm" style="width: {{ rand(60, 100) }}px; margin-bottom:0; flex-shrink:0;"></div>
                @endfor
            </div>
            @for($i = 0; $i < 5; $i++)
            <div class="skeleton-table-row">
                <div class="flex items-center gap-3 flex-1">
                    <div class="skeleton skeleton-avatar" style="width:2rem; height:2rem; border-radius:0.75rem;"></div>
                    <div class="skeleton skeleton-text" style="width: {{ rand(100, 180) }}px; margin-bottom:0;"></div>
                </div>
                <div class="skeleton skeleton-text" style="width: 150px; margin-bottom:0;"></div>
                <div class="skeleton skeleton-badge" style="flex-shrink:0; width: 5rem;"></div>
                <div class="skeleton skeleton-text" style="width: 40px; margin-bottom:0; flex-shrink:0;"></div>
            </div>
            @endfor
        </div>
    </div>

    <!-- ===== REAL CONTENT ===== -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4"
         x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun pengguna sistem inventaris.</p>
        </div>
        <a href="{{ route('users.create') }}" class="w-full sm:w-auto text-center group px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl text-sm font-semibold hover:from-red-500 hover:to-red-400 hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 active:scale-95 transform hover:-translate-y-0.5 no-underline flex justify-center">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah User
            </span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm"
         x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[600px]">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Nama</th>
                    <th class="px-6 py-4 text-left font-semibold">Email</th>
                    <th class="px-6 py-4 text-left font-semibold">Role</th>
                    <th class="px-6 py-4 text-center font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $index => $user)
                <tr class="hover:bg-red-50/30 transition-all duration-200" style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'superadmin')
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200/50">Super Admin</span>
                        @elseif($user->role === 'admin')
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/50">Admin</span>
                        @else
                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-50 text-gray-700 border border-gray-200/50">User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->role !== 'superadmin')
                        <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('users.destroy', $user) }}" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                        <button type="button" onclick="confirmDelete({{ $user->id }})" class="text-gray-300 hover:text-red-500 transition-all duration-200 hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links('pagination::tailwind') }}
        </div>
        @endif
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Hapus User?',
            text: "User yang dihapus tidak bisa dikembalikan!",
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
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
<style>@keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }</style>
@endsection
