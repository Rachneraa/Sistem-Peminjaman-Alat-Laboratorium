@extends('layouts.app')

@section('title', 'Tambah Pengembalian')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Tambah Pengembalian</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border">
        <form method="POST" action="{{ route('admin.returns.store') }}" id="returnForm">
            @csrf
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Peminjaman <span class="text-red-400">*</span></label>
                <select name="borrowing_id" id="borrowingSelect" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    <option value="">Pilih Peminjaman</option>
                    @foreach($borrowings as $borrowing)
                        <option value="{{ $borrowing->id }}" {{ old('borrowing_id') == $borrowing->id ? 'selected' : '' }}>
                            #{{ $borrowing->id }} - {{ $borrowing->user->name }} 
                            ({{ $borrowing->tanggal_pinjam->format('d/m/Y') }} - {{ $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : '-' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Hanya menampilkan peminjaman yang disetujui atau menunggu pengembalian dan belum dikembalikan.</p>
            </div>

            <div id="borrowingDetails" class="mb-8 hidden animate-fade-in-up">
                <div class="p-6 bg-background-dark rounded-xl border border-gray-700/50">
                    <p class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest border-b border-gray-700 pb-2">Detail Peminjaman</p>
                    <div id="detailsContent" class="space-y-3"></div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d')) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                <input type="number" name="denda_kerusakan" min="0" step="1000" value="{{ old('denda_kerusakan', 0) }}" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Masukkan jumlah denda kerusakan jika ada kerusakan pada alat yang dikembalikan.</p>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan Kondisi Alat</label>
                <textarea name="keterangan" rows="4" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Catatan kondisi alat, kerusakan yang ditemukan, dll...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Simpan
                </button>
                <a href="{{ route('admin.returns.index') }}" class="px-6 py-2.5 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all flex items-center gap-2 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const borrowings = @json($borrowingsData->keyBy('id'));

document.getElementById('borrowingSelect').addEventListener('change', function() {
    const borrowingId = parseInt(this.value);
    const detailsDiv = document.getElementById('borrowingDetails');
    const detailsContent = document.getElementById('detailsContent');
    
    if (borrowingId && borrowings[borrowingId]) {
        const borrowing = borrowings[borrowingId];
        let html = `
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Peminjam</p>
                    <p class="text-white font-medium">${borrowing.user.name}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tanggal Pinjam</p>
                    <p class="text-white font-medium">${borrowing.tanggal_pinjam}</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-2">Alat yang Dipinjam</p>
                <div class="space-y-2">
        `;
        
        borrowing.borrowing_details.forEach(function(detail) {
            html += `
                <div class="flex justify-between items-center p-2 bg-gray-800/50 rounded border border-gray-700/50">
                    <span class="text-sm text-gray-200">${detail.tool.nama_alat}</span>
                    <span class="text-xs font-bold text-primary px-2 py-1 bg-primary/10 rounded">${detail.jumlah} Unit</span>
                </div>
            `;
        });
        
        html += '</div></div>';
        detailsContent.innerHTML = html;
        detailsDiv.classList.remove('hidden');
    } else {
        detailsDiv.classList.add('hidden');
    }
});

// Trigger on page load if value exists
if (document.getElementById('borrowingSelect').value) {
    document.getElementById('borrowingSelect').dispatchEvent(new Event('change'));
}
</script>
@endsection
