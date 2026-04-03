{{-- Button Component --}}
@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success, ghost
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'loading' => false,
    'disabled' => false,
])

@php
    $variantClasses = match($variant) {
        'secondary' => 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-blue-200 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20',
        'success' => 'bg-green-600 text-white hover:bg-green-700 shadow-lg shadow-green-600/20',
        'ghost' => 'bg-transparent text-gray-700 dark:text-gray-300 hover:bg-primary/5 dark:hover:bg-white/10',
        default => 'bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/20', // primary
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-5 py-2.5 text-sm', // md
    };
    
    $disabledClasses = ($disabled || $loading) ? 'opacity-50 cursor-not-allowed' : '';
    $commonClasses = "inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900 {$variantClasses} {$sizeClasses} {$disabledClasses}";
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $commonClasses]) }}>
        @if($icon && $iconPosition === 'left')
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}"
        {{ $attributes->merge(['class' => $commonClasses]) }}
        {{ ($disabled || $loading) ? 'disabled' : '' }}
    >
        @if($loading)
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @elseif($icon && $iconPosition === 'left')
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right' && !$loading)
            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
        @endif
    </button>
@endif
