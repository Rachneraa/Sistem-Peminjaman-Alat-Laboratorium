{{-- Filter Panel Component --}}
@props([
    'action',
    'method' => 'GET',
    'activeFiltersCount' => 0,
])

<div x-data="{ expanded: false }" class="mb-6">
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border overflow-hidden">
        {{-- Header/Trigger --}}
        <div class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5 transition-colors" @click="expanded = !expanded">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">filter_list</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Filter & Pencarian</h3>
                    @if($activeFiltersCount > 0)
                        <p class="text-[10px] font-bold text-accent-green uppercase tracking-widest mt-0.5">
                            {{ $activeFiltersCount }} Filter Aktif
                        </p>
                    @else
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-0.5">Semua Data</p>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                @if($activeFiltersCount > 0)
                    <a href="{{ $action }}" class="text-[10px] font-bold text-red-500 hover:text-red-600 uppercase tracking-widest bg-red-500/10 px-2 py-1 rounded" @click.stop>
                        Reset Filter
                    </a>
                @endif
                <span class="material-symbols-outlined text-gray-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''">expand_more</span>
            </div>
        </div>

        {{-- Panel Body --}}
        <div 
            x-show="expanded" 
            x-collapse
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="border-t border-gray-200 dark:border-white/5"
            style="display: none;"
        >
            <form action="{{ $action }}" method="{{ $method }}" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{ $slot }}
                </div>
                
                <div class="mt-8 flex justify-end gap-3 border-t border-gray-200 dark:border-white/5 pt-6">
                    <button type="button" @click="expanded = false" class="px-6 py-2.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-900 dark:hover:text-white transition-colors">
                        Tutup
                    </button>
                    <x-button type="submit" variant="primary" size="sm" icon="search">
                        Terapkan Filter
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
