@extends('layouts.app')

@section('title', 'Manajemen Peminjaman')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Peminjaman</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <a href="{{ route('admin.borrowings.create') }}" class="w-full md:w-auto justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Tambah Peminjaman
    </a>
</div>

<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
@php
    $activeFiltersCount = collect(request()->only(['search', 'status']))->filter()->count();
@endphp

<x-filter-panel :action="route('admin.borrowings.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari Peminjam</label>
        <div class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all group-hover:border-gray-400 dark:group-hover:border-gray-600">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status Peminjaman</label>
        <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Sedang Dipinjam</option>
            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Selesai (Dikembalikan)</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Detail Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Waktu</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Denda</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $borrowing->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">ID: #{{ $borrowing->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @foreach($borrowing->borrowingDetails->take(2) as $detail)
                                    <div class="text-xs text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-primary"></span>
                                        {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})
                                    </div>
                                @endforeach
                                @if($borrowing->borrowingDetails->count() > 2)
                                    <div class="text-[10px] text-gray-400 italic pl-3">+ {{ $borrowing->borrowingDetails->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs space-y-1">
                                <div class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_today</span>
                                    {{ $borrowing->tanggal_pinjam->format('d/m/Y') }}
                                </div>
                                <div class="text-gray-400 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">event_busy</span>
                                    {{ $borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($borrowing->status == 'disetujui')
                                @php $estimatedFine = $borrowing->calculateEstimatedFine(); @endphp
                                @if($estimatedFine['denda'] > 0)
                                    <div class="text-xs font-bold text-red-500">Rp{{ number_format($estimatedFine['denda'], 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-red-400">{{ $estimatedFine['terlambat_hari'] }} Hari</div>
                                @else
                                    <span class="text-xs text-accent-green">Rp0</span>
                                @endif
                            @elseif($borrowing->status == 'dikembalikan' && $borrowing->return)
                                <div class="text-xs font-bold text-red-500">Rp{{ number_format($borrowing->return->denda, 0, ',', '.') }}</div>
                                @if($borrowing->return->terlambat_hari > 0)
                                    <div class="text-[10px] text-gray-400">{{ $borrowing->return->terlambat_hari }} Hari</div>
                                @endif
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="match($borrowing->status) {
                                'disetujui' => 'info',
                                'dikembalikan' => 'success',
                                'ditolak' => 'danger',
                                default => 'warning'
                            }" size="sm">
                                {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1">
                                <x-tooltip text="Detail">
                                    <x-button variant="ghost" size="sm" :href="route('admin.borrowings.show', $borrowing)" icon="visibility" />
                                </x-tooltip>
                                <x-tooltip text="Edit">
                                    <x-button variant="ghost" size="sm" :href="route('admin.borrowings.edit', $borrowing)" class="text-primary" icon="edit" />
                                </x-tooltip>
                                <form method="POST" action="{{ route('admin.borrowings.destroy', $borrowing) }}" class="inline delete-form" data-id="{{ $borrowing->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <x-tooltip text="Hapus">
                                        <x-button variant="ghost" size="sm" class="text-red-500" icon="delete" onclick="handleDeleteBorrowing(this)" />
                                    </x-tooltip>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state 
                                icon="receipt_long"
                                title="Data Peminjaman Kosong"
                                description="Belum ada transaksi peminjaman atau filter tidak sesuai."
                                :actionUrl="route('admin.borrowings.create')"
                                actionText="Tambah Peminjaman"
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $borrowings->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>

<script>
function handleDeleteBorrowing(button) {
    const form = button.closest('form');
    const borrowingId = form.dataset.id;
    
    showConfirmModal({
        title: 'Hapus Peminjaman',
        message: `Yakin ingin menghapus peminjaman #${borrowingId}? Tindakan ini akan mengembalikan stok alat.`,
        type: 'warning',
        okText: 'Ya, Hapus',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
@endsection

