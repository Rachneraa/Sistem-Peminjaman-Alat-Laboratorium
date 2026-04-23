@extends('layouts.app')

@section('title', 'Cetak Laporan')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Cetak Laporan</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>

    @if (session('info'))
        <div class="mb-6 p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 mt-0.5">info</span>
                <div>
                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ session('info') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-6">
        <!-- FILTER TANGGAL GLOBAL -->
        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
            <form method="GET" action="{{ route('petugas.reports.index') }}"
                class="grid gap-4 lg:grid-cols-[1fr_1fr_auto] items-end">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                        Mulai</label>
                    <input type="date" name="start_date" value="{{ $start_date->format('Y-m-d') }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required>
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                        Selesai</label>
                    <input type="date" name="end_date" value="{{ $end_date->format('Y-m-d') }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required>
                </div>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-primary px-8 py-3 text-sm font-semibold text-white transition-all hover:bg-primary/90"
                    style="border-radius: 50px;">
                    <span class="material-symbols-outlined">search</span>
                    Tampilkan
                </button>
            </form>
        </div>

        <!-- TAB NAVIGATION -->
        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
            <div class="flex flex-wrap gap-4 mb-6">
                <button onclick="switchTab('persewaan')" id="tab-persewaan-btn"
                    class="px-6 py-3 font-semibold text-white bg-primary rounded-full uppercase tracking-wider text-sm transition-all hover:bg-primary/90">
                    PERSEWAAN
                </button>
                <button onclick="switchTab('barang')" id="tab-barang-btn"
                    class="px-6 py-3 font-semibold text-primary border-2 border-primary bg-transparent rounded-full uppercase tracking-wider text-sm transition-all hover:bg-primary/5">
                    BARANG
                </button>
            </div>

            <!-- TAB PERSEWAAN -->
            <div id="tab-persewaan" class="space-y-6">
                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-900 dark:to-black rounded-3xl p-6 border border-gray-700 text-center">
                        <p class="text-gray-400 text-sm uppercase tracking-widest mb-2">Total Transaksi</p>
                        <p class="text-4xl font-bold text-white">{{ $totalTransactions }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-900 dark:to-black rounded-3xl p-6 border border-gray-700 text-center">
                        <p class="text-gray-400 text-sm uppercase tracking-widest mb-2">Total Denda (Terlambat)</p>
                        <p class="text-3xl font-bold text-white">Rp {{ number_format($totalLateFine, 0, ',', '.') }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-900 dark:to-black rounded-3xl p-6 border border-gray-700 text-center">
                        <p class="text-gray-400 text-sm uppercase tracking-widest mb-2">Total Denda (Kerusakan)</p>
                        <p class="text-3xl font-bold text-white">Rp {{ number_format($totalDamageFine, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- TABLE -->
                <div
                    class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Laporan
                            Keuangan</h3>
                        <button onclick="showConfirmation('{{ route('petugas.reports.financial', ['start_date' => $start_date->format('Y-m-d'), 'end_date' => $end_date->format('Y-m-d')]) }}')"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary hover:bg-primary/90 px-4 py-2 text-sm font-semibold text-white transition-all">
                            <span class="material-symbols-outlined text-lg">download</span>
                            Export Data
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full min-w-max border-collapse border border-gray-200 dark:border-white/10 text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Peminjam</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Tanggal Peminjaman - Kembali</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Denda Terlambat</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Denda Kerusakan</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)
                                    @php $total = ($return->denda ?? 0) + ($return->denda_kerusakan ?? 0); @endphp
                                    <tr
                                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-background-dark dark:even:bg-panel-dark hover:bg-gray-100 dark:hover:bg-panel-dark/50 transition">
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white">
                                            {{ $return->borrowing->user->name }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white">
                                            {{ $return->tanggal_kembali->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white">Rp
                                            {{ number_format($return->denda ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white">Rp
                                            {{ number_format($return->denda_kerusakan ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-900 dark:text-white">Rp
                                            {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-4 border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-gray-400 py-8"
                                            colspan="5">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>

            <!-- TAB BARANG -->
            <div id="tab-barang" class="space-y-6 hidden">
                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-900 dark:to-black rounded-3xl p-6 border border-gray-700 text-center">
                        <p class="text-gray-400 text-sm uppercase tracking-widest mb-2">Total Alat</p>
                        <p class="text-4xl font-bold text-white">{{ $totalTools }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-red-900 to-red-950 dark:from-red-900 dark:to-red-950 rounded-3xl p-6 border border-red-700 text-center">
                        <p class="text-red-300 text-sm uppercase tracking-widest mb-2">Alat Kondisi Buruk</p>
                        <p class="text-4xl font-bold text-white">{{ $totalBadCondition }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-amber-900 to-amber-950 dark:from-amber-900 dark:to-amber-950 rounded-3xl p-6 border border-amber-700 text-center">
                        <p class="text-amber-300 text-sm uppercase tracking-widest mb-2">Alat Butuh Perbaikan</p>
                        <p class="text-4xl font-bold text-white">{{ $totalNeedRepair }}</p>
                    </div>
                </div>

                <!-- TABLE -->
                <div
                    class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-3xl p-6 industrial-border">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Laporan Barang
                        </h3>
                        <button onclick="showConfirmation('{{ route('petugas.reports.goods', ['start_date' => $start_date->format('Y-m-d'), 'end_date' => $end_date->format('Y-m-d')]) }}')"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary hover:bg-primary/90 px-4 py-2 text-sm font-semibold text-white transition-all">
                            <span class="material-symbols-outlined text-lg">download</span>
                            Export Data
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table
                            class="w-full min-w-max border-collapse border border-gray-200 dark:border-white/10 text-left text-sm">
                            <thead class="bg-gray-50 dark:bg-white/5">
                                <tr>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        ID</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Nama Alat</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Kondisi</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Persediaan</th>
                                    <th
                                        class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-widest text-xs">
                                        Banyak Peminjam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tools as $tool)
                                    <tr
                                        class="odd:bg-white even:bg-gray-50 dark:odd:bg-background-dark dark:even:bg-panel-dark hover:bg-gray-100 dark:hover:bg-panel-dark/50 transition">
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 font-semibold text-gray-900 dark:text-white">
                                            {{ $tool['id'] }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white">
                                            {{ $tool['nama_alat'] }}</td>
                                        <td class="px-4 py-3 border border-gray-200 dark:border-white/10">
                                            <div class="text-xs space-y-1">
                                                <div class="text-emerald-600 dark:text-emerald-400">✓
                                                    {{ $tool['kondisi_baik'] }} Baik</div>
                                                <div class="text-amber-600 dark:text-amber-400">⚙
                                                    {{ $tool['kondisi_perbaikan'] }} Perbaikan</div>
                                                <div class="text-red-600 dark:text-red-400">✕ {{ $tool['kondisi_rusak'] }} Rusak
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="px-4 py-3 border border-gray-200 dark:border-white/10 text-center font-semibold text-gray-900 dark:text-white">
                                            {{ $tool['persediaan'] }}</td>
                                        <td
                                            class="px-4 py-3 border border-gray-200 dark:border-white/10 text-center font-semibold text-gray-900 dark:text-white">
                                            {{ $tool['banyak_peminjam'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-4 py-4 border border-gray-200 dark:border-white/10 text-center text-gray-500 dark:text-gray-400 py-8"
                                            colspan="5">Tidak ada data untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $tools->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONFIRMATION MODAL -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-panel-dark rounded-3xl p-8 max-w-md w-full mx-4 border border-gray-200 dark:border-white/10 shadow-2xl">
            <div class="flex items-start gap-4 mb-4">
                <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30">
                    <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">help</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Konfirmasi Export</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Yakin ingin export data?</p>
                </div>
            </div>
            <div class="flex gap-3 justify-end pt-4">
                <button onclick="closeConfirmation()"
                    class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                    Tidak
                </button>
                <button onclick="confirmExport()"
                    class="px-4 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-primary/90 transition-all">
                    Ya, Export
                </button>
            </div>
        </div>
    </div>

    <script>
        let exportUrl = null;

        function showConfirmation(url) {
            exportUrl = url;
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        function closeConfirmation() {
            exportUrl = null;
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function confirmExport() {
            if (exportUrl) {
                window.location.href = exportUrl;
                closeConfirmation();
            }
        }

        // Close modal when pressing Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeConfirmation();
            }
        });

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmation();
            }
        });

        function switchTab(tab) {
            // Hide all tabs
            document.getElementById('tab-persewaan').classList.add('hidden');
            document.getElementById('tab-barang').classList.add('hidden');

            // Reset button styles
            document.getElementById('tab-persewaan-btn').classList.remove('bg-primary', 'text-white');
            document.getElementById('tab-persewaan-btn').classList.add('border-2', 'border-primary', 'bg-transparent', 'text-primary');
            document.getElementById('tab-barang-btn').classList.remove('border-2', 'border-primary', 'bg-transparent', 'text-primary');
            document.getElementById('tab-barang-btn').classList.add('bg-primary', 'text-white');

            // Show selected tab & highlight button
            if (tab === 'persewaan') {
                document.getElementById('tab-persewaan').classList.remove('hidden');
                document.getElementById('tab-persewaan-btn').classList.remove('border-2', 'border-primary', 'bg-transparent', 'text-primary');
                document.getElementById('tab-persewaan-btn').classList.add('bg-primary', 'text-white');
            } else {
                document.getElementById('tab-barang').classList.remove('hidden');
                document.getElementById('tab-barang-btn').classList.remove('border-2', 'border-primary', 'bg-transparent', 'text-primary');
                document.getElementById('tab-barang-btn').classList.add('bg-primary', 'text-white');
            }
        }
    </script>
@endsection