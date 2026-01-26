@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Log Aktivitas</h1>
    <div class="h-1 w-16 bg-primary mt-2"></div>
</div>

<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                </div>
            </div>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter User</label>
            <select name="user_id" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            <a href="{{ route('admin.activity-logs.index') }}" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <div class="overflow-auto max-h-[75vh]">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aktivitas</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Detail</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($logs as $log)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $log->aktivitas }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $log->detail ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-4">
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px] mb-4">history</span>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada log aktivitas</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        {{ $logs->links('vendor.pagination.industrial') }}
    </div>
</div>
@endsection

