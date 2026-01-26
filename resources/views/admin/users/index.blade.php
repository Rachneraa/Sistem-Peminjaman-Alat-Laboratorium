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

<!-- Filter & Pencarian -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                </div>
            </div>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Role</label>
            <select name="role" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                <option value="peminjam" {{ request('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            <a href="{{ route('admin.users.index') }}" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <div class="overflow-auto max-h-[75vh]">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($user->role == 'admin') bg-purple-500/20 text-purple-400
                                @elseif($user->role == 'petugas') bg-blue-500/20 text-blue-400
                                @else bg-green-500/20 text-green-400
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-primary hover:text-primary/80 inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline delete-form" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="handleDeleteUser(this)" class="text-red-400 hover:text-red-300 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-gray-400 text-lg font-medium">Tidak ada data</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $users->links('vendor.pagination.industrial') }}
    </div>
</div>


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

