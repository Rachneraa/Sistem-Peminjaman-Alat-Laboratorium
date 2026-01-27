{{-- Dropdown Link Component --}}
@props([
    'icon' => null,
    'danger' => false,
])

<a {{ $attributes->merge(['class' => 'flex items-center gap-3 px-4 py-2 text-sm transition-colors ' . ($danger ? 'text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800')]) }}>
    @if($icon)
        <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
    @endif
    {{ $slot }}
</a>
