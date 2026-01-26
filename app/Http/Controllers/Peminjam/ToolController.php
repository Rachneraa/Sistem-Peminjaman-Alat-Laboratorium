<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;

class ToolController extends Controller
{
    /**
     * Menampilkan daftar alat
     */
    public function index(Request $request)
    {
        $query = Tool::with('category')->where('stok', '>', 0);

        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('search') && $request->search) {
            $query->where('nama_alat', 'like', '%' . $request->search . '%');
        }

        $tools = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('peminjam.tools.index', compact('tools', 'categories'));
    }

    /**
     * Menampilkan detail alat
     */
    public function show(Tool $tool)
    {
        $tool->load('category');
        return view('peminjam.tools.show', compact('tool'));
    }
}





