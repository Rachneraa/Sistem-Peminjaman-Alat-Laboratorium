@extends('layouts.app')

@section('title', 'Manajemen Users')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Users</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <!-- Export Button -->
        <a href="{{ route('admin.users.export') }}" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:border-primary hover:text-primary hover:bg-primary/5 transition-all flex items-center gap-2 text-sm font-medium group">
            <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">download</span>
            Export Excel
        </a>
        <!-- Import Button -->
        <button onclick="showImportModal()" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:border-primary hover:text-primary hover:bg-primary/5 transition-all flex items-center gap-2 text-sm font-medium group">
            <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">upload</span>
            Import CSV
        </button>
        <a href="{{ route('admin.users.create') }}" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah User
        </a>
    </div>
</div>

@php
    $activeFiltersCount = collect(request()->only(['search', 'role']))->filter()->count();
@endphp

<x-filter-panel :action="route('admin.users.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari Nama / Email</label>
        <div class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all group-hover:border-gray-400 dark:group-hover:border-gray-600">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status Role</label>
        <select name="role" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
            <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
            <option value="peminjam" {{ request('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Identitas User</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Kontak</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Role</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Terdaftar</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden flex-shrink-0 group-hover:bg-primary/20 transition-colors">
                                    <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">UID: USER-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="match($user->role) {
                                'admin' => 'primary',
                                'petugas' => 'info',
                                default => 'success'
                            }" size="md">
                                {{ ucfirst($user->role) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <x-tooltip text="Edit Profile">
                                    <x-button variant="ghost" size="sm" :href="route('admin.users.edit', $user)" class="text-primary" icon="edit" />
                                </x-tooltip>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline delete-form" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-tooltip text="Hapus User">
                                        <x-button variant="ghost" size="sm" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10" icon="delete" onclick="handleDeleteUser(this)" />
                                    </x-tooltip>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="person_off"
                                title="Data User Kosong"
                                description="Belum ada user yang terdaftar atau filter tidak sesuai."
                                :actionUrl="route('admin.users.create')"
                                actionText="Tambah User Baru"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $users->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>


<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-700 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Users</h3>
            <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File CSV</label>
                <input type="file" name="file" accept=".csv" required class="input text-gray-900 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: Name, Email, Role, Created At</p>
            </div>
            <div class="mb-4">
                <a href="{{ route('admin.users.template') }}" class="text-blue-400 hover:text-blue-300 text-sm inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Template
                </a>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeImportModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Import</button>
            </div>
        </form>
    </div>
</div>

<script>
function showImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}

function handleDeleteUser(button) {
    const form = button.closest('form');
    const userName = form.dataset.name;
    
    showConfirmModal({
        title: 'Hapus User',
        message: `Yakin ingin menghapus user "${userName}"? Tindakan ini tidak dapat dibatalkan.`,
        type: 'danger',
        okText: 'Ya, Hapus',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
@endsection

