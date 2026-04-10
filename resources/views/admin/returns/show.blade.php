@extends('layouts.app')

@section('title', 'Detail Pengembalian')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detail
                    Pengembalian</h1>
                <div class="flex items-center gap-3 mt-2">
                    <div class="h-1 w-16 bg-primary"></div>
                    <span class="text-sm text-gray-500 dark:text-gray-400 font-mono">#{{ $return->id }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.returns.index') }}"
                    class="px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2 font-medium">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </a>
                <a href="{{ route('admin.returns.edit', $return) }}"
                    class="px-5 py-2.5 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg shadow-yellow-600/20">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.returns.destroy', $return) }}" class="inline delete-form"
                    data-id="{{ $return->id }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="handleDeleteReturn(this)"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg shadow-red-600/20">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Return Info -->
            <div
                class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    Informasi Pengembalian
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">
                            Peminjam</p>
                        <p class="text-gray-900 dark:text-white font-medium text-lg">{{ $return->borrowing->user->name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">
                            Tanggal Kembali</p>
                        <p class="text-gray-900 dark:text-white font-mono flex items-center gap-2">
                            <span
                                class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-sm">calendar_today</span>
                            {{ $return->tanggal_kembali->format('d/m/Y') }}
                        </p>
                    </div>
                    @if($return->keterangan)
                        <div>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-2">
                                Keterangan</p>
                            <div
                                class="bg-gray-50 dark:bg-background-dark p-3 rounded-lg border border-gray-200 dark:border-gray-700/50 text-sm text-gray-600 dark:text-gray-300">
                                {{ $return->keterangan }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fines Info -->
            <div
                class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">payments</span>
                    Rincian Denda
                </h3>

                @php
                    $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                @endphp

                <div class="space-y-4">
                    <div
                        class="flex justify-between items-center p-3 bg-gray-50 dark:bg-background-dark rounded border border-gray-200 dark:border-gray-700/50">
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Keterlambatan</p>
                            @if($return->terlambat_hari > 0)
                                <span
                                    class="text-[10px] text-red-500 dark:text-red-400 font-bold uppercase tracking-wider">{{ $return->terlambat_hari }}
                                    Hari</span>
                            @endif
                            @if($return->denda_diabaikan)
                                <div class="text-[10px] text-yellow-500 font-bold uppercase tracking-wider mt-1">Diabaikan
                                    Petugas</div>
                            @endif
                        </div>
                        <span class="text-gray-900 dark:text-white font-mono">Rp
                            {{ number_format($return->denda, 0, ',', '.') }}</span>
                    </div>

                    @if($return->denda_diabaikan)
                        <div class="p-3 bg-yellow-500/10 rounded border border-yellow-500/20">
                            <p class="text-[10px] text-yellow-500 font-bold uppercase tracking-widest mb-1">Alasan Abaikan Denda
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $return->alasan_abaikan_denda ?: '-' }}</p>
                            @if(($return->denda_keterlambatan_awal ?? 0) > 0)
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Nominal denda keterlambatan awal: Rp
                                    {{ number_format($return->denda_keterlambatan_awal, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    @endif

                    <div
                        class="flex justify-between items-center p-3 bg-gray-50 dark:bg-background-dark rounded border border-gray-200 dark:border-gray-700/50">
                        <p class="text-xs text-gray-600 dark:text-gray-400">Kerusakan</p>
                        <span class="text-gray-900 dark:text-white font-mono">Rp
                            {{ number_format($return->denda_kerusakan ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="pt-4 mt-2 border-t border-gray-200 dark:border-gray-700 flex justify-between items-end">
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-widest">Total Denda
                        </p>
                        <p class="text-2xl font-bold font-mono {{ $totalDenda > 0 ? 'text-red-500' : 'text-green-500' }}">
                            Rp {{ number_format($totalDenda, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tools Returned -->
        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border mb-8">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700/50">
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">inventory_2</span>
                    Alat yang Dikembalikan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nama Alat</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($return->borrowing->borrowingDetails as $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $detail->tool->nama_alat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $detail->tool->category->nama_kategori }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-bold bg-blue-500/10 text-blue-400 rounded border border-blue-500/20">
                                        {{ $detail->jumlah }} Unit
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function handleDeleteReturn(button) {
                const form = button.closest('form');
                const returnId = form.dataset.id;

                showConfirmModal({
                    title: 'Hapus Pengembalian',
                    message: `Yakin ingin menghapus pengembalian #${returnId}? Tindakan ini akan mengembalikan status peminjaman menjadi disetujui.`,
                    type: 'warning',
                    okText: 'Ya, Hapus',
                    onConfirm: function () {
                        form.submit();
                    }
                });
            }
        </script>
    </div>
@endsection