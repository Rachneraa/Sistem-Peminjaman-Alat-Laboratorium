@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Dashboard Admin</h1>
    <div class="h-1 w-16 bg-primary mt-2"></div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-primary/20 rounded-full">
                <span class="material-symbols-outlined text-primary text-[28px]">group</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-accent-green/20 rounded-full">
                <span class="material-symbols-outlined text-accent-green text-[28px]">construction</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Alat</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_tools'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-500/20 rounded-full">
                <span class="material-symbols-outlined text-yellow-400 text-[28px]">pending_actions</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Menunggu Persetujuan</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pending_borrowings'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-purple-500/20 rounded-full">
                <span class="material-symbols-outlined text-purple-400 text-[28px]">inventory</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peminjaman Aktif</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_borrowings'] }}</p>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-indigo-500/20 rounded-full">
                <span class="material-symbols-outlined text-indigo-400 text-[28px]">receipt_long</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Peminjaman</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_borrowings'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-teal-500/20 rounded-full">
                <span class="material-symbols-outlined text-teal-400 text-[28px]">assignment_return</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Pengembalian</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_returns'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-rose-500/20 rounded-full">
                <span class="material-symbols-outlined text-rose-400 text-[28px]">payments</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Denda</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($stats['total_fines'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex items-center">
            <div class="p-3 bg-orange-500/20 rounded-full">
                <span class="material-symbols-outlined text-orange-400 text-[28px]">today</span>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengembalian Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['returns_today'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                Statistik Operasional 
                @if($period == 'week') Minggu Ini
                @elseif($period == 'month') Bulan Ini
                @else Tahun Ini
                @endif
            </h2>
            <select id="periodFilter" class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/5 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block px-4 pr-10 py-2 transition-all min-w-[140px] cursor-pointer">
                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu Ini</option>
            </select>
        </div>
        <canvas id="borrowingChart"></canvas>
    </div>

    <!-- Top 10 Popular Tools -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Alat Terpopuler (Top 10)</h2>
        <div class="space-y-2 max-h-[350px] overflow-y-auto">
            @foreach($popular_tools as $index => $item)
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700/50 hover:border-gray-300 dark:hover:border-gray-600 transition">
                    <span class="flex-shrink-0 flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-700 text-xs font-bold text-gray-600 dark:text-gray-400">
                        {{ $index + 1 }}
                    </span>
                    
                    <div class="flex-shrink-0">
                        @if($item->tool->gambar)
                            <img src="{{ asset($item->tool->gambar) }}" 
                                 alt="{{ $item->tool->nama_alat }}" 
                                 class="w-12 h-12 object-cover rounded-md">
                        @else
                            <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white break-words">
                            {{ $item->tool->nama_alat }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $item->total }}x Dipinjam
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
    <div class="p-6 border-b border-gray-200 dark:border-white/5">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Peminjaman Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recent_borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">#{{ $borrowing->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $borrowing->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($borrowing->status == 'disetujui') bg-green-500/20 text-green-400
                                @elseif($borrowing->status == 'ditolak') bg-red-500/20 text-red-400
                                @elseif($borrowing->status == 'dikembalikan') bg-blue-500/20 text-blue-400
                                @else bg-yellow-500/20 text-yellow-400
                                @endif">
                                {{ ucfirst($borrowing->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Tidak ada data</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Period Filter Handling
        const periodFilter = document.getElementById('periodFilter');
        if (periodFilter) {
            periodFilter.addEventListener('change', function() {
                const period = this.value;
                window.location.href = `{{ route('admin.dashboard') }}?period=${period}`;
            });
        }

        const ctx = document.getElementById('borrowingChart').getContext('2d');
        
        // Detect dark mode
        const isDark = document.documentElement.classList.contains('dark');
        
        // Set colors based on theme
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const textColor = isDark ? '#9ca3af' : '#6b7280';
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                {
                    label: 'Peminjaman Baru',
                    data: @json($chart_data),
                    borderColor: '#0d5868',
                    backgroundColor: 'rgba(13, 88, 104, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Peminjaman Aktif',
                    data: @json($active_data),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Pengembalian',
                    data: @json($return_data),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Terlambat',
                    data: @json($overdue_data),
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

