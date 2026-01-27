{{-- Breadcrumb Component --}}
@props([
    'items' => []
])

<nav class="flex mb-6 fade-in" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        {{-- Home --}}
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px] mr-2">home</span>
                Dashboard
            </a>
        </li>

        {{-- Dynamic Items --}}
        @foreach($items as $index => $item)
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[18px]">chevron_right</span>
                    @if($loop->last)
                        <span class="ml-1 text-sm font-medium text-gray-900 dark:text-white md:ml-2">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}" class="ml-1 text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-primary dark:hover:text-primary md:ml-2 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
