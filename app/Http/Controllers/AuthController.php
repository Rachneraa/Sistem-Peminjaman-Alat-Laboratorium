<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'email' => 'required|email',
            'password' => 'required',
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
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
}


