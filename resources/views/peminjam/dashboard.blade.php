@extends('layouts.app')

@section('title', 'Dashboard Peminjam')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Dashboard Peminjam</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-blue-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-blue-500 text-[32px]">pending_actions</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Pinjaman</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['peminjaman_aktif'] }}</h3>
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
            <div class="p-4 bg-green-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-green-500 text-[32px]">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Kembali</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['returned_borrowings'] }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-green-500 w-3/4"></div>
        </div>
    </x-card>

    <x-card class="industrial-border overflow-hidden" :padding="false">
        <div class="p-6 flex items-center gap-4">
            <div class="p-4 bg-red-500/10 rounded-2xl">
                <span class="material-symbols-outlined text-red-500 text-[32px]">warning</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Terlambat</p>
                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['overdue_count'] }}</h3>
            </div>
        </div>
        <div class="h-1 w-full bg-gray-100 dark:bg-white/5">
            <div class="h-full bg-red-500 w-full"></div>
        </div>
    </x-card>
</div>

@if($stats['overdue_count'] > 0)
    <x-alert type="error" title="Peminjaman Terlambat!" class="mb-6 industrial-border" :dismissible="false">
        <x-slot:message>
            Ada <strong class="text-red-500">{{ $stats['overdue_count'] }}</strong> peminjaman yang telah melewati batas waktu pengembalian.
        </x-slot:message>
        <div class="mt-4">
            <x-button variant="danger" size="sm" :href="route('peminjam.borrowings.index')" icon="warning">
                Lihat Detail & Kembalikan
            </x-button>
        </div>
    </x-alert>
@elseif($nearest_due)
    @php
        $daysUntilDue = now()->diffInDays($nearest_due->jatuh_tempo, false);
        $isDueToday = $daysUntilDue <= 0;
    @endphp
    
    <x-card class="mb-6 border-l-4 {{ $isDueToday ? 'border-orange-500' : 'border-yellow-500' }}" :padding="false">
        <div class="p-6 flex items-start gap-4">
            <div class="p-3 {{ $isDueToday ? 'bg-orange-500/10' : 'bg-yellow-500/10' }} rounded-full">
                <span class="material-symbols-outlined {{ $isDueToday ? 'text-orange-500' : 'text-yellow-500' }}">event_upcoming</span>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-900 dark:text-white mb-1 uppercase tracking-wider text-sm">
                    {{ $isDueToday ? '⚠️ Segera Kembalikan Alat' : 'ℹ️ Pengingat Jatuh Tempo' }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Peminjaman <strong>#{{ $nearest_due->id }}</strong> jatuh tempo dalam 
                    <strong class="{{ $isDueToday ? 'text-orange-500' : 'text-yellow-500' }}">
                        {{ $daysUntilDue <= 0 ? 'Hari Ini' : abs(round($daysUntilDue)) . ' Hari' }}
                    </strong>
                </p>
            </div>
            <x-button size="sm" variant="ghost" :href="route('peminjam.borrowings.show', $nearest_due)">Detail</x-button>
        </div>
    </x-card>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Left Column: Trend & History --}}
    <div class="lg:col-span-2 space-y-6">
        <x-card title="Trend Peminjaman" subtitle="Jumlah peminjaman 6 bulan terakhir" icon="auto_graph">
            <div class="h-[250px]">
                <canvas id="peminjamTrendChart"></canvas>
            </div>
        </x-card>

        <x-card title="Peminjaman Terbaru" icon="history">
            <x-slot:actions>
                <a href="{{ route('peminjam.borrowings.index') }}" class="text-xs font-bold text-primary hover:underline uppercase tracking-widest">Semua</a>
            </x-slot:actions>
            
            <div class="space-y-4">
                @forelse($my_borrowings as $borrowing)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 hover-lift">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-white/10 flex items-center justify-center font-mono text-xs font-bold text-primary">
                                #{{ $borrowing->id }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $borrowing->borrowingDetails->first()->tool->nama_alat ?? 'Alat' }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ $borrowing->tanggal_pinjam->format('d M Y') }}</p>
                            </div>
                        </div>
                        <x-badge :type="match($borrowing->status) {
                            'disetujui' => 'primary',
                            'dikembalikan' => 'success',
                            'ditolak' => 'danger',
                            default => 'warning'
                        }" size="sm">
                            {{ $borrowing->status }}
                        </x-badge>
                    </div>
                @empty
                    <p class="text-center py-4 text-gray-500 text-sm">Belum ada peminjaman</p>
                @endforelse
            </div>
        </x-card>
    </div>

    {{-- Right Column: Tools & Actions --}}
    <div class="space-y-6">
        <x-card title="Alat Tersedia" subtitle="Mungkin Anda butuh" icon="inventory_2">
            <div class="space-y-4">
                @foreach($available_tools as $tool)
                    <div class="group cursor-pointer">
                        <div class="flex gap-4 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <div class="w-16 h-16 rounded-md bg-gray-100 dark:bg-gray-800 flex-shrink-0 overflow-hidden border border-gray-100 dark:border-white/5">
                                @if($tool->gambar)
                                    <img src="{{ asset($tool->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span class="material-symbols-outlined">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white truncate group-hover:text-primary transition-colors">{{ $tool->nama_alat }}</h4>
                                <p class="text-[10px] text-gray-500 uppercase font-mono">{{ $tool->category->nama_kategori }}</p>
                                <div class="mt-1 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-accent-green uppercase">Stok {{ $tool->stok_tersedia }}</span>
                                    <a href="{{ route('peminjam.tools.show', $tool) }}" class="text-[10px] font-bold text-primary uppercase hover:underline">Pinjam →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">
                <x-button :href="route('peminjam.tools.index')" variant="secondary" size="sm" class="w-full">
                    Lihat Semua Alat
                </x-button>
            </div>
        </x-card>

        <x-card class="bg-primary text-white border-none shadow-xl shadow-primary/30" :padding="false" :hover="true">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute top-[-20px] right-[-20px] rotate-12 opacity-10">
                    <span class="material-symbols-outlined text-[120px]">add_shopping_cart</span>
                </div>
                <h3 class="text-lg font-bold mb-2 uppercase tracking-wider relative z-10">Pinjam Alat</h3>
                <p class="text-xs text-white/70 mb-6 relative z-10">Butuh alat tambahan? Ajukan permintaan peminjaman baru sekarang.</p>
                <a href="{{ route('peminjam.borrowings.create') }}" class="px-5 py-2.5 bg-white text-primary hover:bg-gray-100 rounded-lg transition-all flex items-center justify-center gap-2 text-sm font-bold w-full relative z-10">
                    Mulai Ajuan
                </a>
            </div>
        </x-card>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('peminjamTrendChart').getContext('2d');
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($trend_labels),
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: @json($trend_data),
                    backgroundColor: '#0d5868',
                    borderRadius: 4,
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
                        grid: { color: gridColor },
                        ticks: { color: textColor, stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor }
                    }
                }
            }
        });
    });
</script>
@endsection

