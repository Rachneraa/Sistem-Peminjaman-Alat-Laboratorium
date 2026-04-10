@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Kategori</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="{{ route('admin.categories.export') }}" data-export-link
                data-loading-text="Menyiapkan export kategori..."
                class="js-export-link flex-1 md:flex-none justify-center px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:border-primary hover:text-primary hover:bg-primary/5 transition-all flex items-center gap-2 text-sm font-medium group">
                <span
                    class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">download</span>
                Export Excel
            </a>
            <button onclick="showImportModal()"
                class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:border-primary hover:text-primary hover:bg-primary/5 transition-all flex items-center gap-2 text-sm font-medium group">
                <span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">upload</span>
                Import CSV
            </button>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <!-- Filter & Pencarian -->
    <!-- Filter & Pencarian -->
    <div
        class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-2 pb-[1px]">
                <button type="submit"
                    class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    Cari
                </button>
                <a href="{{ route('admin.categories.index') }}"
                    class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                    <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Tambah Kategori -->
        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border h-fit">
            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">add_circle</span>
                Tambah Kategori
            </h2>
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-4">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Nama
                        Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all"
                        required autofocus>
                </div>
                <button type="submit"
                    class="w-full px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center justify-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Tambah
                </button>
            </form>
        </div>

        <!-- Daftar Kategori -->
        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
            <h2
                class="text-xl font-bold text-gray-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">list</span>
                Daftar Kategori
            </h2>
            <div class="space-y-3">
                @forelse($categories as $category)
                    <div
                        class="flex justify-between items-center p-4 border border-gray-200 dark:border-white/5 rounded-lg bg-gray-50 dark:bg-gray-800/30 hover:bg-gray-100 dark:hover:bg-gray-800 hover:border-primary/30 transition-all group">
                        <div>
                            <p class="font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors">
                                {{ $category->nama_kategori }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                                {{ $category->tools_count }} alat
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editCategory({{ $category->id }}, '{{ $category->nama_kategori }}')"
                                class="text-primary hover:text-primary/80 inline-flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit
                            </button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                class="inline delete-form" data-id="{{ $category->id }}"
                                data-name="{{ $category->nama_kategori }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="handleDeleteCategory(this)"
                                    class="text-red-400 hover:text-red-300 inline-flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="material-symbols-outlined text-gray-600 text-[48px] mb-3">folder_off</span>
                        <p class="text-gray-400">Tidak ada kategori</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/5">
                {{ $categories->links('vendor.pagination.industrial') }}
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="editModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div
            class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
            <form id="editForm" method="POST" class="p-8">
                @csrf
                @method('PUT')
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-2">Edit Kategori
                    </h3>
                    <div class="h-1 w-12 bg-primary"></div>
                </div>

                <div class="mb-8">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Nama
                        Kategori</label>
                    <input type="text" name="nama_kategori" id="editNamaKategori"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required autofocus>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all text-sm shadow-lg shadow-primary/20">Update</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div
            class="relative top-20 mx-auto p-5 border border-gray-200 dark:border-gray-700 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Import Categories</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.categories.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File CSV</label>
                    <input type="file" name="file" accept=".csv" required class="input text-gray-900 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: Nama Kategori, Keterangan</p>
                </div>
                <div class="mb-4">
                    <a href="{{ route('admin.categories.template') }}"
                        class="text-blue-400 hover:text-blue-300 text-sm inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
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
    async function downloadAndRefresh(url, options = {}) {
        const response = await fetch(url, {
            method: options.method || 'GET',
            headers: options.headers || {},
            body: options.body || null,
            credentials: options.credentials || 'include',
            redirect: 'follow',
            cache: 'no-store'
        });

        if (!response.ok) {
            throw new Error('Export gagal diproses.');
        }

        const blob = await response.blob();
        const contentDisposition = response.headers.get('Content-Disposition') || '';
        const utf8NameMatch = contentDisposition.match(/filename\*=UTF-8''([^;]+)/i);
        const quotedNameMatch = contentDisposition.match(/filename="([^"]+)"/i);
        const plainNameMatch = contentDisposition.match(/filename=([^;]+)/i);
        const filename = utf8NameMatch ? decodeURIComponent(utf8NameMatch[1]) : (quotedNameMatch ? quotedNameMatch[1] : (plainNameMatch ? plainNameMatch[1].trim() : 'export.csv'));

        const blobUrl = window.URL.createObjectURL(blob);
        const anchor = document.createElement('a');
        anchor.href = blobUrl;
        anchor.download = filename;
        document.body.appendChild(anchor);
        anchor.click();
        anchor.remove();
        window.URL.revokeObjectURL(blobUrl);

        window.location.reload();
    }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-export-link]').forEach(function (link) {
            link.addEventListener('click', async function(event) {
                event.preventDefault();

                    const loadingText = link.getAttribute('data-loading-text') || 'Menyiapkan file...';
                    link.classList.add('pointer-events-none', 'opacity-70');
                    link.setAttribute('aria-disabled', 'true');
                    link.innerHTML = '<span class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>' + loadingText;

                try {
                    await downloadAndRefresh(link.href);
                } catch (error) {
                    link.classList.remove('pointer-events-none', 'opacity-70');
                    link.removeAttribute('aria-disabled');
                    link.innerHTML = '<span class="material-symbols-outlined text-[20px] group-hover:text-primary transition-colors">download</span>Export Excel';
                    alert(error.message);
                }
                });
            });
        });

        function showImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }

        function editCategory(id, nama) {
            document.getElementById('editForm').action = `/admin/categories/${id}`;
            document.getElementById('editNamaKategori').value = nama;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function handleDeleteCategory(button) {
            const form = button.closest('form');
            const categoryName = form.dataset.name;

            showConfirmModal({
                title: 'Hapus Kategori',
                message: `Yakin ingin menghapus kategori "${categoryName}"? Semua alat dalam kategori ini juga akan terpengaruh. Tindakan ini tidak dapat dibatalkan.`,
                type: 'danger',
                okText: 'Ya, Hapus',
                onConfirm: function () {
                    form.submit();
                }
            });
        }
    </script>
@endsection