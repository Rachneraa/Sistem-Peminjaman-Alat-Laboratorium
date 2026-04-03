@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Dashboard Admin</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
        <form id="database-export-form" method="POST" action="{{ route('admin.database.export') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
                <span class="material-symbols-outlined text-[18px]">database</span>
                Export Database (.sql)
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card class="industrial-border overflow-hidden" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-primary/10 rounded-2xl">
                    <span class="material-symbols-outlined text-primary text-[32px]">group</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
                <div class="h-full bg-primary w-2/3"></div>
            </div>
        </x-card>

        <x-card class="industrial-border overflow-hidden" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-accent-green/10 rounded-2xl">
                    <span class="material-symbols-outlined text-accent-green text-[32px]">construction</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Alat</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_tools'] }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
                <div class="h-full bg-accent-green w-3/4"></div>
            </div>
        </x-card>

        <x-card class="industrial-border overflow-hidden" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-yellow-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-yellow-500 text-[32px]">pending_actions</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Menunggu</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pending_borrowings'] }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
                <div class="h-full bg-yellow-500 w-1/4"></div>
            </div>
        </x-card>

        <x-card class="industrial-border overflow-hidden" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-purple-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-purple-500 text-[32px]">inventory</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_borrowings'] }}</p>
                </div>
            </div>
            <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
                <div class="h-full bg-purple-500 w-1/2"></div>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-card class="industrial-border" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-indigo-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-indigo-500 text-[32px]">receipt_long</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Pinjam</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_borrowings'] }}</p>
                </div>
            </div>
        </x-card>

        <x-card class="industrial-border" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-teal-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-teal-500 text-[32px]">assignment_return</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Kembali
                    </p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_returns'] }}</p>
                </div>
            </div>
        </x-card>

        <x-card class="industrial-border" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-rose-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-rose-500 text-[32px]">payments</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Denda</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        Rp{{ number_format($stats['total_fines'], 0, ',', '.') }}</p>
                </div>
            </div>
        </x-card>

        <x-card class="industrial-border" :padding="false">
            <div class="p-6 flex items-center gap-4">
                <div class="p-4 bg-orange-500/10 rounded-2xl">
                    <span class="material-symbols-outlined text-orange-500 text-[32px]">today</span>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['returns_today'] }}</p>
                </div>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-card title="Statistik Operasional" icon="auto_graph">
            <x-slot:actions>
                <select id="periodFilter"
                    class="bg-gray-50 dark:bg-gray-800 border-none text-xs font-bold uppercase tracking-widest text-gray-500 rounded-lg focus:ring-0 px-3 py-1 cursor-pointer">
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahun</option>
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulan</option>
                    <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Minggu</option>
                </select>
            </x-slot:actions>
            <div class="h-[300px]">
                <canvas id="borrowingChart"></canvas>
            </div>
        </x-card>

        <x-card title="Alat Terpopuler" subtitle="Top 10 alat paling sering dipinjam" icon="trending_up" :padding="false">
            <div class="h-[300px] overflow-y-auto custom-scrollbar">
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach($popular_tools as $pop)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition flex items-center gap-4 group">
                            <div
                                class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-background-dark border border-gray-200 dark:border-gray-700 overflow-hidden flex-shrink-0">
                                @if($pop->tool->gambar)
                                    <img src="{{ asset($pop->tool->gambar) }}" alt="{{ $pop->tool->nama_alat }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span class="material-symbols-outlined text-[20px]">construction</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4
                                    class="font-bold text-sm text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors">
                                    {{ $pop->tool->nama_alat }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-mono">ID:
                                    #{{ $pop->tool->id }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-primary dark:text-accent-green font-mono">{{ $pop->total }}
                                </div>
                                <div class="text-[8px] text-gray-400 uppercase font-bold tracking-[0.2em]">KALI PINJAM</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-card title="Distribusi Kategori" icon="pie_chart">
            <div class="h-[300px]">
                <canvas id="categoryChart"></canvas>
            </div>
        </x-card>

        <x-card title="Statistik Denda" subtitle="Total denda masuk per bulan (Tahun Ini)" icon="monetization_on">
            <div class="h-[300px]">
                <canvas id="finesChart"></canvas>
            </div>
        </x-card>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
        <div class="p-6 border-b border-gray-200 dark:border-white/5">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Peminjaman Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            ID</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Peminjam</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tanggal Pinjam</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recent_borrowings as $borrowing)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                #{{ $borrowing->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $borrowing->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</td>
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
                                <a href="{{ route('admin.borrowings.show', $borrowing) }}"
                                    class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dbExportForm = document.getElementById('database-export-form');
            if (dbExportForm) {
                dbExportForm.addEventListener('submit', function (event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Export Database?',
                        text: 'Sistem akan membuat backup SQL penuh dan langsung mengunduh file.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, export',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#2563EB'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            dbExportForm.submit();
                        }
                    });
                });
            }

            // Period Filter Handling
            const periodFilter = document.getElementById('periodFilter');
            if (periodFilter) {
                periodFilter.addEventListener('change', function () {
                    const period = this.value;
                    window.location.href = `{{ route('admin.dashboard') }}?period=${period}`;
                });
            }

            // Common Chart Config
            Chart.defaults.font.family = "'Space Grotesk', sans-serif";
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
            const textColor = isDark ? '#9ca3af' : '#6b7280';

            // 1. Operational Chart
            const ctx = document.getElementById('borrowingChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [
                        {
                            label: 'Peminjaman',
                            data: @json($chart_data),
                            borderColor: '#0d5868',
                            backgroundColor: 'rgba(13, 88, 104, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Kembali',
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
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { color: textColor, boxWidth: 10, usePointStyle: true }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: textColor, stepSize: 1 },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });


            // 3. Category Distribution Chart
            const catCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categories_dist->pluck('nama_kategori')),
                    datasets: [{
                        data: @json($categories_dist->pluck('tools_count')),
                        backgroundColor: [
                            '#0d5868', '#16a34a', '#dc2626', '#ca8a04',
                            '#9333ea', '#2563eb', '#0891b2', '#059669'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: textColor, boxWidth: 10, usePointStyle: true }
                        }
                    }
                }
            });

            // 4. Fines Chart
            const finesCtx = document.getElementById('finesChart').getContext('2d');
            new Chart(finesCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Total Denda (Rp)',
                        data: @json($fines_data),
                        backgroundColor: '#ca8a04',
                        borderRadius: 4,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                callback: function (value) { return 'Rp' + value.toLocaleString(); }
                            },
                            grid: { color: gridColor }
                        },
                        x: {
                            ticks: { color: textColor },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection