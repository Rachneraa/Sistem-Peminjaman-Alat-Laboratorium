@extends('layouts.app')

@section('title', 'Pengembalian Alat')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pengembalian Alat</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

@php
    $activeFiltersCount = collect(request()->only(['search', 'denda']))->filter()->count();
@endphp

<x-filter-panel :action="route('petugas.returns.all')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari Peminjam</label>
        <div class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status Denda</label>
        <select name="denda" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua</option>
            <option value="ada" {{ request('denda') == 'ada' ? 'selected' : '' }}>Ada Denda</option>
            <option value="tidak_ada" {{ request('denda') == 'tidak_ada' ? 'selected' : '' }}>Tanpa Denda</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Waktu Kembali</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Item Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Denda</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($returns as $return)
                    @php
                        $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $return->borrowing->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">ID: #{{ $return->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $return->tanggal_kembali->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @foreach($return->borrowing->borrowingDetails->take(2) as $detail)
                                    <div class="text-xs text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-primary"></span>
                                        {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})
                                    </div>
                                @endforeach
                                @if($return->borrowing->borrowingDetails->count() > 2)
                                    <div class="text-[10px] text-gray-400 italic pl-3">+ {{ $return->borrowing->borrowingDetails->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-[10px] space-y-1 uppercase font-bold tracking-wider">
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Keterlambatan:</span>
                                    <span class="{{ $return->denda > 0 ? 'text-yellow-500' : 'text-gray-400' }}">Rp{{ number_format($return->denda, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span class="text-gray-500">Kerusakan:</span>
                                    <span class="{{ ($return->denda_kerusakan ?? 0) > 0 ? 'text-orange-500' : 'text-gray-400' }}">Rp{{ number_format($return->denda_kerusakan ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="$totalDenda > 0 ? 'danger' : 'success'" size="md">
                                Rp{{ number_format($totalDenda, 0, ',', '.') }}
                            </x-badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="history"
                                title="Riwayat Kosong"
                                description="Belum ada data pengembalian alat yang tercatat."
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($returns->isNotEmpty())
            <tfoot class="bg-gray-50/50 dark:bg-panel-dark/50 border-t border-gray-200 dark:border-white/10">
                @php
                    $totalDendaKeterlambatan = $returns->sum('denda');
                    $totalDendaKerusakan = $returns->sum('denda_kerusakan');
                    $totalKeseluruhan = $totalDendaKeterlambatan + $totalDendaKerusakan;
                @endphp
                <tr>
                    <td colspan="4" class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Halaman Ini:</td>
                    <td class="px-6 py-3 text-sm font-bold text-gray-900 dark:text-white">
                        Rp{{ number_format($totalKeseluruhan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    @if($returns->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $returns->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>
@endsection





