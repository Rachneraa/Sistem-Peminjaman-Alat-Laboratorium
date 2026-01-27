{{-- Badge Component --}}
@props([
    'type' => 'default', // default, success, danger, warning, info, primary
    'size' => 'md', // sm, md, lg
    'pulse' => false,
])

@php
    $typeClasses = match($type) {
        'success' => 'bg-green-500/10 text-green-500 dark:text-green-400 border-green-500/20',
        'danger' => 'bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20',
        'warning' => 'bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20',
        'info' => 'bg-blue-500/10 text-blue-500 dark:text-blue-400 border-blue-500/20',
        'primary' => 'bg-primary/10 text-primary border-primary/20',
        default => 'bg-gray-500/10 text-gray-500 dark:text-gray-400 border-gray-500/20',
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        'lg' => 'px-4 py-1.5 text-sm',
        default => 'px-3 py-1 text-xs',
    };
@endphp

<span class="inline-flex items-center gap-1 rounded-full font-bold uppercase tracking-wider border {{ $typeClasses }} {{ $sizeClasses }} {{ $pulse ? 'pulse-badge' : '' }}" {{ $attributes }}>
    {{ $slot }}
</span>
