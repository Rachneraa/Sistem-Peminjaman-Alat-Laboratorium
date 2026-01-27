{{-- Empty State Component --}}
@props([
    'icon' => 'inbox',
    'title' => 'Tidak ada data',
    'description' => null,
    'actionUrl' => null,
    'actionText' => null,
])

<div class="flex flex-col items-center justify-center text-center py-12 px-4" {{ $attributes }}>
    {{-- Icon --}}
    <div class="mb-4 relative">
        <div class="absolute inset-0 bg-primary/5 rounded-full blur-2xl"></div>
        <div class="relative bg-gray-100 dark:bg-gray-800 rounded-full p-6">
            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px]">{{ $icon }}</span>
        </div>
    </div>

    {{-- Title --}}
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h3>

    {{-- Description --}}
    @if($description)
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 max-w-md">{{ $description }}</p>
    @endif

    {{-- Action Button --}}
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add</span>
            {{ $actionText }}
        </a>
    @endif

    {{-- Custom Slot --}}
    {{ $slot }}
</div>
