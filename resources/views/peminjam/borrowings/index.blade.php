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

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#{{ $borrowing->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div>{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Selesai: {{ $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                            @foreach($borrowing->borrowingDetails as $detail)
                                <li class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                    {{ $detail->tool->nama_alat }} <span class="text-gray-500 dark:text-gray-500">({{ $detail->jumlah }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $estimatedFine = $borrowing->status == 'disetujui' ? $borrowing->calculateEstimatedFine() : ['denda' => 0, 'terlambat_hari' => 0];
                        @endphp
                        @if($borrowing->status == 'disetujui')
                            @if($estimatedFine['denda'] > 0)
                                <div class="text-red-500 dark:text-red-400 font-semibold">Rp {{ number_format($estimatedFine['denda'], 0, ',', '.') }}</div>
                                <div class="text-xs text-red-400 dark:text-red-300">{{ $estimatedFine['terlambat_hari'] }} hari terlambat</div>
                            @else
                                <span class="text-green-500 dark:text-green-400">Rp 0</span>
                            @endif
                        @elseif($borrowing->status == 'dikembalikan' && $borrowing->return)
                            <div class="text-red-500 dark:text-red-400 font-semibold">Rp {{ number_format($borrowing->return->denda, 0, ',', '.') }}</div>
                            @if($borrowing->return->terlambat_hari > 0)
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $borrowing->return->terlambat_hari }} hari terlambat</div>
                            @endif
                        @else
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border
                            @if($borrowing->status == 'disetujui') bg-green-500/10 text-green-500 dark:text-green-400 border-green-500/20
                            @elseif($borrowing->status == 'menunggu_pengembalian') bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20
                            @elseif($borrowing->status == 'ditolak') bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20
                            @elseif($borrowing->status == 'dikembalikan') bg-blue-500/10 text-blue-500 dark:text-blue-400 border-blue-500/20
                            @else bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20
                            @endif">
                            @if($borrowing->status == 'menunggu_pengembalian')
                                Menunggu
                            @else
                                {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('peminjam.borrowings.show', $borrowing) }}" class="text-primary hover:text-primary/80 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                Detail
                            </a>
                            @if($borrowing->status == 'disetujui')
                                <form method="POST" action="{{ route('peminjam.borrowings.return', $borrowing) }}" class="inline return-form" data-id="{{ $borrowing->id }}">
                                    @csrf
                                    <button type="button" onclick="handleReturnBorrowing(this)" class="text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">assignment_return</span>
                                        Kembalikan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12">
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px] mb-4">inbox</span>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">Belum ada peminjaman</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mb-4">Mulai dengan mengajukan peminjaman pertama Anda</p>
                            <a href="{{ route('peminjam.borrowings.create') }}" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center justify-center gap-2 text-sm font-medium shadow-lg shadow-primary/20 w-fit mx-auto">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                Ajukan Peminjaman
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $borrowings->links('vendor.pagination.industrial') }}
    </div>
</div>

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

