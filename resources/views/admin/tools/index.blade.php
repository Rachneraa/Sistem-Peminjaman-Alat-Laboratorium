@extends('layouts.app')

@section('title', 'Manajemen Alat')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Alat</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
        <div class="flex flex-wrap gap-3 w-full md:w-auto">
            <a href="{{ route('admin.tools.export') }}" data-export-link data-loading-text="Menyiapkan export alat..."
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
            <a href="{{ route('admin.tools.create') }}"
                class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Alat
            </a>
        </div>
    </div>

    @php
        $activeFiltersCount = collect(request()->only(['search', 'kategori_id', 'status']))->filter()->count();
    @endphp

    <x-filter-panel :action="route('admin.tools.index')" :activeFiltersCount="$activeFiltersCount">
        <div>
            <label
                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari
                Nama</label>
            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama alat..."
                    class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all group-hover:border-gray-400 dark:group-hover:border-gray-600">
                <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500 group-hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </div>
            </div>
        </div>

        <div>
            <label
                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Kategori</label>
            <select name="kategori_id"
                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('kategori_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label
                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status
                Ketersediaan</label>
            <select name="status"
                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="rusak" {{ request('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            </select>
        </div>

        <div>
            <label
                class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Urutan</label>
            <select name="sort"
                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                <option value="stock_low" {{ request('sort') == 'stock_low' ? 'selected' : '' }}>Stok Terendah</option>
            </select>
        </div>
    </x-filter-panel>

    <x-card class="overflow-hidden" :padding="false">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
                <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Informasi Alat</th>
                        <th
                            class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Kategori</th>
                        <th
                            class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Inventori</th>
                        <th
                            class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Status</th>
                        <th
                            class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @forelse($tools as $tool)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-background-dark border border-gray-200 dark:border-white/10 flex items-center justify-center overflow-hidden flex-shrink-0 group-hover:border-primary/50 transition-colors">
                                                @if($tool->gambar)
                                                    <img src="{{ asset($tool->gambar) }}" class="w-full h-full object-cover">
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[24px]">construction</span>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                                    {{ $tool->nama_alat }}
                                                </div>
                                                <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">ID:
                                                    TOOL-{{ str_pad($tool->id, 4, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge type="primary" size="sm"
                                            class="font-normal">{{ $tool->category->nama_kategori }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1 text-xs">
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-accent-green"></span>
                                                <span class="text-gray-600 dark:text-gray-300 font-bold">{{ $tool->stok }}<span
                                                        class="text-[10px] font-normal ml-1">Bagus</span></span>
                                            </div>
                                            @if($tool->stok_rusak > 0)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    <span class="text-gray-600 dark:text-gray-300">{{ $tool->stok_rusak }}<span
                                                            class="text-[10px] ml-1">Rusak</span></span>
                                                </div>
                                            @endif
                                            @if($tool->stok_perbaikan > 0)
                                                <div class="flex items-center gap-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                    <span class="text-gray-600 dark:text-gray-300">{{ $tool->stok_perbaikan }}<span
                                                            class="text-[10px] ml-1">Bengkel</span></span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-badge :type="match ($tool->status) {
                            'tersedia' => 'success',
                            'rusak' => 'danger',
                            'dipinjam' => 'info',
                            default => 'warning'
                        }" size="md">{{ ucfirst($tool->status ?? 'tersedia') }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <x-tooltip text="Lihat Detail">
                                                <x-button variant="ghost" size="sm" :href="route('admin.tools.show', $tool)"
                                                    icon="visibility" />
                                            </x-tooltip>
                                            <x-tooltip text="Edit Data">
                                                <x-button variant="ghost" size="sm" :href="route('admin.tools.edit', $tool)"
                                                    class="text-primary" icon="edit" />
                                            </x-tooltip>
                                            <form method="POST" action="{{ route('admin.tools.destroy', $tool) }}"
                                                class="inline delete-form" data-id="{{ $tool->id }}" data-name="{{ $tool->nama_alat }}">
                                                @csrf
                                                @method('DELETE')
                                                <x-tooltip text="Hapus Alat">
                                                    <x-button variant="ghost" size="sm"
                                                        class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10" icon="delete"
                                                        onclick="handleDeleteTool(this)" />
                                                </x-tooltip>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <x-empty-state icon="inventory_2" title="Data Alat Kosong"
                                    description="Belum ada alat yang terdaftar atau filter tidak sesuai."
                                    :actionUrl="route('admin.tools.create')" actionText="Tambah Alat Baru" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tools->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
                {{ $tools->links('vendor.pagination.industrial') }}
            </div>
        @endif
    </x-card>


    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border border-gray-700 w-96 shadow-lg rounded-md bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-white">Import Tools</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.tools.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-300 mb-2">File CSV</label>
                    <input type="file" name="file" accept=".csv" required class="input">
                    <p class="text-xs text-gray-400 mt-1">Format: Nama Alat, Kategori, Stok, Status, Deskripsi</p>
                </div>
                <div class="mb-4">
                    <a href="{{ route('admin.tools.template') }}"
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
                link.addEventListener('click', async function (event) {
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

        function handleDeleteTool(button) {
            const form = button.closest('form');
            const toolName = form.dataset.name;

            showConfirmModal({
                title: 'Hapus Alat',
                message: `Yakin ingin menghapus alat "${toolName}"? Tindakan ini tidak dapat dibatalkan.`,
                type: 'danger',
                okText: 'Ya, Hapus',
                onConfirm: function () {
                    form.submit();
                }
            });
        }
    </script>
@endsection