@extends('layouts.app')

@section('title', 'Detail Alat')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detail Alat</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image Section -->
        <div class="lg:col-span-1">
            <div
                class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
                @if($tool->gambar)
                    <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                        class="w-full h-64 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                @else
                    <div
                        class="w-full h-64 bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px]">image</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Details Section -->
        <div class="lg:col-span-2">
            <div
                class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $tool->nama_alat }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">
                            Kategori</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $tool->category->nama_kategori }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Stok
                            Tersedia</p>
                        <p class="text-3xl font-bold text-primary">{{ $tool->stok_tersedia }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Status
                        </p>
                        <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border inline-block
                            @if($tool->status == 'tersedia') bg-green-500/10 text-green-500 border-green-500/20
                            @elseif($tool->status == 'rusak') bg-red-500/10 text-red-500 border-red-500/20
                            @elseif($tool->status == 'dipinjam') bg-blue-500/10 text-blue-500 border-blue-500/20
                            @else bg-yellow-500/10 text-yellow-500 border-yellow-500/20
                            @endif">
                            {{ ucfirst($tool->status ?? 'tersedia') }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <a href="{{ route('peminjam.tools.index') }}"
                        class="px-5 py-2.5 bg-transparent border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                        Kembali
                    </a>
                    @if($tool->stok_tersedia > 0 && $tool->status == 'tersedia')
                        <a href="{{ route('peminjam.borrowings.create', ['tool_id' => $tool->id]) }}"
                            class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-[20px]">add_shopping_cart</span>
                            Pinjam Alat Ini
                        </a>
                    @else
                        <button disabled
                            class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-500 rounded-lg cursor-not-allowed text-sm font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">block</span>
                            Tidak Tersedia
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection