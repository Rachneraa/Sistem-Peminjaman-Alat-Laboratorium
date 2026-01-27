@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Peminjaman Saya</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <a href="{{ route('peminjam.borrowings.create') }}" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Ajukan Peminjaman
    </a>
</div>

@php
    $activeFiltersCount = collect(request()->only(['status']))->filter()->count();
@endphp

<x-filter-panel :action="route('peminjam.borrowings.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-4">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Status Peminjaman</label>
        <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Sedang Dipinjam</option>
            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">ID / Waktu Pinjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Daftar Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Estimasi Denda</th>
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
                                    <span class="material-symbols-outlined text-primary text-[18px]">receipt_long</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-[10px] text-gray-500 font-mono uppercase">#{{ $borrowing->id }}</div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</div>
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
                        <td class="px-6 py-4 text-sm font-medium">
                            @php
                                $showFine = in_array($borrowing->status, ['disetujui', 'menunggu_pengembalian']);
                                $estimatedFine = $showFine ? $borrowing->calculateEstimatedFine() : ['denda' => 0, 'terlambat_hari' => 0];
                            @endphp
                            
                            @if($showFine)
                                @if($estimatedFine['denda'] > 0)
                                    <div class="text-red-500 font-bold text-xs">Rp{{ number_format($estimatedFine['denda'], 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-red-400">{{ $estimatedFine['terlambat_hari'] }} Hari Terlambat</div>
                                @else
                                    <span class="text-accent-green text-xs">Rp0</span>
                                @endif
                            @elseif($borrowing->status == 'dikembalikan' && $borrowing->return)
                                <div class="text-red-500 font-bold text-xs">Rp{{ number_format($borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0), 0, ',', '.') }}</div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="match($borrowing->status) {
                                'disetujui' => 'primary',
                                'dikembalikan' => 'success',
                                'ditolak' => 'danger',
                                default => 'warning'
                            }" size="sm">
                                {{ $borrowing->status == 'menunggu_pengembalian' ? 'PROSES KEMBALI' : strtoupper(str_replace('_', ' ', $borrowing->status)) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1">
                                <x-button variant="ghost" size="sm" :href="route('peminjam.borrowings.show', $borrowing)" icon="visibility" />
                                @if($borrowing->status == 'disetujui')
                                    <form method="POST" action="{{ route('peminjam.borrowings.return', $borrowing) }}" class="inline return-form" data-id="{{ $borrowing->id }}">
                                        @csrf
                                        <x-button variant="ghost" size="sm" type="button" class="text-accent-green" icon="assignment_return" onclick="handleReturnBorrowing(this)" />
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="receipt_long"
                                title="Belum Ada Peminjaman"
                                description="Anda belum pernah melakukan peminjaman alat."
                                :actionUrl="route('peminjam.borrowings.create')"
                                actionText="Pinjam Alat Sekarang"
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
function handleReturnBorrowing(button) {
    const form = button.closest('form');
    const borrowingId = form.dataset.id;
    
    showConfirmModal({
        title: 'Kembalikan Alat',
        message: 'Yakin ingin mengembalikan alat? Pastikan semua alat sudah lengkap dan dalam kondisi baik.',
        type: 'success',
        okText: 'Ya, Kembalikan',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
@endsection
