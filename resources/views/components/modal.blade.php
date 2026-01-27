{{-- Modal Component --}}
@props([
    'name',
    'title' => null,
    'maxWidth' => 'md', // sm, md, lg, xl, 2xl
    'closeable' => true,
])

@php
    $maxWidthClasses = match($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm"
        @click="show = false"
    ></div>

    {{-- Modal --}}
    <div class="flex items-center justify-center min-h-screen p-4">
        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full {{ $maxWidthClasses }} shadow-2xl rounded-xl industrial-border"
            @click.stop
        >
            {{-- Header --}}
            @if($title || $closeable)
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
                    @if($title)
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">{{ $title }}</h3>
                    @endif
                    
                    @if($closeable)
                        <button @click="show = false" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    @endif
                </div>
            @endif

            {{-- Body --}}
            <div class="p-6">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @isset($footer)
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700/50">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>

{{-- Helper Script --}}
@once
    @push('scripts')
    <script>
        function openModal(name) {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: name }));
        }
        
        function closeModal(name) {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: name }));
        }
    </script>
    @endpush
@endonce
