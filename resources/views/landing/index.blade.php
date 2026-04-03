@extends('layouts.landing')

@section('title', 'Beranda - LabPinjam')

@section('content')

    <!-- Hero Section -->
    <section
        class="hero-pattern pt-20 pb-28 px-6 md:px-12 w-full lg:min-h-[85vh] flex items-center relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="absolute w-[600px] h-[600px] bg-blue-500/10 rounded-full blur-3xl -bottom-48 -right-20"></div>
        <div class="absolute w-[400px] h-[400px] bg-white/5 rounded-full blur-2xl top-10 left-10"></div>

        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 relative z-10">
            <!-- Left Content -->
            <div class="flex flex-col justify-center space-y-6">
                <div
                    class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 w-max text-xs font-semibold tracking-wider text-blue-200">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    SISTEM AKTIF & ONLINE
                </div>

                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight">
                    Pinjam Alat Lab dengan <span class="italic text-blue-300">Mudah</span> & Cepat
                </h1>

                <p class="text-white/70 text-lg max-w-lg leading-relaxed">
                    Platform peminjaman alat laboratorium terintegrasi untuk mahasiswa dan peneliti. Cek ketersediaan,
                    ajukan pinjaman, dan pantau status — semua dalam satu tempat.
                </p>

                <div class="pt-4 flex flex-wrap items-center gap-4">
                    <a href="{{ route('landing.tools') }}"
                        class="bg-landing-primary hover:bg-blue-500 text-white shadow-lg px-6 py-3.5 rounded-lg font-semibold flex items-center gap-2 transition transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                        Lihat Alat Tersedia
                    </a>
                    <a href="#cara-peminjaman"
                        class="bg-white/10 hover:bg-white/15 border border-white/20 text-white px-6 py-3.5 rounded-lg font-semibold flex items-center gap-2 transition">
                        Cara Peminjaman
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Right Content / Stats -->
            <div class="relative w-full flex flex-col justify-center">
                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 md:gap-6 mb-6">
                    <div class="bg-landing-card/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-white/60 text-xs font-bold tracking-widest uppercase mb-1">TOTAL ALAT</h3>
                        <div class="text-4xl font-bold text-white mb-2">{{ $totalTools }}</div>
                        <div class="text-white/40 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">inventory_2</span> Tercatat di db
                        </div>
                    </div>

                    <div
                        class="bg-landing-card/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-5">
                            <span class="material-symbols-outlined text-9xl">check_circle</span>
                        </div>
                        <h3 class="text-white/60 text-xs font-bold tracking-widest uppercase mb-1">TERSEDIA</h3>
                        <div class="text-4xl font-bold text-white mb-2">{{ $totalTools }}</div>
                        <div class="text-white/40 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">event_available</span> Siap dipinjam
                        </div>
                    </div>
                </div>

                <!-- Latest Tools List -->
                <div class="bg-landing-card/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-white/60 text-xs font-bold tracking-widest uppercase mb-4">ALAT TERBARU</h3>
                    <div class="space-y-4">
                        @forelse($latestTools as $tool)
                            <div
                                class="flex items-center justify-between group py-2 hover:bg-white/5 rounded-lg px-2 -mx-2 transition cursor-default">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded bg-white/10 border border-white/10 overflow-hidden flex-shrink-0">
                                        @if($tool->gambar)
                                            <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-white/50 text-[20px]">science</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white leading-tight mb-1">{{ $tool->nama_alat }}</h4>
                                        <p class="text-[11px] text-white/50">{{ $tool->category->nama_kategori ?? 'Umum' }}</p>
                                    </div>
                                </div>
                                <!-- Status Badge -->
                                <div
                                    class="text-xs px-2.5 py-1 rounded-full border {{ $tool->status === 'tersedia' ? 'border-green-400/30 text-green-400 bg-green-400/10' : 'border-red-400/30 text-red-400 bg-red-400/10' }} flex-shrink-0 font-medium">
                                    {{ ucfirst($tool->status) }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-white/50 text-sm">
                                Belum ada alat terdaftar.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Peminjaman Section -->
    <section id="cara-peminjaman" class="py-24 px-6 md:px-12 bg-white relative">
        <div class="max-w-7xl mx-auto">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h4 class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-3">CARA PEMINJAMAN</h4>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Tiga langkah mudah,<br />alat siap di tangan
                </h2>
                <p class="text-gray-500">Proses peminjaman dirancang sesederhana mungkin agar tidak menghabiskan waktu riset
                    kamu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connecting dashed line -->
                <div
                    class="hidden md:block absolute top-[50%] left-0 w-full h-[2px] bg-blue-100/50 -translate-y-1/2 z-0 border-t-2 border-dashed border-blue-200">
                </div>

                <!-- Step 1 -->
                <div
                    class="bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] transition duration-300 rounded-2xl p-8 relative z-10 flex flex-col sm:flex-row md:flex-col gap-6">
                    <div
                        class="w-14 h-14 bg-landing-primary text-white text-2xl font-black flex items-center justify-center rounded-xl shadow-lg shadow-blue-500/30 flex-shrink-0">
                        1</div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">PILIH ALAT</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Cari dan pilih alat lab yang kamu butuhkan. Cek
                            status ketersediaan secara real-time langsung dari halaman ini.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div
                    class="bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] transition duration-300 rounded-2xl p-8 relative z-10 flex flex-col sm:flex-row md:flex-col gap-6">
                    <div
                        class="w-14 h-14 bg-landing-primary text-white text-2xl font-black flex items-center justify-center rounded-xl shadow-lg shadow-blue-500/30 flex-shrink-0">
                        2</div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">AJUKAN</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Klik tombol pinjam, tentukan tanggal pinjam &
                            kembali, lalu kirim pengajuan. Tunggu petugas menyetujui jadwal.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div
                    class="bg-white border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.1)] transition duration-300 rounded-2xl p-8 relative z-10 flex flex-col sm:flex-row md:flex-col gap-6">
                    <div
                        class="w-14 h-14 bg-landing-primary text-white text-2xl font-black flex items-center justify-center rounded-xl shadow-lg shadow-blue-500/30 flex-shrink-0">
                        3</div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3 tracking-tight">AMBIL</h3>
                        <p class="text-gray-600 leading-relaxed font-medium">Setelah disetujui, datang ke lab pada tanggal
                            sesuai pesanan dan tunjukkan bukti persetujuan ke petugas. Selesai!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tools Section -->
    <section class="py-24 px-6 md:px-12 bg-gray-50/50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12">
                <div>
                    <h4 class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-2">DAFTAR ALAT</h4>
                    <h2 class="text-3xl font-bold text-gray-900">Alat Lab Unggulan</h2>
                </div>
                <a href="{{ route('landing.tools') }}"
                    class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold px-6 py-2.5 rounded-lg text-sm transition flex items-center gap-2 max-w-max">
                    Lihat Semua Alat <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>

            <div id="featured-tools-scroll"
                class="flex overflow-x-auto gap-6 pb-6 snap-x snap-mandatory hide-scroll cursor-grab select-none"
                style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    .hide-scroll::-webkit-scrollbar {
                        display: none;
                    }

                    .cursor-grabbing {
                        cursor: grabbing !important;
                    }
                </style>
                @forelse($featuredTools as $tool)
                    <div
                        class="bg-white rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group relative w-[280px] sm:w-[300px] flex-none snap-center">
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
                    <div class="col-span-full py-12 text-center text-gray-500 bg-white border border-gray-100 rounded-2xl">
                        <span class="material-symbols-outlined text-4xl mb-3 opacity-50">inventory_2</span>
                        <p class="font-medium">Belum ada alat yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slider = document.getElementById('featured-tools-scroll');
            if (!slider) return;

            let isDown = false;
            let startX;
            let scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('cursor-grabbing');
                slider.classList.remove('snap-x', 'snap-mandatory'); // turn off snap during drag for smooth drag
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                if (!isDown) return;
                isDown = false;
                slider.classList.remove('cursor-grabbing');
                slider.classList.add('snap-x', 'snap-mandatory');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
                slider.classList.add('snap-x', 'snap-mandatory');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - slider.offsetLeft;
                const walk = (x - startX) * 1.5; // scroll speed multiplier
                slider.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
@endsection