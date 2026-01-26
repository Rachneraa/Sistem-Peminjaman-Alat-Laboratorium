<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Services\NotificationService;

class ToolController extends Controller
{
    /**
     * Menampilkan daftar alat
     */
    public function index(Request $request)
    {
        $query = Tool::with('category');

        // Filter by kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by nama_alat
        if ($request->filled('search')) {
            $query->where('nama_alat', 'like', "%{$request->search}%");
        }

        $tools = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();
        return view('admin.tools.index', compact('tools', 'categories'));
    }

    /**
     * Menampilkan form tambah alat
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create', compact('categories'));
    }

    /**
     * Menyimpan alat baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            'stok_rusak' => 'required|integer|min:0',
            'stok_perbaikan' => 'required|integer|min:0',
            // 'kondisi' => 'required|in:baik,rusak,perlu_perbaikan', // Removed as per request
            'status' => 'required|in:tersedia,dipinjam,rusak,perbaikan',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Set default kondisi if not present (since we removed it from form but DB needs it if not nullable default)
        // DB migration shows default 'baik', so it's handled there if omitted, but let's be safe if model doesn't strip it.
        // Actually $fillable includes it. Let's merge default if needed or just let DB handle it.
        // We actally removed it from validation, so it won't be in $validated.
        // If DB has default, we are good. Migration says default('baik').

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaGambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/tools'), $namaGambar);
            $validated['gambar'] = 'images/tools/' . $namaGambar;
        }

        $tool = Tool::create($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Menambah alat: ' . $tool->nama_alat,
            $tool
        );

        // Notifikasi ke admin lain
        NotificationService::notifyToolCreated($tool, auth()->user());

        return redirect()->route('admin.tools.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail alat
     */
    public function show(Tool $tool)
    {
        $tool->load('category', 'borrowingDetails.borrowing.user');
        return view('admin.tools.show', compact('tool'));
    }

    /**
     * Menampilkan form edit alat
     */
    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    /**
     * Update alat
     */
    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            'stok_rusak' => 'required|integer|min:0',
            'stok_perbaikan' => 'required|integer|min:0',
            //'kondisi' => 'required|in:baik,rusak,perlu_perbaikan',
            'status' => 'required|in:tersedia,dipinjam,rusak,perbaikan',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle upload gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($tool->gambar && file_exists(public_path($tool->gambar))) {
                unlink(public_path($tool->gambar));
            }

            $gambar = $request->file('gambar');
            $namaGambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('images/tools'), $namaGambar);
            $validated['gambar'] = 'images/tools/' . $namaGambar;
        }

        $tool->update($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Mengupdate alat: ' . $tool->nama_alat,
            $tool
        );

        return redirect()->route('admin.tools.index')
            ->with('success', 'Alat berhasil diupdate.');
    }

    /**
     * Hapus alat
     */
    public function destroy(Tool $tool)
    {
        $name = $tool->nama_alat;
        
        // Hapus gambar jika ada
        if ($tool->gambar && file_exists(public_path($tool->gambar))) {
            unlink(public_path($tool->gambar));
        }
        
        $tool->delete();

        ActivityLog::createLog(
            auth()->id(),
            'Menghapus alat: ' . $name,
            null
        );

        return redirect()->route('admin.tools.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}

