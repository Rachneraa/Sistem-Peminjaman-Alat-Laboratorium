@extends('layouts.app')

@section('title', 'Pengaturan Denda')

@section('content')
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pengaturan Denda</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Atur nominal denda per alat. Nilai ini dipakai untuk
                semua perhitungan denda.</p>
        </div>
        <a href="{{ route('admin.denda.export') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
            <span class="material-symbols-outlined text-[18px]">download</span>
            Export CSV
        </a>
    </div>

    <x-card class="industrial-border" :padding="false">
        <form method="POST" action="{{ route('admin.denda.update') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Alat</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Stok</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Denda / Hari</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($tools as $tool)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $tool->nama_alat }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $tool->category->nama_kategori ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $tool->stok_total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="tools[{{ $tool->id }}][denda_per_hari]"
                                        value="{{ old('tools.' . $tool->id . '.denda_per_hari', $tool->denda_per_hari ?? 5000) }}"
                                        min="0" step="1000"
                                        class="w-40 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-3">
                <button type="submit"
                    class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-card>
@endsection