{{-- Dropdown Component --}}
@props([
    'align' => 'right', // left, right
    'width' => '48', // width in rem
    'trigger',
])

@php
    $alignmentClasses = match($align) {
        'left' => 'origin-top-left left-0',
        default => 'origin-top-right right-0',
    };
    
    $widthClasses = match($width) {
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
        default => 'w-48',
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    {{-- Trigger --}}
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>

    {{-- Dropdown Menu --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-2 {{ $widthClasses }} {{ $alignmentClasses }} bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 rounded-lg shadow-xl py-1"
        style="display: none;"
        @click="open = false"
    >
        {{ $slot }}
    </div>
</div>
