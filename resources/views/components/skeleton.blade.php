{{-- Skeleton Loading Component --}}
@props([
    'type' => 'text', // text, card, table, circle, button
    'lines' => 3,
    'height' => null,
    'width' => null,
])

@php
    $baseClasses = 'animate-pulse bg-gray-200 dark:bg-gray-700 rounded';
    $heightClass = $height ?? match($type) {
        'text' => 'h-4',
        'card' => 'h-48',
        'table' => 'h-12',
        'circle' => 'h-12 w-12 rounded-full',
        'button' => 'h-10',
        default => 'h-4'
    };
    $widthClass = $width ?? 'w-full';
@endphp

@if($type === 'text')
    <div class="space-y-3">
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $baseClasses }} {{ $heightClass }} {{ $i === $lines - 1 ? 'w-4/5' : $widthClass }}"></div>
        @endfor
    </div>
@elseif($type === 'card')
    <div class="{{ $baseClasses }} {{ $heightClass }} {{ $widthClass }}">
        <div class="p-6 space-y-4">
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-3/4"></div>
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-1/2"></div>
            <div class="h-4 bg-gray-300 dark:bg-gray-600 rounded w-5/6"></div>
        </div>
    </div>
@elseif($type === 'table')
    <div class="space-y-3">
        @for($i = 0; $i < $lines; $i++)
            <div class="{{ $baseClasses }} {{ $heightClass }} {{ $widthClass }}"></div>
        @endfor
    </div>
@elseif($type === 'circle')
    <div class="{{ $baseClasses }} {{ $heightClass }} rounded-full"></div>
@elseif($type === 'button')
    <div class="{{ $baseClasses }} {{ $heightClass }} {{ $width ?? 'w-32' }}"></div>
@else
    <div class="{{ $baseClasses }} {{ $heightClass }} {{ $widthClass }}" {{ $attributes }}></div>
@endif
