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
        // 1. Total alat lab (semua alat yang terdaftar)
        $totalTools = Tool::count();

        // 2. Alat tersedia (stok yang masih tersedia untuk dipinjam)
        $availableTools = Tool::sum('stok');

        // 3. Peminjaman bulan ini
        $borrowingsThisMonth = \App\Models\Borrowing::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // 4. Persentase alat tersedia
        $totalStockCapacity = Tool::selectRaw('SUM(stok + stok_rusak + stok_perbaikan) as total')->value('total');
        $availabilityPercentage = $totalStockCapacity > 0 ? round(($availableTools / $totalStockCapacity) * 100) : 0;

        // 5. Alat untuk katalog (6 alat dengan gambar dan stok > 0)
        $featuredTools = Tool::with('category')
            ->where('stok', '>', 0)
            ->whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->orderBy('stok', 'desc')
            ->take(6)
            ->get();

        return view('landing.index', compact(
            'totalTools',
            'availableTools',
            'borrowingsThisMonth',
            'availabilityPercentage',
            'featuredTools'
        ));
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
            $toolsQuery->where('kategori_id', $categoryId);
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

        // View All Tools with pagination (10 items per page)
        $tools = $toolsQuery->paginate(10)->withQueryString();

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
