{{-- Loading Overlay Component --}}
@props([
    'show' => false,
    'message' => 'Loading...'
])

<div 
    x-data="{ show: @js($show) }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    style="display: none;"
    {{ $attributes }}
>
    <div class="bg-white dark:bg-panel-dark rounded-xl p-8 shadow-2xl scale-in">
        <div class="flex flex-col items-center gap-4">
            {{-- Spinner --}}
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 dark:border-gray-700 rounded-full"></div>
                <div class="absolute top-0 left-0 w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
            </div>
            
            {{-- Message --}}
            <p class="text-gray-900 dark:text-white font-medium">{{ $message }}</p>
        </div>
    </div>
</div>
