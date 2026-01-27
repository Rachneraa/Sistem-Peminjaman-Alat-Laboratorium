@extends('layouts.app')

@section('title', 'Manajemen Pengembalian')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Pengembalian</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="{{ route('admin.returns.create') }}" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Pengembalian
        </a>
        <a href="{{ route('admin.reports.return') }}" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-green-600/20">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Cetak Laporan
        </a>
    </div>
</div>

<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
@php
    $activeFiltersCount = collect(request()->only(['search', 'denda']))->filter()->count();
@endphp

<x-filter-panel :action="route('admin.returns.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari Peminjam</label>
        <div class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all group-hover:border-gray-400 dark:group-hover:border-gray-600">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status Denda</label>
        <select name="denda" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua</option>
            <option value="ada" {{ request('denda') == 'ada' ? 'selected' : '' }}>Ada Denda (Terlambat/Rusak)</option>
            <option value="tidak_ada" {{ request('denda') == 'tidak_ada' ? 'selected' : '' }}>Tanpa Denda (Tepat Waktu)</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Waktu Kembali</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Item Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Denda</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($returns as $return)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $return->borrowing->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">ID: #{{ $return->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $return->tanggal_kembali->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @foreach($return->borrowing->borrowingDetails->take(2) as $detail)
                                    <div class="text-xs text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-primary"></span>
                                        {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})
                                    </div>
                                @endforeach
                                @if($return->borrowing->borrowingDetails->count() > 2)
                                    <div class="text-[10px] text-gray-400 italic pl-3">+ {{ $return->borrowing->borrowingDetails->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                            @endphp
                            <x-badge :type="$totalDenda > 0 ? 'danger' : 'success'" size="md">
                                Rp{{ number_format($totalDenda, 0, ',', '.') }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1">
                                <x-tooltip text="Lihat Detail">
                                    <x-button variant="ghost" size="sm" :href="route('admin.returns.show', $return)" icon="visibility" />
                                </x-tooltip>
                                <x-tooltip text="Edit Data">
                                    <x-button variant="ghost" size="sm" :href="route('admin.returns.edit', $return)" class="text-primary" icon="edit" />
                                </x-tooltip>
                                <form method="POST" action="{{ route('admin.returns.destroy', $return) }}" class="inline delete-form" data-id="{{ $return->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-tooltip text="Hapus Log">
                                        <x-button variant="ghost" size="sm" class="text-red-500" icon="delete" onclick="handleDeleteReturn(this)" />
                                    </x-tooltip>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="assignment_return"
                                title="Data Pengembalian Kosong"
                                description="Belum ada alat yang dikembalikan atau filter tidak sesuai."
                                :actionUrl="route('admin.returns.create')"
                                actionText="Input Pengembalian"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($returns->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $returns->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>

<script>
function handleDeleteReturn(button) {
    const form = button.closest('form');
    const returnId = form.dataset.id;
    
    showConfirmModal({
        title: 'Hapus Pengembalian',
        message: `Yakin ingin menghapus pengembalian #${returnId}? Tindakan ini akan mengembalikan status peminjaman menjadi disetujui.`,
        type: 'warning',
        okText: 'Ya, Hapus',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
@endsection

