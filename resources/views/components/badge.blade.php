{{-- Badge Component --}}
@props([
    'type' => 'default', // default, success, danger, warning, info, primary
    'size' => 'md', // sm, md, lg
    'pulse' => false,
])
@php
    $typeClasses = match ($type) {
        'success' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
        'danger' => 'bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20',
        'warning' => 'bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20',
        'info' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
        'primary' => 'bg-primary/10 text-primary border-primary/20',
        default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
    };

    $sizeClasses = match ($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        'lg' => 'px-4 py-1.5 text-sm',
        default => 'px-3 py-1 text-xs',
    };
@endphp
<span class="inline-flex items-center gap-1 rounded-full font-bold uppercase tracking-wider border {{ $typeClasses }} {{ $sizeClasses }} {{ $pulse ? 'pulse-badge' : '' }}" {{ $attributes }}>
    {{ $slot }}
</span>
