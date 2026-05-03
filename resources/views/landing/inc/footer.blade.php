<!-- Footer Component -->
<footer class="bg-gradient-to-b from-[#0a3f4f] to-[#051f2a] text-white/90 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- Brand Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-white font-bold text-lg">LabPinjam</span>
                </div>
                <p class="text-white/70 text-sm leading-relaxed">
                    Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan praktikum yang lebih efisien.
                </p>
            </div>

            <!-- Navigation Links -->
            <div class="space-y-4">
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('landing.index') }}" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.tools') }}" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            Daftar Alat
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('landing.index') }}#cara-peminjaman" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            Cara Peminjaman
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Information Links -->
            <div class="space-y-4">
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider">Informasi</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            Syarat & Ketentuan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-white/70 hover:text-white transition-colors duration-200 text-sm">
                            Kebijakan Privasi
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Section -->
            <div class="space-y-4">
                <h4 class="text-white font-semibold text-sm uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#1a7a94] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-white/70 text-sm">labpinjam@university.ac.id</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#1a7a94] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-white/70 text-sm">+62 22 1234 5678</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#1a7a94] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-white/70 text-sm">Gedung Lab Lt. 2, Kampus Utama</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-white/10 pt-8">
            <!-- Footer Bottom -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-white/60 text-sm">
                    &copy; {{ date('Y') }} LabPinjam. Semua hak dilindungi.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-white/60 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20v-7.21H5.5V9.25h2.79V7.07c0-2.77 1.69-4.28 4.16-4.28 1.18 0 2.2.09 2.49.13v2.89h-1.71c-1.34 0-1.6.64-1.6 1.57v2.05h3.2l-.41 3.54h-2.79V20"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-white/60 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-white/60 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
