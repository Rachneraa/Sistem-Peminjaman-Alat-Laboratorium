@extends('layouts.app')

@section('title', 'Riwayat Notifikasi')

@section('content')
<div class="mb-6 flex justify-between items-center px-2">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Riwayat Notifikasi</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <div class="flex gap-3">
        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <x-button variant="ghost" size="sm" type="submit" icon="done_all" class="text-gray-500 dark:text-gray-400">
                Tandai Semua Dibaca
            </x-button>
        </form>
    </div>
</div>

{{-- Search & Filter --}}
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border shadow-sm">
    <form method="GET" action="{{ route('notifications.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <div class="relative group">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari isi notifikasi..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 pl-10 transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500">
                    <span class="material-symbols-outlined text-[20px]">search</span>
                </div>
            </div>
        </div>
        <x-button type="submit" variant="primary" icon="search">Cari</x-button>
        @if(request('search'))
            <x-button variant="ghost" :href="route('notifications.index')" icon="refresh">Reset</x-button>
        @endif
    </form>
</div>

<x-card class="overflow-hidden" :padding="false">
    <div class="divide-y divide-gray-100 dark:divide-white/5">
        @forelse($notifications as $notification)
            @php
                $getTypeConfig = function($tipe) {
                    return match($tipe) {
                        'peminjaman_disetujui' => ['icon' => 'check_circle', 'color' => 'text-green-500', 'bg' => 'bg-green-500/10'],
                        'peminjaman_ditolak' => ['icon' => 'cancel', 'color' => 'text-red-500', 'bg' => 'bg-red-500/10'],
                        'peminjaman_baru' => ['icon' => 'add_shopping_cart', 'color' => 'text-blue-500', 'bg' => 'bg-blue-500/10'],
                        'pengembalian_diproses' => ['icon' => 'assignment_turned_in', 'color' => 'text-emerald-500', 'bg' => 'bg-emerald-500/10'],
                        'pengembalian_peminjam' => ['icon' => 'assignment_return', 'color' => 'text-orange-500', 'bg' => 'bg-orange-500/10'],
                        'jatuh_tempo' => ['icon' => 'warning', 'color' => 'text-red-600', 'bg' => 'bg-red-600/10'],
                        'user_dibuat' => ['icon' => 'person_add', 'color' => 'text-indigo-500', 'bg' => 'bg-indigo-500/10'],
                        'alat_dibuat' => ['icon' => 'construction', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
                        'kategori_dibuat' => ['icon' => 'category', 'color' => 'text-amber-500', 'bg' => 'bg-amber-500/10'],
                        'pengingat_pengembalian' => ['icon' => 'notifications_active', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-500/10'],
                        'estimasi_denda' => ['icon' => 'payments', 'color' => 'text-rose-500', 'bg' => 'bg-rose-500/10'],
                        default => ['icon' => 'notifications', 'color' => 'text-gray-500', 'bg' => 'bg-gray-500/10'],
                    };
                };
                $config = $getTypeConfig($notification->tipe);
                $isUnread = !$notification->status_baca;
            @endphp
            <div class="p-6 transition-all hover:bg-gray-50 dark:hover:bg-white/5 relative group {{ $isUnread ? 'bg-primary/[0.02] dark:bg-accent-green/[0.02] border-l-4 border-primary dark:border-accent-green' : '' }}">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full {{ $config['bg'] }} flex items-center justify-center border border-white dark:border-panel-dark shadow-sm">
                            <span class="material-symbols-outlined {{ $config['color'] }} text-[24px]">{{ $config['icon'] }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                {{ $notification->judul }}
                                @if($isUnread)
                                    <span class="w-2 h-2 rounded-full bg-primary dark:bg-accent-green"></span>
                                @endif
                            </h3>
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500 uppercase">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-4">{{ $notification->pesan }}</p>
                        
                        <div class="flex items-center gap-4">
                            <div class="text-[10px] uppercase font-bold text-gray-400 dark:text-gray-500 tracking-widest">{{ $notification->created_at->diffForHumans() }}</div>
                            <div class="flex gap-2">
                                @if($isUnread)
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-primary dark:text-accent-green hover:underline">Tandai Dibaca</button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('notifications.destroy', $notification) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-400 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <x-empty-state 
                icon="notifications_off"
                title="Riwayat Kosong"
                description="Belum ada notifikasi yang masuk untuk Anda."
            />
        @endforelse
    </div>
    @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $notifications->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>
@endsection
