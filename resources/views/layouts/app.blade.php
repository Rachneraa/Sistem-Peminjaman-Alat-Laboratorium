<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script type="module" src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2563EB",
                        "accent-green": "#10B981",
                        "background-light": "#F8FAFC",
                        "background-dark": "#0F172A",
                        "panel-dark": "#111827",
                        "input-border": "#BFDBFE",
                    },
                    fontFamily: {
                        "display": ["Space Grotesk", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #F8FAFC;
            background-image: radial-gradient(circle at top right, rgba(37, 99, 235, 0.08) 0%, transparent 34%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.06) 0%, transparent 32%),
                radial-gradient(#dbeafe 1px, transparent 1px);
            background-size: auto, auto, 24px 24px;
        }

        .dark body {
            background-color: #0F172A;
            background-image: radial-gradient(circle at top right, rgba(37, 99, 235, 0.14) 0%, transparent 34%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.1) 0%, transparent 32%),
                radial-gradient(#1f2937 1px, transparent 1px);
            background-size: auto, auto, 24px 24px;
        }

        .industrial-border {
            border-left: 4px solid #2563EB;
        }

        .sidebar-link-active {
            background: rgba(37, 99, 235, 0.1);
            border-left: 4px solid #2563EB;
            color: #1d4ed8;
        }

        .dark .sidebar-link-active {
            background: rgba(37, 99, 235, 0.22);
            border-left: 4px solid #60a5fa;
            color: #93c5fd;
        }

        /* Textures */
        .sidebar-texture {
            background-color: #ffffff;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(37, 99, 235, 0.025) 10px, rgba(37, 99, 235, 0.025) 20px);
        }

        .dark .sidebar-texture {
            background-color: #111827;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, 0.02) 10px, rgba(255, 255, 255, 0.02) 20px);
        }

        .navbar-texture {
            background-image: linear-gradient(to right, rgba(37, 99, 235, 0.04) 1px, transparent 1px);
            background-size: 40px 100%;
        }

        .dark .navbar-texture {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 100%;
        }

        /* Custom Scrollbar */
        /* Custom Scrollbar - Global */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E2E8F0;
        }

        ::-webkit-scrollbar-thumb {
            background: #93C5FD;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563EB;
        }

        ::-webkit-scrollbar-corner {
            background: #E2E8F0;
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

        .dark #transition-loader {
            background: rgba(15, 23, 42, 0.86);
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
    </style>
    <script>
        // Check local storage for theme
        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body
    class="bg-background-light dark:bg-background-dark min-h-screen font-display text-gray-800 transition-colors duration-300">
    <div id="transition-loader" aria-hidden="true">
        <dotlottie-player id="transition-lottie" src="{{ asset('loading.lottie') }}" background="transparent" speed="1"
            loop autoplay style="width:84px;height:84px"></dotlottie-player>
        <div class="loader-spinner" aria-hidden="true"></div>
    </div>

    <!-- Skeleton/Preloader -->
    <div id="page-loader" class="fixed inset-0 z-[60] bg-background-light dark:bg-background-dark flex min-h-screen">
        <!-- Sidebar Skeleton -->
        <aside
            class="w-64 bg-white dark:bg-panel-dark border-r border-gray-200 dark:border-white/5 hidden lg:flex flex-col">
            <div class="h-16 border-b border-gray-200 dark:border-white/5 mx-4 flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-200 dark:bg-white/10 rounded animate-pulse"></div>
                <div class="w-24 h-4 bg-gray-200 dark:bg-white/10 rounded animate-pulse"></div>
            </div>
            <div class="p-4 space-y-4">
                @for ($i = 0; $i < 6; $i++)
                    <div class="h-10 bg-gray-200 dark:bg-white/5 rounded animate-pulse w-full"></div>
                @endfor
            </div>
        </aside>

        <!-- Main Content Skeleton -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Skeleton -->
            <div
                class="h-16 border-b border-gray-200 dark:border-white/5 bg-white/95 dark:bg-panel-dark/95 flex items-center justify-between px-6">
                <div class="w-32 h-4 bg-gray-200 dark:bg-white/10 rounded animate-pulse"></div>
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-gray-200 dark:bg-white/10 rounded-full animate-pulse"></div>
                    <div class="w-24 h-4 bg-gray-200 dark:bg-white/10 rounded animate-pulse hidden sm:block"></div>
                </div>
            </div>
            <!-- Content Skeleton -->
            <div class="p-6 space-y-6">
                <!-- Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @for ($i = 0; $i < 3; $i++)
                        <div
                            class="h-32 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl animate-pulse">
                        </div>
                    @endfor
                </div>
                <!-- Table Skeleton -->
                <div
                    class="h-96 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl animate-pulse">
                </div>
            </div>
        </div>
    </div>
    @auth
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <!-- Sidebar -->
            <aside id="sidebar"
                class="sidebar-texture fixed lg:static inset-y-0 left-0 z-[60] w-64 border-r border-gray-200 dark:border-white/5 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    @php
                        $dashboardRoute = auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isPetugas() ? route('petugas.dashboard') : route('peminjam.dashboard'));
                    @endphp
                    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-white/5">
                        <a href="{{ $dashboardRoute }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px] text-primary">science</span>
                            </div>
                            <span class="text-gray-900 dark:text-white font-bold text-sm tracking-wider">TOOL SYSTEM</span>
                        </a>
                        <button id="sidebar-close"
                            class="lg:hidden text-gray-400 hover:text-gray-900 dark:hover:text-white">

                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 overflow-y-auto py-4 px-3">
                        <div class="space-y-1">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">group</span>
                                    <span>Users</span>
                                </a>
                                <a href="{{ route('admin.tools.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.tools.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">construction</span>
                                    <span>Alat</span>
                                </a>
                                <a href="{{ route('admin.denda.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.denda.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">payments</span>
                                    <span>Pengaturan Denda</span>
                                </a>
                                <a href="{{ route('admin.categories.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">category</span>
                                    <span>Kategori</span>
                                </a>
                                <a href="{{ route('admin.borrowings.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.borrowings.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                                    <span>Peminjaman</span>
                                </a>
                                <a href="{{ route('admin.returns.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.returns.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                                    <span>Pengembalian</span>
                                </a>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.reports.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">print</span>
                                    <span>Laporan</span>
                                </a>
                                <a href="{{ route('admin.activity-logs.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('admin.activity-logs.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">history</span>
                                    <span>Log Aktivitas</span>
                                </a>
                            @elseif(auth()->user()->isPetugas())
                                <!-- Same pattern for Petugas links -->
                                <a href="{{ route('petugas.dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('petugas.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('petugas.borrowings.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ (request()->routeIs('petugas.borrowings.index') || (request()->routeIs('petugas.borrowings.show') && request('from') !== 'returns')) ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                    <span>Setujui Peminjaman</span>
                                </a>
                                <a href="{{ route('petugas.returns.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ (request()->routeIs('petugas.returns.index') || (request()->routeIs('petugas.borrowings.show') && request('from') === 'returns')) ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                                    <span>Setujui Pengembalian</span>
                                </a>
                                <a href="{{ route('petugas.borrowings.all') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('petugas.borrowings.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                                    <span>Peminjaman Alat</span>
                                </a>
                                <a href="{{ route('petugas.returns.all') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('petugas.returns.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                                    <span>Pengembalian Alat</span>
                                </a>
                                <a href="{{ route('petugas.reports.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('petugas.reports.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">print</span>
                                    <span>Cetak Laporan</span>
                                </a>
                            @else
                                <!-- Same pattern for Peminjam links -->
                                <a href="{{ route('peminjam.dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('peminjam.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('peminjam.tools.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('peminjam.tools.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                    <span>Daftar Alat</span>
                                </a>
                                <a href="{{ route('peminjam.borrowings.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('peminjam.borrowings.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                                    <span>Peminjaman Saya</span>
                                </a>
                                <a href="{{ route('peminjam.history.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition {{ request()->routeIs('peminjam.history.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white' }}">
                                    <span class="material-symbols-outlined text-[20px]">history</span>
                                    <span>Riwayat</span>
                                </a>
                            @endif
                        </div>
                    </nav>

                    <!-- User Info & Logout -->
                    <div class="border-t border-gray-200 dark:border-white/5 p-4">
                        <div class="flex items-center gap-3 mb-3 px-2">
                            <div
                                class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ auth()->user()->role }}
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-red-600/10 hover:bg-red-600/20 dark:bg-red-600/20 dark:hover:bg-red-600/30 text-red-600 dark:text-red-400 font-medium py-2.5 px-4 rounded transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span class="text-sm">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Sidebar Overlay (Mobile) -->
            <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-[55] lg:hidden hidden"></div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                <!-- Top Bar -->
                <header
                    class="navbar-texture sticky top-0 z-50 bg-white/95 dark:bg-panel-dark/95 backdrop-blur-md border-b border-gray-200 dark:border-white/5 shadow-lg">
                    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                        <div class="flex items-center gap-4">
                            <button id="sidebar-toggle"
                                class="lg:hidden text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                <span class="material-symbols-outlined">menu</span>
                            </button>
                            <div class="hidden lg:block">
                                <h2 class="text-gray-900 dark:text-white text-sm font-bold tracking-[0.2em] uppercase">
                                    @yield('page-title', 'Dashboard')</h2>
                                <div class="h-0.5 w-12 bg-primary mt-1"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button id="theme-toggle"
                                class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                                <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                            </button>
                            @include('components.notifications')
                            <div class="hidden sm:flex items-center gap-2 opacity-40">
                                <div class="w-1.5 h-1.5 bg-accent-green rounded-full animate-pulse"></div>
                                <span
                                    class="text-[10px] text-gray-900 dark:text-white font-bold tracking-wider">ONLINE</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                    <!-- Alerts -->
                    <!-- Toast Container -->
                    <div id="toast-container"
                        class="fixed top-20 right-4 z-50 flex flex-col gap-3 min-w-[300px] max-w-sm pointer-events-none">
                        @if(session('success'))
                            <div
                                class="toast bg-white dark:bg-panel-dark border-l-4 border-green-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0 industrial-border">
                                <span class="material-symbols-outlined text-green-500">check_circle</span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Berhasil</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ session('success') }}</p>
                                </div>
                                <button onclick="this.closest('.toast').remove()"
                                    class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div
                                class="toast bg-white dark:bg-panel-dark border-l-4 border-red-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0">
                                <span class="material-symbols-outlined text-red-500">error</span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Terjadi Kesalahan</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ session('error') }}</p>
                                </div>
                                <button onclick="this.closest('.toast').remove()"
                                    class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div
                                class="toast bg-white dark:bg-panel-dark border-l-4 border-yellow-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0">
                                <span class="material-symbols-outlined text-yellow-500">warning</span>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Perlu Perhatian</h4>
                                    <ul class="mt-1 list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button onclick="this.closest('.toast').remove()"
                                    class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">close</span>
                                </button>
                            </div>
                        @endif
                    </div>

                    @yield('content')
                </main>
            </div>
        </div>
    @else
        @yield('content')
    @endauth

    @include('components.toast')
    @include('components.confirm-modal')

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

        function attachTransitionLoaderHandlers() {
            document.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    if (!shouldTriggerTransitionLoader(link, event)) return;
                    if (transitionInProgress) return;

                    transitionInProgress = true;
                    event.preventDefault();
                    showTransitionLoader();

                    setTimeout(() => {
                        window.location.assign(link.href);
                    }, 140);
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

        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            initTransitionLoaderVisual();
            attachTransitionLoaderHandlers();

            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarClose = document.getElementById('sidebar-close');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', openSidebar);
            }

            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            // Auto-hide toasts (3 seconds)
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                // Add slide-in animation class on load if needed, but default is visible
                // Wait 3 seconds then hide
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300); // Wait for transition
                }, 3000);
            });
        });

        // Helper function for confirm modal
        function showConfirm(message, onConfirm, options = {}) {
            showConfirmModal({
                title: options.title || 'Konfirmasi',
                message: message,
                type: options.type || 'warning',
                okText: options.okText || 'Ya, Lanjutkan',
                cancelText: options.cancelText || 'Batal',
                onConfirm: onConfirm
            });
        }

        // Hide Preloader
        window.addEventListener('load', function () {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.transition = 'opacity 0.5s ease-out';
                loader.style.opacity = '0';
                setTimeout(() => {
                    loader.remove();
                }, 500);
            }
        });

        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function () {
                // Toggle icons via class
                // (Icons are handled by CSS classes dark:hidden and hidden dark:block)

                // Toggle local storage and html class
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            });
        }
    </script>
</body>

</html>