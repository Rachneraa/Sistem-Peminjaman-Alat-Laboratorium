<!DOCTYPE html>
<html class="dark" lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Space Grotesk', sans-serif;
            min-height: max(884px, 100dvh);
        }
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(13, 88, 104, 0.4);
        }
        .industrial-border {
            border-left: 4px solid #0d5868;
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
<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display transition-colors duration-300 relative overflow-hidden">
    <!-- Background SVG Patterns -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <!-- Top Right Pattern -->
        <svg class="absolute -top-20 -right-20 w-96 h-96 opacity-5 dark:opacity-10" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1.5" class="fill-primary"/>
                </pattern>
            </defs>
            <rect width="200" height="200" fill="url(#grid)"/>
        </svg>
        
        <!-- Bottom Left Geometric -->
        <svg class="absolute -bottom-32 -left-32 w-80 h-80 opacity-5 dark:opacity-10" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 0L200 100L100 200L0 100Z" class="fill-primary"/>
            <circle cx="100" cy="100" r="60" class="fill-background-light dark:fill-background-dark"/>
            <circle cx="100" cy="100" r="40" class="fill-primary"/>
        </svg>
        
        <!-- Center Floating Tools Icon -->
        <svg class="absolute top-1/4 right-1/4 w-64 h-64 opacity-[0.02] dark:opacity-[0.05]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="stroke-primary"/>
        </svg>
    </div>
    
    <!-- Top Navigation / Header -->

    <main class="flex-1 flex flex-col items-center justify-center px-6 py-8 relative z-10">
        <div class="w-full max-w-md">
            <!-- Headline Section -->
            <div class="mb-8 text-center">
                <div class="inline-flex items-center justify-center p-3 mb-4 bg-primary/10 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-primary">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <h1 class="text-gray-900 dark:text-white tracking-tight text-3xl font-bold leading-tight">Buat Akun Baru</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-normal mt-2">Bergabung dengan sistem peminjaman alat</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border-l-4 border-red-500 rounded-lg">
                    <ul class="text-sm text-red-400 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Modular Form Card -->
            <div class="bg-white dark:bg-panel-dark p-6 rounded-xl shadow-xl border border-gray-200 dark:border-white/5 industrial-border">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Full Name Field -->
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-[#94bdc7] px-4 text-base transition-all" 
                               placeholder="Contoh: John Doe"/>
                    </div>

                    <!-- Email Address Field -->
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                            Alamat Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-[#94bdc7] px-4 text-base transition-all" 
                               placeholder="nama@email.com"/>
                    </div>

                    <!-- Password Field -->
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                            Password
                        </label>
                        <input type="password" name="password" required
                               class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-[#94bdc7] px-4 text-base transition-all" 
                               placeholder="••••••••"/>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                            <span class="material-symbols-outlined text-[18px]">shield</span>
                            Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 dark:placeholder:text-[#94bdc7] px-4 text-base transition-all" 
                               placeholder="••••••••"/>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] mt-4 flex items-center justify-center gap-2">
                        <span class="tracking-[0.1em] uppercase">Daftar Akun Baru</span>
                        <span class="material-symbols-outlined">trending_flat</span>
                    </button>
                </form>
            </div>

            <!-- Login Redirect -->
            <div class="mt-8 text-center">
                <p class="text-gray-600 dark:text-gray-400 text-sm">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-primary font-bold hover:underline ml-1">Login di sini</a>
                </p>
            </div>
        </div>
    </main>

    <!-- Refined Utility Footer Detail -->
    <footer class="p-6 flex flex-col items-center gap-2">
        <div class="flex items-center gap-2 opacity-30">
            <div class="size-1.5 bg-accent-green rounded-full animate-pulse"></div>
            <span class="text-[10px] text-gray-900 dark:text-white font-bold tracking-[0.2em] uppercase">System Ready // v1.0.4</span>
        </div>
        <div class="w-32 h-[1px] bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
    </footer>
</body>
</html>
