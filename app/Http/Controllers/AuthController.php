<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|max:60',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log aktivitas
            ActivityLog::createLog(
                Auth::id(),
                'Login ke sistem',
                Auth::user()
            );

            // Redirect berdasarkan role
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isPetugas()) {
                return redirect()->route('petugas.dashboard');
            } else {
                return redirect()->route('peminjam.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // Log aktivitas
        if (Auth::check()) {
            ActivityLog::createLog(
                Auth::id(),
                'Logout dari sistem',
                Auth::user()
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Menampilkan form register
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isPetugas()) {
                return redirect()->route('petugas.dashboard');
            } else {
                return redirect()->route('peminjam.dashboard');
            }
        }
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|max:60|confirmed',
        ]);

        // Buat user baru dengan role peminjam
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'peminjam', // Default role untuk registrasi
        ]);

        // Log aktivitas
        ActivityLog::createLog(
            $user->id,
            'Registrasi akun baru',
            $user
        );

        // Auto login setelah registrasi
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('peminjam.dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    /**
     * Menampilkan form lupa password.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email terdaftar.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:50',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password berhasil dikirim ke email Anda.')
            : back()->withErrors(['email' => $this->translatePasswordStatus($status)]);
    }

    /**
     * Menampilkan form reset password.
     */
    public function showResetPasswordForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Proses reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:8|max:60|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                ActivityLog::createLog(
                    $user->id,
                    'Reset password akun',
                    $user
                );
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login kembali.')
            : back()->withErrors(['email' => $this->translatePasswordStatus($status)]);
    }

    /**
     * Terjemahkan status broker password ke bahasa Indonesia.
     */
    private function translatePasswordStatus(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'Email tidak terdaftar di sistem.',
            Password::RESET_THROTTLED => 'Permintaan terlalu sering. Silakan coba lagi beberapa saat.',
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            default => 'Terjadi kesalahan saat memproses permintaan reset password.',
        };
    }
}


