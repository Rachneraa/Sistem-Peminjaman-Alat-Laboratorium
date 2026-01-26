@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full text-sm">
        <div class="text-gray-400">
            Showing 
            <span class="font-bold text-white">{{ $paginator->firstItem() }}</span> 
            to 
            <span class="font-bold text-white">{{ $paginator->lastItem() }}</span> 
            of 
            <span class="font-bold text-white">{{ $paginator->total() }}</span> 
            results
        </div>

        <div class="flex gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center w-8 h-8 rounded bg-gray-800/50 text-gray-600 border border-white/5 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center w-8 h-8 rounded bg-transparent text-gray-500">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-8 h-8 rounded bg-primary text-white border border-primary font-bold shadow-lg shadow-primary/20">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            @else
                <span class="flex items-center justify-center w-8 h-8 rounded bg-gray-800/50 text-gray-600 border border-white/5 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            @endif
        </div>
    </div>
@endif
