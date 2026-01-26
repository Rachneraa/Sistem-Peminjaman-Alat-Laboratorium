@extends('layouts.app')

@section('title', 'Daftar Alat')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Daftar Alat</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="{{ route('peminjam.tools.index') }}" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari alat..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 placeholder-gray-500 transition-all">
        </div>
        <div class="flex-1 min-w-[200px]">
            <select name="kategori_id" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('kategori_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            <a href="{{ route('peminjam.tools.index') }}" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Grid Alat -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($tools as $tool)
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-primary/50 transition-all duration-300 aspect-square flex flex-col justify-between group relative overflow-hidden shadow-sm dark:shadow-none" style="aspect-ratio: 1 / 1;">
            @if($tool->gambar)
                <div class="w-full h-1/2 mb-3 overflow-hidden rounded-lg relative bg-gray-100 dark:bg-gray-800">
                    <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                </div>
            @else
                <div class="w-full h-1/2 mb-3 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
            
            <div class="flex-1 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-white truncate" title="{{ $tool->nama_alat }}">{{ $tool->nama_alat }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 truncate">{{ $tool->category->nama_kategori }}</p>
                    
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-gray-100 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                            <span class="text-[10px] text-gray-600 dark:text-gray-300">Stok</span>
                            <span class="text-xs font-bold text-gray-900 dark:text-white ml-3">{{ $tool->stok_tersedia }}</span>
                        </div>
                        
                        <span class="px-2 py-1 rounded-full text-[10px]
                            @if($tool->status == 'tersedia') bg-green-500/20 text-green-400
                            @elseif($tool->status == 'rusak') bg-red-500/20 text-red-400
                            @elseif($tool->status == 'dipinjam') bg-blue-500/20 text-blue-400
                            @else bg-yellow-500/20 text-yellow-400
                            @endif">
                            {{ ucfirst($tool->status ?? 'tersedia') }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2 mt-auto">
                    @if($tool->status == 'tersedia' && $tool->stok_tersedia > 0)
                        <a href="{{ route('peminjam.borrowings.create', ['tool_id' => $tool->id]) }}" class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all inline-flex items-center justify-center gap-1.5 text-xs shadow-lg shadow-green-600/20">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Pinjam
                        </a>
                    @endif
                    <a href="{{ route('peminjam.tools.show', $tool) }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg font-medium transition-all inline-flex items-center justify-center gap-1.5 text-xs {{ $tool->status == 'tersedia' && $tool->stok_tersedia > 0 ? '' : 'flex-1' }}">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada alat ditemukan</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tools->links() }}
</div>
@endsection

