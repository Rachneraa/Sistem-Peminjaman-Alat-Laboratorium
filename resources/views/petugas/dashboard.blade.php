@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Dashboard Petugas</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-blue-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-blue-500 text-[32px]">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aktif</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_borrowings'] }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-blue-500 w-1/2"></div>
        </div>
    </x-card>

    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-yellow-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-yellow-500 text-[32px]">assignment_late</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Menunggu</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pending_borrowings'] }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-yellow-500 w-1/3"></div>
        </div>
    </x-card>

    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-red-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-red-500 text-[32px]">warning</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Terlambat</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['overdue_borrowings'] }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-red-500 w-full"></div>
        </div>
    </x-card>

    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-green-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-green-500 text-[32px]">today</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Hari Ini</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['borrowed_today'] ?? 0 }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-green-500 w-3/4"></div>
        </div>
    </x-card>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Peminjaman Menunggu Persetujuan Table -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border flex flex-col h-full">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700/50">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">assignment_late</span>
                Menunggu Persetujuan
            </h2>
            <a href="{{ route('petugas.borrowings.index', ['status' => 'menunggu']) }}" class="text-xs text-primary hover:text-primary/80 font-bold uppercase tracking-wide flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        
        <div class="flex-1 space-y-4 overflow-y-auto max-h-[500px] custom-scrollbar pr-2">
            @forelse($pending_borrowings as $borrowing)
                <div class="p-4 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700/50 rounded-lg group hover:border-primary/30 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                             <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">
                                    #{{ $borrowing->id }}
                                </span>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $borrowing->user->name }}</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                {{ $borrowing->tanggal_pinjam->format('d/m/Y') }}
                            </p>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                    </div>
                    
                    <div class="mb-4 bg-white dark:bg-gray-800/30 p-3 rounded border border-gray-200 dark:border-white/5">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 font-bold">Alat dipinjam</p>
                        <div class="space-y-1">
                            @foreach($borrowing->borrowingDetails->take(2) as $detail)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $detail->tool->nama_alat }}</span>
                                    <span class="font-mono text-primary">{{ $detail->jumlah }}x</span>
                                </div>
                            @endforeach
                            @if($borrowing->borrowingDetails->count() > 2)
                                <p class="text-[10px] text-gray-500 italic mt-1">+{{ $borrowing->borrowingDetails->count() - 2 }} alat lainnya</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('petugas.borrowings.approve', $borrowing) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white text-xs font-bold rounded uppercase tracking-wider transition-colors flex items-center justify-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">check</span> Accept
                            </button>
                        </form>
                        <button onclick="showRejectModal({{ $borrowing->id }})" class="flex-1 px-3 py-1.5 bg-red-600 hover:bg-red-500 text-white text-xs font-bold rounded uppercase tracking-wider transition-colors flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">close</span> Reject
                        </button>
                        <a href="{{ route('petugas.borrowings.show', $borrowing) }}" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold rounded uppercase tracking-wider transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">assignment_turned_in</span>
                    <p class="text-sm">Tidak ada peminjaman pending</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Peminjaman Aktif Table -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border flex flex-col h-full">
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700/50">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500">pending_actions</span>
                Peminjaman Aktif
            </h2>
            <a href="{{ route('petugas.returns.index') }}" class="text-xs text-primary hover:text-primary/80 font-bold uppercase tracking-wide flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        
        <div class="flex-1 space-y-4 overflow-y-auto max-h-[500px] custom-scrollbar pr-2">
            @forelse($active_borrowings as $borrowing)
                <div class="p-4 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-gray-700/50 rounded-lg group hover:border-primary/30 transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                             <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-500 border border-blue-500/20">
                                    #{{ $borrowing->id }}
                                </span>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $borrowing->user->name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span>
                                    {{ $borrowing->tanggal_pinjam->format('d/m/y') }}
                                </span>
                                @if($borrowing->tanggal_selesai)
                                    <span class="flex items-center gap-1 text-red-500 dark:text-red-400">
                                        <span class="material-symbols-outlined text-[12px]">event_busy</span>
                                        {{ $borrowing->tanggal_selesai->format('d/m/y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    </div>
                    
                    <div class="mb-4 bg-white dark:bg-gray-800/30 p-3 rounded border border-gray-200 dark:border-white/5">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2 font-bold">Alat dipinjam</p>
                        <div class="space-y-1">
                            @foreach($borrowing->borrowingDetails->take(2) as $detail)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $detail->tool->nama_alat }}</span>
                                    <span class="font-mono text-primary">{{ $detail->jumlah }}x</span>
                                </div>
                            @endforeach
                            @if($borrowing->borrowingDetails->count() > 2)
                                <p class="text-[10px] text-gray-500 italic mt-1">+{{ $borrowing->borrowingDetails->count() - 2 }} alat lainnya</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="showReturnModal({{ $borrowing->id }})" class="flex-1 px-3 py-1.5 bg-primary hover:bg-primary/90 text-white text-xs font-bold rounded uppercase tracking-wider transition-colors flex items-center justify-center gap-1 shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[16px]">assignment_return</span> Return
                        </button>
                        <a href="{{ route('petugas.borrowings.show', $borrowing) }}" class="px-3 py-1.5 bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold rounded uppercase tracking-wider transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">hourglass_empty</span>
                    <p class="text-sm">Tidak ada peminjaman aktif</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <form id="rejectForm" method="POST" class="p-6">
            @csrf
            <div class="mb-6 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700/50 pb-4">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500">block</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tolak Peminjaman</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Berikan alasan penolakan</p>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Alasan</label>
                <textarea name="keterangan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 transition-all" required placeholder="Contoh: Stok alat tidak mencukupi..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-red-600/20">Tolak Permintaan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengembalian -->
<div id="returnModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <form id="returnForm" method="POST" class="p-6">
            @csrf
            <div class="mb-6 flex items-center gap-3 border-b border-gray-200 dark:border-gray-700/50 pb-4">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">assignment_return</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Proses Pengembalian</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Verifikasi pengembalian alat</p>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 text-sm">Rp</span>
                    <input type="number" name="denda_kerusakan" min="0" step="1000" value="0" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 pl-10 transition-all font-mono" placeholder="0">
                </div>
                <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wide">Isi jika terdapat kerusakan pada alat</p>
            </div>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan</label>
                <textarea name="keterangan" rows="3" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Kondisi alat saat dikembalikan..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-primary/20">Proses Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(borrowingId) {
    document.getElementById('rejectForm').action = `{{ url('/petugas/borrowings') }}/${borrowingId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

function showReturnModal(borrowingId) {
    document.getElementById('returnForm').action = `{{ url('/petugas/borrowings') }}/${borrowingId}/return`;
    document.getElementById('returnModal').classList.remove('hidden');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
}
</script>
@endsection

