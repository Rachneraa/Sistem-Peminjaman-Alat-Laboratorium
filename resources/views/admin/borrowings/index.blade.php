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
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="{{ route('admin.borrowings.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email peminjam..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                </div>
            </div>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Status</label>
            <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            <a href="{{ route('admin.borrowings.index') }}" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <div class="overflow-auto max-h-[75vh]">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estimasi Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">#{{ $borrowing->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $borrowing->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <ul class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                @foreach($borrowing->borrowingDetails as $detail)
                                    <li>{{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($borrowing->status == 'disetujui')
                                @php
                                    $estimatedFine = $borrowing->calculateEstimatedFine();
                                @endphp
                                @if($estimatedFine['denda'] > 0)
                                    <div class="text-red-400 font-semibold">Rp {{ number_format($estimatedFine['denda'], 0, ',', '.') }}</div>
                                    <div class="text-xs text-red-300">{{ $estimatedFine['terlambat_hari'] }} hari terlambat</div>
                                @else
                                    <span class="text-green-400">Rp 0</span>
                                @endif
                            @elseif($borrowing->status == 'dikembalikan' && $borrowing->return)
                                <div class="text-red-400 font-semibold">Rp {{ number_format($borrowing->return->denda, 0, ',', '.') }}</div>
                                @if($borrowing->return->terlambat_hari > 0)
                                    <div class="text-xs text-gray-400">{{ $borrowing->return->terlambat_hari }} hari terlambat</div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($borrowing->status == 'disetujui') bg-green-500/20 text-green-400
                                @elseif($borrowing->status == 'menunggu_pengembalian') bg-yellow-500/20 text-yellow-400
                                @elseif($borrowing->status == 'ditolak') bg-red-500/20 text-red-400
                                @elseif($borrowing->status == 'dikembalikan') bg-blue-500/20 text-blue-400
                                @else bg-yellow-500/20 text-yellow-400
                                @endif">
                                @if($borrowing->status == 'menunggu_pengembalian')
                                    Menunggu Pengembalian
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    Detail
                                </a>
                                <a href="{{ route('admin.borrowings.edit', $borrowing) }}" class="text-primary hover:text-primary/80 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.borrowings.destroy', $borrowing) }}" class="inline delete-form" data-id="{{ $borrowing->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="handleDeleteBorrowing(this)" class="text-red-400 hover:text-red-300 inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4">
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-gray-600 text-[64px] mb-4">inbox</span>
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
        {{ $borrowings->links('vendor.pagination.industrial') }}
    </div>
</div>

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

