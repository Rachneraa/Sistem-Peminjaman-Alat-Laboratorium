@extends('layouts.landing')

@section('title', 'Alat Lab Tersedia - LabPinjam')

@section('content')
    <!-- Header Area -->
    <section class="bg-[#0B1426] py-12 px-6 md:px-12 w-full flex items-center relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl -top-20 -right-20"></div>

        <div class="max-w-7xl mx-auto w-full relative z-10 text-center">
            <h1 class="text-3xl lg:text-4xl font-bold leading-tight text-white mb-4">
                Daftar <span class="italic text-blue-300">Alat Lab</span>
            </h1>
            <p class="text-white/70 max-w-xl mx-auto">
                Jelajahi berbagai alat laboratorium yang tersedia untuk dipinjam. Semua alat dirawat dengan baik dan siap
                digunakan.
            </p>
        </div>
    </section>

    <!-- Tools List Section -->
    <section class="py-16 px-6 md:px-12 bg-white relative">
        <div class="max-w-7xl mx-auto">
            <!-- Grid List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                @forelse($tools as $tool)
                    <div
                        class="bg-white rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group relative">
                        <!-- Stock badge -->
                        <div
                            class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm border border-gray-200 px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                            Total: {{ $tool->stok_total }}
                        </div>

                        <!-- Image -->
                        <div class="aspect-[4/3] bg-gray-100 transition relative overflow-hidden">
                            @if($tool->gambar)
                                <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                                    class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-[1.04]">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[72px] text-gray-200">science</span>
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-2">
                                {{ $tool->category->nama_kategori ?? 'Umum' }}
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1 leading-tight text-truncate-2">
                                {{ $tool->nama_alat }}
                            </h3>

                            <!-- Status indicator -->
                            <div class="flex items-center gap-2 mt-auto pt-4 mb-4">
                                @if($tool->stok > 0)
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-sm font-medium text-gray-600">{{ $tool->stok }} Tersedia</span>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    <span class="text-sm font-medium text-gray-600">Habis Dipinjam</span>
                                @endif
                            </div>

                            <!-- Action -->
                            <a href="{{ route('landing.pinjam', $tool->id) }}"
                                class="w-full bg-landing-primary hover:bg-landing-primary/90 text-white font-bold py-3 rounded-xl text-center text-sm transition uppercase tracking-wide flex items-center justify-center gap-2">
                                Pinjam <span class="material-symbols-outlined text-[16px]">touch_app</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center text-gray-500 bg-gray-50/50 border border-gray-100 rounded-3xl">
                        <span class="material-symbols-outlined text-6xl mb-4 opacity-30 text-blue-500">search_off</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Alat</h3>
                        <p class="font-medium text-gray-500">Coba cek kembali nanti atau hubungi petugas lab.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tools->hasPages())
                <div class="flex justify-center mt-12">
                    {{ $tools->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </section>
@endsection