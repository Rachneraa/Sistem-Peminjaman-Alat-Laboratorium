<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Alat')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
        }
        .hero-pattern {
            background-color: #102F68;
            /* Simple elegant gradient over primary solid blue */
            background-image: radial-gradient(circle at top right, rgba(37,99,235,0.3) 0%, transparent 40%),
                              radial-gradient(circle at bottom right, rgba(37,99,235,0.2) 0%, transparent 40%),
                              radial-gradient(circle at center 20%, rgba(255,255,255,0.05) 0%, transparent 20%);
            color: white;
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

    <!-- Navigation -->
    <nav class="sticky top-0 bg-landing-bg w-full z-50 py-4 px-6 md:px-12 flex items-center justify-between text-white border-b border-landing-border/50 shadow-md">
        <div class="flex items-center gap-2">
            <!-- Logo Icon Box -->
            <div class="w-8 h-8 rounded bg-white/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">science</span>
            </div>
            <span class="font-bold text-lg tracking-wide">LabPinjam</span>
        </div>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="{{ route('landing.index') }}" class="hover:text-blue-300 transition {{ request()->routeIs('landing.index') ? 'text-blue-200' : 'text-white/80' }}">Beranda</a>
            <a href="{{ route('landing.tools') }}" class="hover:text-blue-300 transition {{ request()->routeIs('landing.tools') ? 'text-blue-200' : 'text-white/80' }}">Alat Lab</a>
            <a href="{{ route('landing.index') }}#cara-peminjaman" class="hover:text-blue-300 transition text-white/80">Cara Peminjaman</a>
        </div>

        <div>
            @if(auth()->check())
                 <a href="/" class="bg-landing-primary hover:bg-landing-primary/90 text-white px-5 py-2.5 rounded text-sm font-semibold transition border border-white/10 shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                 </a>
            @else
                <a href="{{ route('login') }}" class="bg-landing-primary hover:bg-landing-primary/90 text-white px-5 py-2.5 rounded text-sm font-semibold transition border border-white/10 shadow-sm flex items-center gap-2">
                    Login <span class="material-symbols-outlined text-[18px]">login</span>
                </a>
            @endif
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#0B1426] text-white/70 py-12 px-6 md:px-12 mt-12 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <div class="flex items-center gap-2 text-white">
                    <div class="w-8 h-8 rounded bg-landing-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">science</span>
                    </div>
                    <span class="font-bold text-lg tracking-wide">LabPinjam</span>
                </div>
                <p class="text-sm">Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan praktikum yang lebih efisien.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4 text-sm tracking-wider">NAVIGASI</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing.index') }}" class="hover:text-white transition">Beranda</a></li>
                    <li><a href="{{ route('landing.tools') }}" class="hover:text-white transition">Daftar Alat</a></li>
                    <li><a href="{{ route('landing.index') }}#cara-peminjaman" class="hover:text-white transition">Cara Peminjaman</a></li>
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
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">mail</span> labpinjam@university.ac.id</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-[16px]">call</span> +62 22 1234 5678</li>
                    <li class="flex items-start gap-2"><span class="material-symbols-outlined text-[16px] mt-0.5">location_on</span> Gedung Lab Lt. 2, Kampus Utama</li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
