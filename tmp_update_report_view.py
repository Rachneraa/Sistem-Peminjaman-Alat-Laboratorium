from pathlib import Path
path = Path('resources/views/petugas/reports/index.blade.php')
text = path.read_text(encoding='utf-8')
start = text.index('    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">')
end = text.rindex('    </div>\n@endsection')
new_block = '''    <div class="space-y-6">
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
            <form method="GET" action="{{ route('petugas.reports.index') }}" class="grid gap-4 lg:grid-cols-[1fr_1fr_auto] items-end">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                <div class="flex flex-col gap-3 lg:items-end">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-primary/90">
                        <span class="material-symbols-outlined">visibility</span>
                        Tampilkan
                    </button>
                    <div class="inline-flex flex-wrap gap-2">
                        <a href="{{ route('petugas.reports.borrowing', ['start_date' => request('start_date', now()->startOfMonth()->format('Y-m-d')), 'end_date' => request('end_date', now()->endOfMonth()->format('Y-m-d'))]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition-all hover:border-primary hover:text-primary">
                            <span class="material-symbols-outlined">picture_as_pdf</span>
                            Cetak Peminjaman
                        </a>
                        <a href="{{ route('petugas.reports.return', ['start_date' => request('start_date', now()->startOfMonth()->format('Y-m-d')), 'end_date' => request('end_date', now()->endOfMonth()->format('Y-m-d'))]) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition-all hover:border-accent-green hover:text-accent-green">
                            <span class="material-symbols-outlined">picture_as_pdf</span>
                            Cetak Pengembalian
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-5 industrial-border">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Transaksi</p>
                <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
            </div>
            <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-5 industrial-border">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Sewa</p>
                <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalItems) }}</p>
            </div>
            <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-5 industrial-border">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Denda</p>
                <p class="mt-4 text-3xl font-bold text-red-500">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-5 industrial-border">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                <p class="mt-4 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            @php
                $statusMap = [
                    'dikembalikan' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-300',
                    'disetujui' => 'bg-sky-500/10 text-sky-600 dark:text-sky-300',
                    'menunggu' => 'bg-amber-500/10 text-amber-600 dark:text-amber-300',
                    'menunggu_pengembalian' => 'bg-orange-500/10 text-orange-600 dark:text-orange-300',
                    'ditolak' => 'bg-red-500/10 text-red-600 dark:text-red-300',
                ];
            @endphp

            @foreach($statusMap as $statusKey => $statusClass)
                <span class="inline-flex items-center rounded-full border px-4 py-2 text-xs font-semibold uppercase tracking-widest {{ $statusClass }} border-current">
                    {{ ucfirst(str_replace('_', ' ', $statusKey)) }}: {{ $statusCounts[$statusKey] ?? 0 }}
                </span>
            @endforeach
        </div>

        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Laporan Keuangan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Periode: {{ $start_date->format('d/m/Y') }} - {{ $end_date->format('d/m/Y') }}</p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Data disaring berdasarkan tanggal pinjam dan tanggal kembali.</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-max border-collapse border border-gray-200 dark:border-white/10 text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5 text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Kode</th>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Peminjam</th>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Periode</th>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Status</th>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Sewa</th>
                            <th class="px-4 py-3 border border-gray-200 dark:border-white/10">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-background-dark dark:even:bg-panel-dark">
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">PMJ-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">{{ $borrowing->user->name }}</td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">
                                    {{ $borrowing->tanggal_pinjam->format('Y-m-d') }}
                                    @if($borrowing->tanggal_kembali)
                                        <span class="text-xs text-gray-400 dark:text-gray-500">s/d {{ $borrowing->tanggal_kembali->format('Y-m-d') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">{{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}</td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">{{ $borrowing->borrowingDetails->sum('jumlah') }}</td>
                                <td class="px-4 py-3 border border-gray-200 dark:border-white/10">
                                    @php
                                        $fineAmount = $borrowing->return ? (($borrowing->return->denda ?? 0) + ($borrowing->return->denda_kerusakan ?? 0)) : 0;
                                    @endphp
                                    Rp {{ number_format($fineAmount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-4 border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-gray-400" colspan="6">Tidak ada transaksi untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>'''
path.write_text(text[:start] + new_block + text[end:], encoding='utf-8')
