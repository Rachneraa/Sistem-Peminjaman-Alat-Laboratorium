<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script type="module" src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "landing-bg": "#102F68", // Darker blue background based on image
                        "landing-white": "#F8FAFC", // Off white background for bottom sections
                        "landing-card": "#1E448A", // Lighter blue for cards
                        "landing-primary": "#2563EB", // Primary blue button
                        "landing-border": "#2852A0"
                    },
                    fontFamily: {
                        "display": ["Poppins", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        section[id] {
            scroll-margin-top: 96px;
        }

        .hero-pattern {
            background-color: #102F68;
            /* Simple elegant gradient over primary solid blue */
            background-image: radial-gradient(circle at top right, rgba(37, 99, 235, 0.3) 0%, transparent 40%),
                radial-gradient(circle at bottom right, rgba(37, 99, 235, 0.2) 0%, transparent 40%),
                radial-gradient(circle at center 20%, rgba(255, 255, 255, 0.05) 0%, transparent 20%);
            color: white;
        }

        [data-fade-up] {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
            transition-delay: var(--fade-delay, 0ms);
            will-change: opacity, transform;
        }

        [data-fade-up].is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        #transition-loader {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(248, 250, 252, 0.84);
            backdrop-filter: blur(3px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        #transition-loader.is-active {
            opacity: 1;
            pointer-events: all;
        }

        #transition-loader dotlottie-player {
            width: 84px;
            height: 84px;
            filter: drop-shadow(0 8px 20px rgba(37, 99, 235, 0.2));
        }

        #transition-loader .loader-spinner {
            position: absolute;
            width: 56px;
            height: 56px;
            border: 4px solid rgba(37, 99, 235, 0.16);
            border-top-color: #2563EB;
            border-radius: 50%;
            animation: loader-spin 0.9s linear infinite;
            opacity: 0;
        }

        #transition-loader.is-fallback .loader-spinner {
            opacity: 1;
        }

        #transition-loader.is-fallback dotlottie-player {
            opacity: 0;
        }

        @keyframes loader-spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            [data-fade-up] {
                opacity: 1;
                transform: none;
                transition: none;
            }
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="font-display bg-landing-white antialiased text-gray-800">
    <div id="transition-loader" aria-hidden="true">
        <dotlottie-player id="transition-lottie" src="{{ asset('loading.lottie') }}" background="transparent" speed="1"
            loop autoplay style="width:84px;height:84px"></dotlottie-player>
        <div class="loader-spinner" aria-hidden="true"></div>
    </div>

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
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-50 bg-[#0B1426] text-white/70 py-12 px-6 md:px-12 mt-0 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-white">
                    <div class="w-8 h-8 rounded bg-landing-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">science</span>
                    </div>
                    <span class="font-bold text-lg tracking-wide">LabPinjam</span>
                </div>
                <p class="text-sm">Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan
                    praktikum yang lebih efisien.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm tracking-wider">NAVIGASI</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing.index') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('landing.tools') }}" class="hover:text-white transition">Daftar Alat</a></li>
                    <li><a href="{{ route('landing.index') }}#cara-peminjaman" class="hover:text-white transition">Cara
                            Peminjaman</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm tracking-wider">INFORMASI</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm tracking-wider">KONTAK</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">mail</span>
                        labpinjam@university.ac.id</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">call</span>
                        +62 22 1234 5678</li>
                    <li class="flex items-start gap-2"><span
                            class="material-symbols-outlined text-[16px] mt-0.5">location_on</span> Gedung Lab Lt. 2,
                        Kampus Utama</li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        let transitionInProgress = false;

        function showTransitionLoader() {
            const loader = document.getElementById('transition-loader');
            if (!loader) return;
            loader.classList.add('is-active');
        }

        function shouldTriggerTransitionLoader(link, event) {
            if (!link || !link.href) return false;
            if (event && (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey)) return false;

            const rawHref = link.getAttribute('href') || '';
            if (!rawHref || rawHref.startsWith('#')) return false;
            if (rawHref.startsWith('javascript:') || rawHref.startsWith('mailto:') || rawHref.startsWith('tel:')) return false;
            if (link.hasAttribute('download')) return false;
            if ((link.getAttribute('target') || '').toLowerCase() === '_blank') return false;

            const targetUrl = new URL(link.href, window.location.origin);
            if (targetUrl.origin !== window.location.origin) return false;

            const isSamePageAnchor = targetUrl.pathname === window.location.pathname &&
                targetUrl.search === window.location.search &&
                targetUrl.hash;

            return !isSamePageAnchor;
        }

        function initTransitionLoaderVisual() {
            const lottiePlayer = document.getElementById('transition-lottie');
            const loader = document.getElementById('transition-loader');
            if (!lottiePlayer || !loader) return;

            const useFallback = () => {
                loader.classList.add('is-fallback');
            };

            lottiePlayer.addEventListener('ready', () => {
                loader.classList.remove('is-fallback');
            });

            lottiePlayer.addEventListener('error', useFallback);

            setTimeout(() => {
                if (!customElements.get('dotlottie-player')) {
                    useFallback();
                }
            }, 1800);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initTransitionLoaderVisual();
            const fadeItems = document.querySelectorAll('[data-fade-up]');
            if (fadeItems.length) {
                const observer = new IntersectionObserver((entries, observerInstance) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observerInstance.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.16,
                    rootMargin: '0px 0px -8% 0px'
                });

                fadeItems.forEach((item) => observer.observe(item));
            }

            const anchorLinks = document.querySelectorAll('a[href^="#"], a[href*="#"]');
            anchorLinks.forEach((link) => {
                link.addEventListener('click', (event) => {
                    if (shouldTriggerTransitionLoader(link, event)) {
                        if (transitionInProgress) return;

                        transitionInProgress = true;
                        event.preventDefault();
                        showTransitionLoader();

                        setTimeout(() => {
                            window.location.assign(link.href);
                        }, 140);
                        return;
                    }

                    const href = link.getAttribute('href') || '';
                    if (!href.includes('#')) return;

                    const url = new URL(link.href, window.location.origin);
                    const isSamePage = url.pathname === window.location.pathname && url.hostname === window.location.hostname;
                    if (!isSamePage) return;

                    const targetId = url.hash.replace('#', '');
                    if (!targetId) return;

                    const targetElement = document.getElementById(targetId);
                    if (!targetElement) return;

                    event.preventDefault();
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    history.replaceState(null, '', `#${targetId}`);
                });
            });

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) return;
                if ((form.getAttribute('target') || '').toLowerCase() === '_blank') return;
                if (transitionInProgress) return;

                transitionInProgress = true;
                event.preventDefault();
                showTransitionLoader();

                setTimeout(() => {
                    form.submit();
                }, 140);
            }, true);

            // Check for sweetalert session
            @if(session('sweetalert_error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Akses Ditolak',
                    text: '{{ session('sweetalert_error') }}',
                    confirmButtonColor: '#2563EB',
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#2563EB',
                });
            @endif
        });
    </script>
</body>

</html>