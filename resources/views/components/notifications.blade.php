@php
    $user = auth()->user();
    $unreadCount = $user->unreadNotificationsCount();
    $notifications = $user->notifications()->unread()->latest()->limit(5)->get();
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-panel-dark"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-panel-dark rounded-lg shadow-xl border border-gray-200 dark:border-white/10 z-50 max-h-96 overflow-y-auto">
        <div class="p-4 border-b border-gray-200 dark:border-white/10 flex justify-between items-center sticky top-0 bg-white dark:bg-panel-dark z-10">
            <h3 class="font-semibold text-gray-900 dark:text-white font-display uppercase tracking-wider text-sm">Notifikasi</h3>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-[10px] uppercase font-bold tracking-wider text-accent-green hover:text-primary dark:hover:text-white transition-colors">Tandai dibaca</button>
                </form>
            @endif
        </div>
        <div class="divide-y divide-gray-100 dark:divide-white/5">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-white/5 transition group">
                    <div class="flex justify-between items-start gap-3">
                        <div class="flex-1">
                            <p class="font-bold text-sm text-gray-900 dark:text-white font-display">{{ $notification->judul }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">{{ $notification->pesan }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-2 font-mono uppercase">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="text-accent-green hover:text-primary dark:hover:text-white transition-colors" title="Tandai dibaca">
                                    <span class="material-symbols-outlined text-[18px]">check</span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('notifications.destroy', $notification) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 dark:hover:text-red-300 transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <span class="material-symbols-outlined text-4xl mb-2 opacity-20">notifications_off</span>
                    <p class="text-sm">Tidak ada notifikasi</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

