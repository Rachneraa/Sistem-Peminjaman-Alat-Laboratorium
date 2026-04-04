<!DOCTYPE html>
<html class="dark" lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0d5868",
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
            min-height: max(884px, 100dvh);
        }

        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(13, 88, 104, 0.4);
        }

        .industrial-border {
            border-left: 4px solid #0d5868;
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark min-h-screen flex items-center justify-center px-6 py-8 font-display">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <div
                class="inline-flex items-center justify-center w-16 h-16 mb-4 bg-primary/10 rounded-xl border border-primary/10 shadow-sm">
                <span class="material-symbols-outlined text-[34px] text-primary">lock_reset</span>
            </div>
            <h1 class="text-gray-900 dark:text-white text-3xl font-bold">Reset Password</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Masukkan password baru untuk akun Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-500/10 border-l-4 border-red-500 rounded-lg">
                <ul class="text-sm text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="bg-white dark:bg-panel-dark p-6 rounded-xl shadow-xl border border-gray-200 dark:border-white/5 industrial-border">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="flex flex-col gap-2">
                    <label
                        class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[18px]">mail</span>
                        Alamat Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required maxlength="50"
                        autofocus
                        class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 px-4 text-base transition-all"
                        placeholder="nama@email.com" />
                </div>

                <div class="flex flex-col gap-2">
                    <label
                        class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                        Password Baru
                    </label>
                    <div class="relative">
                        <input id="reset-password" type="password" name="password" required maxlength="60"
                            class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 px-4 pr-12 text-base transition-all"
                            placeholder="Minimal 8 karakter" />
                        <button type="button" data-toggle-password data-target="reset-password"
                            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-primary transition-colors"
                            aria-label="Tampilkan password">
                            <span class="material-symbols-outlined text-[20px]" data-password-icon>visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label
                        class="flex items-center gap-2 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[18px]">shield</span>
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input id="reset-password-confirmation" type="password" name="password_confirmation" required
                            maxlength="60"
                            class="form-input w-full rounded-lg text-gray-900 dark:text-white border border-gray-300 dark:border-input-border bg-gray-50 dark:bg-[#1a2e32] focus:border-primary h-12 placeholder:text-gray-400 px-4 pr-12 text-base transition-all"
                            placeholder="Ulangi password baru" />
                        <button type="button" data-toggle-password data-target="reset-password-confirmation"
                            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-primary transition-colors"
                            aria-label="Tampilkan password">
                            <span class="material-symbols-outlined text-[20px]" data-password-icon>visibility</span>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-lg shadow-lg shadow-primary/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <span class="tracking-[0.1em] uppercase">Reset Password</span>
                    <span class="material-symbols-outlined">check_circle</span>
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Kembali ke Login</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-toggle-password]').forEach(function (toggleButton) {
                toggleButton.addEventListener('click', function () {
                    const targetId = toggleButton.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const icon = toggleButton.querySelector('[data-password-icon]');
                    if (!targetInput || !icon) return;

                    const isPassword = targetInput.type === 'password';
                    targetInput.type = isPassword ? 'text' : 'password';
                    icon.textContent = isPassword ? 'visibility_off' : 'visibility';
                    toggleButton.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                });
            });

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Reset Password Gagal',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#0d5868',
                });
            @endif
        });
    </script>
</body>

</html>