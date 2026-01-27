<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sistem Peminjaman Alat'); ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d5868",
                        "accent-green": "#8EFF00",
                        "background-light": "#f7f7f7",
                        "background-dark": "#141414",
                        "panel-dark": "#212121",
                        "input-border": "#355c64",
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
            background-color: #f7f7f7;
            background-image: radial-gradient(#d1d5db 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .dark body {
            background-color: #141414;
            background-image: radial-gradient(#333 1px, transparent 1px);
        }
        .industrial-border {
            border-left: 4px solid #0d5868;
        }
        .sidebar-link-active {
            background: rgba(13, 88, 104, 0.1);
            border-left: 4px solid #0d5868;
            color: #0d5868;
        }
        .dark .sidebar-link-active {
            background: rgba(13, 88, 104, 0.3);
            border-left: 4px solid #8EFF00;
            color: #8EFF00;
        }
        /* Textures */
        .sidebar-texture {
            background-color: #ffffff;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(13, 88, 104, 0.02) 10px, rgba(13, 88, 104, 0.02) 20px);
        }
        .dark .sidebar-texture {
            background-color: #212121;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255, 255, 255, 0.01) 10px, rgba(255, 255, 255, 0.01) 20px);
        }
        .navbar-texture {
            background-image: linear-gradient(to right, rgba(13, 88, 104, 0.03) 1px, transparent 1px);
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
            background: #141414; 
        }
        ::-webkit-scrollbar-thumb {
            background: #355c64; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #0d5868; 
        }
        ::-webkit-scrollbar-corner {
            background: #141414;
        }
    </style>
    <script>
        // Check local storage for theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark min-h-screen font-display transition-colors duration-300">
    <!-- Skeleton/Preloader -->
    <div id="page-loader" class="fixed inset-0 z-[60] bg-background-light dark:bg-background-dark flex min-h-screen">
        <!-- Sidebar Skeleton -->
        <aside class="w-64 bg-white dark:bg-panel-dark border-r border-gray-200 dark:border-white/5 hidden lg:flex flex-col">
            <div class="h-16 border-b border-gray-200 dark:border-white/5 mx-4 flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-200 dark:bg-white/10 rounded animate-pulse"></div>
                <div class="w-24 h-4 bg-gray-200 dark:bg-white/10 rounded animate-pulse"></div>
            </div>
            <div class="p-4 space-y-4">
                <?php for($i = 0; $i < 6; $i++): ?>
                <div class="h-10 bg-gray-200 dark:bg-white/5 rounded animate-pulse w-full"></div>
                <?php endfor; ?>
            </div>
        </aside>
        
        <!-- Main Content Skeleton -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Skeleton -->
            <div class="h-16 border-b border-gray-200 dark:border-white/5 bg-white/95 dark:bg-panel-dark/95 flex items-center justify-between px-6">
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
                    <?php for($i = 0; $i < 3; $i++): ?>
                    <div class="h-32 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl animate-pulse"></div>
                    <?php endfor; ?>
                </div>
                <!-- Table Skeleton -->
                <div class="h-96 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl animate-pulse"></div>
            </div>
        </div>
    </div>
    <?php if(auth()->guard()->check()): ?>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar-texture fixed lg:static inset-y-0 left-0 z-[60] w-64 border-r border-gray-200 dark:border-white/5 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-white/5">
                    <a href="/" class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-primary">
                            <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-gray-900 dark:text-white font-bold text-sm tracking-wider">TOOL SYSTEM</span>
                    </a>
                    <button id="sidebar-close" class="lg:hidden text-gray-400 hover:text-gray-900 dark:hover:text-white">

                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4 px-3">
                    <div class="space-y-1">
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.users.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">group</span>
                                <span>Users</span>
                            </a>
                            <a href="<?php echo e(route('admin.tools.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.tools.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">construction</span>
                                <span>Alat</span>
                            </a>
                            <a href="<?php echo e(route('admin.categories.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.categories.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">category</span>
                                <span>Kategori</span>
                            </a>
                            <a href="<?php echo e(route('admin.borrowings.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.borrowings.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                                <span>Peminjaman</span>
                            </a>
                            <a href="<?php echo e(route('admin.returns.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.returns.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                                <span>Pengembalian</span>
                            </a>
                            <a href="<?php echo e(route('admin.reports.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.reports.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">print</span>
                                <span>Laporan</span>
                            </a>
                            <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('admin.activity-logs.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">history</span>
                                <span>Log Aktivitas</span>
                            </a>
                        <?php elseif(auth()->user()->isPetugas()): ?>
                            <!-- Same pattern for Petugas links -->
                            <a href="<?php echo e(route('petugas.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="<?php echo e(route('petugas.borrowings.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.borrowings.*') && !request()->routeIs('petugas.borrowings.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                <span>Setujui Peminjaman</span>
                            </a>
                            <a href="<?php echo e(route('petugas.returns.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.returns.*') && !request()->routeIs('petugas.returns.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                                <span>Setujui Pengembalian</span>
                            </a>
                            <a href="<?php echo e(route('petugas.borrowings.all')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.borrowings.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">swap_horiz</span>
                                <span>Peminjaman Alat</span>
                            </a>
                            <a href="<?php echo e(route('petugas.returns.all')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.returns.all') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">assignment_return</span>
                                <span>Pengembalian Alat</span>
                            </a>
                            <a href="<?php echo e(route('petugas.reports.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('petugas.reports.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">print</span>
                                <span>Cetak Laporan</span>
                            </a>
                        <?php else: ?>
                            <!-- Same pattern for Peminjam links -->
                            <a href="<?php echo e(route('peminjam.dashboard')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('peminjam.dashboard') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                                <span>Dashboard</span>
                            </a>
                            <a href="<?php echo e(route('peminjam.tools.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('peminjam.tools.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                <span>Daftar Alat</span>
                            </a>
                            <a href="<?php echo e(route('peminjam.borrowings.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('peminjam.borrowings.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">shopping_cart</span>
                                <span>Peminjaman Saya</span>
                            </a>
                            <a href="<?php echo e(route('peminjam.history.index')); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded text-sm font-medium transition <?php echo e(request()->routeIs('peminjam.history.*') ? 'sidebar-link-active' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                                <span class="material-symbols-outlined text-[20px]">history</span>
                                <span>Riwayat</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>

                <!-- User Info & Logout -->
                <div class="border-t border-gray-200 dark:border-white/5 p-4">
                    <div class="flex items-center gap-3 mb-3 px-2">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[20px]">person</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white truncate"><?php echo e(auth()->user()->name); ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 capitalize"><?php echo e(auth()->user()->role); ?></div>
                        </div>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full bg-red-600/10 hover:bg-red-600/20 dark:bg-red-600/20 dark:hover:bg-red-600/30 text-red-600 dark:text-red-400 font-medium py-2.5 px-4 rounded transition flex items-center justify-center gap-2">
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
            <header class="navbar-texture sticky top-0 z-50 bg-white/95 dark:bg-panel-dark/95 backdrop-blur-md border-b border-gray-200 dark:border-white/5 shadow-lg">
                <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                    <div class="flex items-center gap-4">
                        <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                        <div class="hidden lg:block">
                            <h2 class="text-gray-900 dark:text-white text-sm font-bold tracking-[0.2em] uppercase"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
                            <div class="h-0.5 w-12 bg-primary mt-1"></div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="theme-toggle" class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                            <span class="material-symbols-outlined dark:hidden">dark_mode</span>
                            <span class="material-symbols-outlined hidden dark:block">light_mode</span>
                        </button>
                        <?php echo $__env->make('components.notifications', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <div class="hidden sm:flex items-center gap-2 opacity-40">
                            <div class="w-1.5 h-1.5 bg-accent-green rounded-full animate-pulse"></div>
                            <span class="text-[10px] text-gray-900 dark:text-white font-bold tracking-wider">ONLINE</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6 overflow-y-auto">
                <!-- Alerts -->
                <!-- Toast Container -->
                <div id="toast-container" class="fixed top-20 right-4 z-50 flex flex-col gap-3 min-w-[300px] max-w-sm pointer-events-none">
                    <?php if(session('success')): ?>
                        <div class="toast bg-white dark:bg-panel-dark border-l-4 border-green-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0 industrial-border">
                            <span class="material-symbols-outlined text-green-500">check_circle</span>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Berhasil</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><?php echo e(session('success')); ?></p>
                            </div>
                            <button onclick="this.closest('.toast').remove()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="toast bg-white dark:bg-panel-dark border-l-4 border-red-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0">
                            <span class="material-symbols-outlined text-red-500">error</span>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Terjadi Kesalahan</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><?php echo e(session('error')); ?></p>
                            </div>
                            <button onclick="this.closest('.toast').remove()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="toast bg-white dark:bg-panel-dark border-l-4 border-yellow-500 shadow-lg rounded-r-lg p-4 flex items-start gap-3 pointer-events-auto transform transition-all duration-300 translate-x-0">
                            <span class="material-symbols-outlined text-yellow-500">warning</span>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Perlu Perhatian</h4>
                                <ul class="mt-1 list-disc list-inside text-sm text-gray-600 dark:text-gray-300 space-y-1">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <button onclick="this.closest('.toast').remove()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    <?php else: ?>
        <?php echo $__env->yieldContent('content'); ?>
    <?php endif; ?>

    <?php echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.confirm-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
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
        window.addEventListener('load', function() {
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
            themeToggleBtn.addEventListener('click', function() {
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
<?php /**PATH D:\UKKfix\resources\views/layouts/app.blade.php ENDPATH**/ ?>