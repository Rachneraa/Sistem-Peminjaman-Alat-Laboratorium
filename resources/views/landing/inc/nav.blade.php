<!-- Navigation Component -->
<nav class="sticky top-0 z-50 w-full bg-gradient-to-r from-[#0d5868] to-[#1a7a94] shadow-lg backdrop-blur-md bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center hover:bg-white/30 transition-colors duration-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <a href="{{ route('landing.index') }}" class="text-white font-bold text-xl tracking-tight hover:text-blue-100 transition-colors duration-200">
                    LabPinjam
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('landing.index') }}" 
                   class="text-white/90 hover:text-white font-medium transition-colors duration-200 {{ request()->routeIs('landing.index') ? 'text-white border-b-2 border-white pb-1' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('landing.tools') }}" 
                   class="text-white/90 hover:text-white font-medium transition-colors duration-200 {{ request()->routeIs('landing.tools') ? 'text-white border-b-2 border-white pb-1' : '' }}">
                    Alat Lab
                </a>
                <a href="{{ route('landing.index') }}#cara-peminjaman" 
                   class="text-white/90 hover:text-white font-medium transition-colors duration-200">
                    Cara Peminjaman
                </a>
            </div>

            <!-- Right Side - Auth Button -->
            <div class="flex items-center gap-4">
                @if(auth()->check())
                    @php
                        $dashboardRoute = auth()->user()->isAdmin()
                            ? route('admin.dashboard')
                            : (auth()->user()->isPetugas() ? route('petugas.dashboard') : route('peminjam.dashboard'));
                    @endphp
                    <a href="{{ $dashboardRoute }}" 
                       class="hidden sm:inline-flex items-center gap-2 bg-white text-[#0d5868] px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-200 hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="hidden sm:inline-flex items-center gap-2 bg-white text-[#0d5868] px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-all duration-200 hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v2a2 2 0 01-2 2H7a2 2 0 01-2-2v-2"></path>
                        </svg>
                        Login
                    </a>
                @endif

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-white hover:bg-white/20 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/20">
            <a href="{{ route('landing.index') }}" 
               class="block px-4 py-2 text-white/90 hover:text-white hover:bg-white/10 rounded transition-colors duration-200 {{ request()->routeIs('landing.index') ? 'bg-white/20 text-white' : '' }}">
                Beranda
            </a>
            <a href="{{ route('landing.tools') }}" 
               class="block px-4 py-2 text-white/90 hover:text-white hover:bg-white/10 rounded transition-colors duration-200 {{ request()->routeIs('landing.tools') ? 'bg-white/20 text-white' : '' }}">
                Alat Lab
            </a>
            <a href="{{ route('landing.index') }}#cara-peminjaman" 
               class="block px-4 py-2 text-white/90 hover:text-white hover:bg-white/10 rounded transition-colors duration-200">
                Cara Peminjaman
            </a>
            @if(auth()->check())
                @php
                    $dashboardRoute = auth()->user()->isAdmin()
                        ? route('admin.dashboard')
                        : (auth()->user()->isPetugas() ? route('petugas.dashboard') : route('peminjam.dashboard'));
                @endphp
                <a href="{{ $dashboardRoute }}" 
                   class="block mt-2 px-4 py-2 bg-white text-[#0d5868] rounded font-semibold hover:bg-blue-50 transition-colors duration-200">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="block mt-2 px-4 py-2 bg-white text-[#0d5868] rounded font-semibold hover:bg-blue-50 transition-colors duration-200">
                    Login
                </a>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close menu when a link is clicked
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
        }
    });
</script>
