<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page
     */
    public function index()
    {
        // 1. Total Alat (Total of available stock)
        $totalTools = Tool::sum('stok');

        // 2. Alat Terbaru (Latest 3 added)
        $latestTools = Tool::with('category')->latest()->take(3)->get();

        // 3. Alat Unggulan (Top 6 tools with stock > 0, sorted by stock lowest to highest)
        $featuredTools = Tool::with('category')
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->take(6)
            ->get();

        return view('landing.index', compact('totalTools', 'latestTools', 'featuredTools'));
    }

    /**
     * Tampilkan semua alat untuk halaman publik
     */
    public function tools()
    {
        // View All Tools with pagination
        $tools = Tool::with('category')
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->paginate(10);

        return view('landing.tools', compact('tools'));
    }

    /**
     * Proses klik pinjam dari landing page
     */
    public function pinjam($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->isAdmin() || $user->isPetugas()) {
            return back()->with('sweetalert_error', 'Gagal menambah alat. Alat hanya dapat dipinjam oleh peminjam. Silakan login sebagai peminjam untuk melanjutkan.');
        }

        // Redirect ke form peminjaman
        return redirect()->route('peminjam.borrowings.create', ['tool_id' => $id]);
    }
}
