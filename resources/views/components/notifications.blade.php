@php
    $user = auth()->user();
    $unreadCount = $user->unreadNotificationsCount();
    $notifications = $user->notifications()->unread()->latest()->limit(5)->get();
@endphp

@php
    $user = auth()->user();
    $unreadCount = $user->unreadNotificationsCount();
    $notifications = $user->notifications()->unread()->latest()->limit(5)->get();

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
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors group">
        <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">notifications</span>
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white dark:border-panel-dark text-[8px] font-bold text-white items-center justify-center">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 lg:w-96 bg-white dark:bg-panel-dark rounded-xl shadow-2xl border border-gray-200 dark:border-white/10 z-50 overflow-hidden industrial-border"
         style="display: none;">
        
        <div class="p-4 border-b border-gray-200 dark:border-white/10 flex justify-between items-center bg-gray-50 dark:bg-panel-dark">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white font-display uppercase tracking-widest text-xs">Notifikasi</h3>
                @if($unreadCount > 0)
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Ada {{ $unreadCount }} pesan baru</p>
                @endif
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-[10px] uppercase font-bold tracking-wider text-primary dark:text-accent-green hover:underline">Tandai semua dibaca</button>
                </form>
            @endif
        </div>

        <div class="divide-y divide-gray-100 dark:divide-white/5 max-h-[400px] overflow-y-auto">
            @forelse($notifications as $notification)
                @php $config = $getTypeConfig($notification->tipe); @endphp
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition group relative">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full {{ $config['bg'] }} flex items-center justify-center">
                                <span class="material-symbols-outlined {{ $config['color'] }} text-[20px]">{{ $config['icon'] }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="font-bold text-sm text-gray-900 dark:text-white font-display truncate pr-8">{{ $notification->judul }}</p>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $notification->pesan }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-mono uppercase">{{ $notification->created_at->diffForHumans() }}</span>
                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="text-green-500 hover:text-green-600 dark:text-accent-green transition-colors" title="Tandai dibaca">
                                            <span class="material-symbols-outlined text-[18px]">done</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="relative inline-block mb-4">
                        <div class="absolute inset-0 bg-gray-100 dark:bg-white/5 rounded-full blur-xl"></div>
                        <span class="material-symbols-outlined relative text-gray-300 dark:text-gray-700 text-5xl">notifications_off</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>
        
        <div class="p-3 bg-gray-50 dark:bg-white/5 border-t border-gray-200 dark:border-white/10 text-center">
            <a href="{{ route('notifications.index') }}" class="text-[10px] uppercase font-bold tracking-widest text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-white transition-colors">Lihat Semua Riwayat</a>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

