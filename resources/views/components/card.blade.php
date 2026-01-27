{{-- Card Component --}}
@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'padding' => true,
    'hover' => false,
])

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border {{ $hover ? 'hover-glow hover-lift cursor-pointer' : '' }} transition-smooth" {{ $attributes }}>
    @if($title || $icon)
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700/50 flex items-center {{ $subtitle ? 'gap-3' : 'justify-between' }}">
            @if($icon)
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-[24px]">{{ $icon }}</span>
                    </div>
                </div>
            @endif
            
            <div class="flex-1">
                @if($title)
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            
            @isset($actions)
                <div class="flex-shrink-0">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif
    
    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
