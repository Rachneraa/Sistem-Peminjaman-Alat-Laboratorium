@extends('layouts.app')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Edit Pengembalian #{{ $return->id }}</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Borrowing Info -->
        <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Informasi Peminjaman
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Peminjam</p>
                    <p class="text-white font-medium text-lg">{{ $return->borrowing->user->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">ID Peminjaman</p>
                    <p class="text-gray-300 font-mono">#{{ $return->borrowing->id }}</p>
                </div>
            </div>
        </div>

        <!-- Borrowed Tools -->
        <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">construction</span>
                Alat yang Dikembalikan
            </h3>
            <div class="space-y-2">
                @foreach($return->borrowing->borrowingDetails as $detail)
                    <div class="flex justify-between items-center p-3 bg-background-dark rounded-lg border border-gray-700/50">
                        <span class="text-sm text-gray-200 font-medium">{{ $detail->tool->nama_alat }}</span>
                        <span class="text-xs font-bold text-primary px-2 py-1 bg-primary/10 rounded border border-primary/20">{{ $detail->jumlah }} Unit</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border">
        <form method="POST" action="{{ route('admin.returns.update', $return) }}" id="returnForm">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', $return->tanggal_kembali->format('Y-m-d')) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Keterlambatan (Rp)</label>
                <input type="number" name="denda" min="0" step="1000" value="{{ old('denda', $return->denda) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Denda untuk keterlambatan pengembalian.</p>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Hari Terlambat</label>
                <input type="number" name="terlambat_hari" min="0" value="{{ old('terlambat_hari', $return->terlambat_hari) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                <input type="number" name="denda_kerusakan" min="0" step="1000" value="{{ old('denda_kerusakan', $return->denda_kerusakan ?? 0) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Denda untuk kerusakan alat.</p>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan Kondisi Alat</label>
                <textarea name="keterangan" rows="4" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Catatan kondisi alat, kerusakan yang ditemukan, dll...">{{ old('keterangan', $return->keterangan) }}</textarea>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">update</span>
                    Update
                </button>
                <a href="{{ route('admin.returns.index') }}" class="px-6 py-2.5 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all flex items-center gap-2 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
