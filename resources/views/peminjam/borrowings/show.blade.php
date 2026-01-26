@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Detail Peminjaman #{{ $borrowing->id }}</h1>
    
    <div class="card p-6 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-') }}</p>
                </div>
            </div>
            @php
                $estimatedFine = $borrowing->status == 'disetujui' ? $borrowing->calculateEstimatedFine() : ['denda' => 0, 'terlambat_hari' => 0];
            @endphp
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($borrowing->status == 'disetujui') bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400
                        @elseif($borrowing->status == 'menunggu_pengembalian') bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400
                        @elseif($borrowing->status == 'ditolak') bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400
                        @elseif($borrowing->status == 'dikembalikan') bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400
                        @else bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400
                        @endif">
                        @if($borrowing->status == 'menunggu_pengembalian')
                            Menunggu Persetujuan
                        @else
                            {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                        @endif
                    </span>
                </div>
                @if($borrowing->status == 'disetujui')
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Estimasi Denda</p>
                        @if($estimatedFine['denda'] > 0)
                            <p class="font-medium text-lg text-red-600 dark:text-red-400">Rp {{ number_format($estimatedFine['denda'], 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $estimatedFine['terlambat_hari'] }} hari terlambat</p>
                        @else
                            <p class="font-medium text-green-600 dark:text-green-400">Rp 0</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tidak ada denda</p>
                        @endif
                    </div>
                @endif
            </div>
            @if($borrowing->status == 'disetujui' && isset($estimatedFine) && $estimatedFine['denda'] > 0)
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-600 dark:text-red-400 mb-1">Peringatan Denda</p>
                            <p class="text-sm text-red-500 dark:text-red-300">Peminjaman Anda telah melewati tanggal jatuh tempo. Segera kembalikan alat untuk menghentikan penambahan denda. Denda akan terus bertambah setiap harinya.</p>
                        </div>
                    </div>
                </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Alat yang Dipinjam</p>
                <div class="space-y-3">
                    @foreach($borrowing->borrowingDetails as $detail)
                        <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            {{-- Tool Image --}}
                            <div class="flex-shrink-0">
                                @if($detail->tool->gambar)
                                    <img src="{{ asset($detail->tool->gambar) }}" 
                                         alt="{{ $detail->tool->nama_alat }}" 
                                         class="w-10 h-10 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center border-2 border-gray-200 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Tool Info --}}
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $detail->tool->nama_alat }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $detail->tool->category->nama_kategori }}</p>
                            </div>
                            
                            {{-- Quantity Badge --}}
                            <div class="flex-shrink-0">
                                <div class="bg-blue-50 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg px-4 py-2 text-center">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">Jumlah</p>
                                    <p class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ $detail->jumlah }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if($borrowing->keterangan)
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $borrowing->keterangan }}</p>
                </div>
            @endif
            @if($borrowing->return)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informasi Pengembalian</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kembali</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $borrowing->return->tanggal_kembali->format('d/m/Y') }}</p>
                        </div>
                        @php
                            $totalDenda = $borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0);
                        @endphp
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Denda Dibayar</p>
                            <p class="font-medium text-lg text-red-600 dark:text-red-400">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                            @if($borrowing->return->denda > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400">Keterlambatan: Rp {{ number_format($borrowing->return->denda, 0, ',', '.') }}
                                    @if($borrowing->return->terlambat_hari > 0)
                                        ({{ $borrowing->return->terlambat_hari }} hari)
                                    @endif
                                </p>
                            @endif
                            @if(($borrowing->return->denda_kerusakan ?? 0) > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400">Kerusakan: Rp {{ number_format($borrowing->return->denda_kerusakan ?? 0, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        @if($borrowing->status == 'disetujui')
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/30 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-300">Klik tombol di bawah untuk mengajukan pengembalian. Petugas akan memeriksa kondisi alat dan menentukan denda jika ada kerusakan.</p>
            </div>
        @elseif($borrowing->status == 'menunggu_pengembalian')
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/30 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-300 font-semibold">Pengembalian Anda sedang menunggu persetujuan petugas. Petugas akan memeriksa kondisi alat dan menentukan denda jika ada kerusakan.</p>
            </div>
        @endif
        
        <div class="flex gap-3">
            <a href="{{ route('peminjam.borrowings.index') }}" class="px-5 py-2.5 bg-transparent border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
            @if($borrowing->status == 'disetujui' || $borrowing->status == 'menunggu_pengembalian' || $borrowing->status == 'dikembalikan')
                <a href="{{ route('peminjam.borrowings.print', $borrowing) }}" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all flex items-center gap-2 text-sm shadow-lg shadow-blue-600/20">
                    <span class="material-symbols-outlined text-[20px]">print</span>
                    Cetak Bukti
                </a>
            @endif
            @if($borrowing->status == 'disetujui')
                <form method="POST" action="{{ route('peminjam.borrowings.return', $borrowing) }}" class="return-form" data-id="{{ $borrowing->id }}">
                    @csrf
                    <button type="button" onclick="handleReturnBorrowing(this)" class="px-5 py-2.5 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all flex items-center gap-2 text-sm shadow-lg shadow-green-600/20">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        Kembalikan Alat
                    </button>
                </form>
            @endif
        </div>
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

