{{-- Error Alert Component --}}
@props([
    'type' => 'error', // error, warning, success, info
    'title' => null,
    'message',
    'dismissible' => true,
])

@php
    $config = match($type) {
        'error' => [
            'bg' => 'bg-red-50 dark:bg-red-900/10',
            'border' => 'border-red-500',
            'icon' => 'error',
            'iconColor' => 'text-red-500',
            'titleColor' => 'text-red-800 dark:text-red-400',
            'textColor' => 'text-red-700 dark:text-red-300',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/10',
            'border' => 'border-yellow-500',
            'icon' => 'warning',
            'iconColor' => 'text-yellow-500',
            'titleColor' => 'text-yellow-800 dark:text-yellow-400',
            'textColor' => 'text-yellow-700 dark:text-yellow-300',
        ],
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/10',
            'border' => 'border-green-500',
            'icon' => 'check_circle',
            'iconColor' => 'text-green-500',
            'titleColor' => 'text-green-800 dark:text-green-400',
            'textColor' => 'text-green-700 dark:text-green-300',
        ],
        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/10',
            'border' => 'border-blue-500',
            'icon' => 'info',
            'iconColor' => 'text-blue-500',
            'titleColor' => 'text-blue-800 dark:text-blue-400',
            'textColor' => 'text-blue-700 dark:text-blue-300',
        ],
    };
@endphp

<div class="rounded-lg border-l-4 {{ $config['border'] }} {{ $config['bg'] }} p-4 fade-in {{ $type === 'error' ? 'shake' : '' }}" role="alert" {{ $attributes }}>
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined {{ $config['iconColor'] }} flex-shrink-0">{{ $config['icon'] }}</span>
        
        <div class="flex-1">
            @if($title)
                <h3 class="font-semibold {{ $config['titleColor'] }} mb-1">{{ $title }}</h3>
            @endif
            <p class="{{ $config['textColor'] }} text-sm">{{ $message }}</p>
            
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button type="button" onclick="this.closest('[role=alert]').remove()" class="{{ $config['iconColor'] }} hover:opacity-70 transition flex-shrink-0">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        @endif
    </div>
</div>
