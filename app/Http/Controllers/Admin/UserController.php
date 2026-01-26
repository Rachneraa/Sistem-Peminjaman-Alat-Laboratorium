<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Menambah user baru: ' . $user->name,
            $user
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan form edit user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Mengupdate user: ' . $user->name,
            $user
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();

        ActivityLog::createLog(
            auth()->id(),
            'Menghapus user: ' . $name,
            null
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}

