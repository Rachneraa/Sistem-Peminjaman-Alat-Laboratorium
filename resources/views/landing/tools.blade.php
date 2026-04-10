<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Alat Lab — LabPinjam</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "landing-bg": "#102F68",
                        "landing-white": "#F8FAFC",
                        "landing-card": "#1E448A",
                        "landing-primary": "#2563EB",
                        "landing-border": "#2852A0"
                    },
                },
            },
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    @php
    @endphp

    <!-- Navigation -->
    <nav class="sticky top-0 w-full z-50 py-4 px-6 md:px-12 flex items-center justify-between text-white/90 border-b border-white/10 shadow-md backdrop-blur-md"
        style="background: rgba(0, 0, 0, 0.28);">
        <div class="flex items-center gap-2">
            <!-- Logo Icon Box -->
            <div class="w-8 h-8 rounded bg-white/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">science</span>
            </div>
            <span class="font-bold text-lg tracking-wide">LabPinjam</span>
        </div>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="{{ route('landing.index') }}"
                class="hover:text-blue-300 transition {{ request()->routeIs('landing.index') ? 'text-blue-200' : 'text-white/80' }}">Beranda</a>
            <a href="{{ route('landing.tools') }}"
                class="hover:text-blue-300 transition {{ request()->routeIs('landing.tools') ? 'text-blue-200' : 'text-white/80' }}">Alat
                Lab</a>
            <a href="{{ route('landing.index') }}#cara-peminjaman"
                class="hover:text-blue-300 transition text-white/80">Cara Peminjaman</a>
        </div>

        <div>
            @if(auth()->check())
                @php
                    $dashboardRoute = auth()->user()->isAdmin()
                        ? route('admin.dashboard')
                        : (auth()->user()->isPetugas() ? route('petugas.dashboard') : route('peminjam.dashboard'));
                @endphp
                <a href="{{ $dashboardRoute }}"
                    class="bg-landing-primary hover:bg-landing-primary/90 text-white px-5 py-2.5 rounded text-sm font-semibold transition border border-white/10 shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" target="_blank" rel="noopener noreferrer"
                    class="bg-landing-primary hover:bg-landing-primary/90 text-white px-5 py-2.5 rounded text-sm font-semibold transition border border-white/10 shadow-sm flex items-center gap-2">
                    Login <span class="material-symbols-outlined text-[18px]">login</span>
                </a>
            @endif
        </div>
    </nav>

    <main>
        <section class="page-hero">
            <div class="page-hero__blob"></div>
            <div class="page-hero__grid"></div>
            <div class="floater page-ring-1"></div>
            <div class="container page-hero__inner">
                <h1 class="page-hero__title">Daftar <em class="gradient-text">Alat Lab</em></h1>
                <p class="page-hero__sub">
                    Jelajahi berbagai alat laboratorium yang tersedia untuk dipinjam.<br>
                    Semua alat dirawat dengan baik dan siap digunakan.
                </p>
            </div>
        </section>

        <section class="filter-bar" aria-label="Filter katalog alat">
            <div class="container filter-bar__inner">
                <form id="toolsFilterForm" class="filter-form" method="GET" action="{{ route('landing.tools') }}">
                    <div class="filter-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="text" id="searchInput" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari alat..." autocomplete="off" />
                    </div>

                    <div class="filter-dropdown-row">
                        <div class="filter-category">
                            <select id="categorySelect" name="category" aria-label="Filter berdasarkan kategori">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (string) ($categoryId ?? '') === (string) $category->id ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filter-sort">
                            <select id="sortSelect" name="sort" aria-label="Urutkan alat">
                                <option value="default" {{ ($sort ?? 'default') === 'default' ? 'selected' : '' }}>Urutkan
                                </option>
                                <option value="name-asc" {{ ($sort ?? '') === 'name-asc' ? 'selected' : '' }}>Nama A–Z
                                </option>
                                <option value="stock-high" {{ ($sort ?? '') === 'stock-high' ? 'selected' : '' }}>Stok
                                    Terbanyak</option>
                                <option value="available" {{ ($sort ?? '') === 'available' ? 'selected' : '' }}>Tersedia
                                    Dulu</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="alat-section">
            <div class="container">
                <div id="alatResults">
                    <div class="alat-grid" id="alatGrid">
                        @forelse($tools as $tool)
                            @php
                                $toolCategory = $tool->category->nama_kategori ?? 'Umum';
                                $isAvailable = $tool->stok > 0;
                            @endphp

                            <article class="alat-card">
                                <div class="alat-card__img">
                                    @if($tool->gambar)
                                        <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}" />
                                    @else
                                        <div class="alat-card__img--empty" aria-hidden="true">
                                            <i class="fa-solid fa-flask"></i>
                                        </div>
                                    @endif

                                    <span class="alat-card__total">Total: {{ $tool->stok_total }}</span>
                                </div>

                                <div class="alat-card__body">
                                    <span class="alat-card__cat">{{ \Illuminate\Support\Str::upper($toolCategory) }}</span>
                                    <h3>{{ $tool->nama_alat }}</h3>

                                    <span class="alat-card__stock {{ $isAvailable ? 'available' : 'dipinjam' }}">
                                        <i class="fa-solid fa-circle"></i>
                                        {{ $isAvailable ? $tool->stok . ' Tersedia' : 'Habis Dipinjam' }}
                                    </span>

                                    @if($tool->stok > 0)
                                        <a href="{{ route('landing.pinjam', $tool->id) }}" class="btn-pinjam">
                                            PINJAM <i class="fa-solid fa-hand"></i>
                                        </a>
                                    @else
                                        <button type="button" class="btn-pinjam btn-pinjam--disabled" disabled
                                            aria-label="Barang habis">
                                            HABIS <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="alat-empty" id="alatEmptyState">
                                Belum ada alat yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>

                    @if($tools->count() > 0)
                        <div class="alat-empty" id="alatEmptyState" hidden>
                            Aduh alatnya belum ada nih. Coba cek lagi kata kunci atau filter yang kamu pakai, ya!
                        </div>
                    @endif

                    @if($tools->hasPages())
                        <div class="alat-pagination" aria-label="Navigasi halaman alat">
                            @if($tools->onFirstPage())
                                <span class="alat-page-btn is-disabled" aria-label="Sebelumnya">
                                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                    <span class="alat-page-text">Sebelumnya</span>
                                </span>
                            @else
                                <a class="alat-page-btn" href="{{ $tools->previousPageUrl() }}" aria-label="Sebelumnya">
                                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                    <span class="alat-page-text">Sebelumnya</span>
                                </a>
                            @endif

                            <div class="alat-page-info" aria-label="Informasi halaman saat ini">
                                <span class="alat-page-info__label">Halaman</span>
                                <span class="alat-page-info__current">{{ $tools->currentPage() }}</span>
                                <span class="alat-page-info__divider">dari</span>
                                <span class="alat-page-info__total">{{ $tools->lastPage() }}</span>
                            </div>

                            @if($tools->hasMorePages())
                                <a class="alat-page-btn" href="{{ $tools->nextPageUrl() }}" aria-label="Berikutnya">
                                    <span class="alat-page-text">Berikutnya</span>
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </a>
                            @else
                                <span class="alat-page-btn is-disabled" aria-label="Berikutnya">
                                    <span class="alat-page-text">Berikutnya</span>
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer__inner">
            <div class="footer__brand">
                <a href="{{ route('landing.index') }}" class="navbar__logo" aria-label="LabPinjam Beranda">
                    <span class="material-symbols-outlined">science</span>
                    LabPinjam
                </a>
                <p>Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan praktikum yang lebih
                    efisien.</p>
            </div>

            <div class="footer__col">
                <h4>NAVIGASI</h4>
                <a href="{{ route('landing.index') }}">Beranda</a>
                <a href="{{ route('landing.tools') }}">Daftar Alat</a>
                <a href="{{ route('landing.index') }}#cara-peminjaman">Cara Peminjaman</a>
            </div>

            <div class="footer__col">
                <h4>INFORMASI</h4>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">FAQ</a>
                <a href="#">Kebijakan Privasi</a>
            </div>

            <div class="footer__col">
                <h4>KONTAK</h4>
                <span><i class="fa-solid fa-envelope"></i> labpinjam@university.ac.id</span>
                <span><i class="fa-solid fa-phone"></i> +62 22 1234 5678</span>
                <span><i class="fa-solid fa-location-dot"></i> Gedung Lab Lt. 2, Kampus Utama</span>
            </div>
        </div>

        <div class="footer__bottom">
            &copy; {{ date('Y') }} LabPinjam. Semua hak dilindungi.
        </div>
    </footer>
</body>

</html>