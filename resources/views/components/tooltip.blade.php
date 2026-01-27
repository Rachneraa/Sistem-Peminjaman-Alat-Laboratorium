{{-- Tooltip Component --}}
@props([
    'text',
    'position' => 'top', // top, bottom, left, right
])

@php
    $positionClasses = match($position) {
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
        'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
        default => 'bottom-full left-1/2 -translate-x-1/2 mb-2', // top
    };
    
    $arrowClasses = match($position) {
        'bottom' => 'bottom-full left-1/2 -translate-x-1/2 border-b-gray-900 dark:border-b-gray-700',
        'left' => 'left-full top-1/2 -translate-y-1/2 border-l-gray-900 dark:border-l-gray-700',
        'right' => 'right-full top-1/2 -translate-y-1/2 border-r-gray-900 dark:border-r-gray-700',
        default => 'top-full left-1/2 -translate-x-1/2 border-t-gray-900 dark:border-t-gray-700', // top
    };
@endphp

<div class="relative inline-flex group" {{ $attributes }}>
    {{ $slot }}
    
    {{-- Tooltip --}}
    <div class="absolute {{ $positionClasses }} z-50 px-3 py-2 text-sm font-medium text-white bg-gray-900 dark:bg-gray-700 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap pointer-events-none scale-95 group-hover:scale-100">
        {{ $text }}
        
        {{-- Arrow --}}
        <div class="absolute {{ $arrowClasses }} w-0 h-0 border-4 border-transparent"></div>
    </div>
</div>
