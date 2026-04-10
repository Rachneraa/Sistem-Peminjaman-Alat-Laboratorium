<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;
use App\Models\BorrowingDetail;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page
     */
    public function index()
    {
        // 1. Alat siap dipinjam (stok yang masih tersedia)
        $availableTools = Tool::sum('stok');

        // 2. Alat yang sedang dipinjam atau masih menunggu proses
        $borrowedTools = BorrowingDetail::whereHas('borrowing', function ($query) {
            $query->whereIn('status', ['menunggu', 'disetujui', 'menunggu_pengembalian']);
        })->sum('jumlah');

        // 3. Total alat fisik tetap stabil: tersedia + sedang dipinjam + rusak + perbaikan
        $toolStockTotals = Tool::selectRaw('COALESCE(SUM(stok_rusak + stok_perbaikan), 0) as total')
            ->value('total');

        $totalTools = $availableTools + $borrowedTools + $toolStockTotals;

        // 4. Alat Terbaru (Latest 3 added)
        $latestTools = Tool::with('category')->latest()->take(3)->get();

        // 5. Alat Unggulan (Top 6 tools with stock > 0, sorted by stock lowest to highest)
        $featuredTools = Tool::with('category')
            ->where('stok', '>', 0)
            ->orderBy('stok', 'asc')
            ->take(6)
            ->get();

        return view('landing.index', compact('totalTools', 'availableTools', 'latestTools', 'featuredTools'));
    }

    /**
     * Tampilkan semua alat untuk halaman publik
     */
    public function tools(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category');
        $sort = $request->query('sort', 'default');

        $toolsQuery = Tool::with('category');

        if ($search) {
            $toolsQuery->where('nama_alat', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $toolsQuery->where('category_id', $categoryId);
        }

        switch ($sort) {
            case 'name-asc':
                $toolsQuery->orderBy('nama_alat', 'asc');
                break;
            case 'stock-high':
                $toolsQuery->orderBy('stok', 'desc');
                break;
            case 'available':
                $toolsQuery->orderBy('stok', 'desc');
                break;
            default:
                $toolsQuery->orderBy('stok', 'asc');
                break;
        }

        // View All Tools with pagination
        $tools = $toolsQuery->paginate(9)->withQueryString();

        // Get all categories
        $categories = Category::orderBy('nama_kategori')->get();

        return view('landing.tools', compact('tools', 'categories', 'search', 'categoryId', 'sort'));
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
