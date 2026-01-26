<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ActivityLog;
use App\Services\NotificationService;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori
     */
    public function index(Request $request)
    {
        $query = Category::withCount('tools');

        // Search by nama_kategori
        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', "%{$request->search}%");
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
        ]);

        $category = Category::create($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Menambah kategori: ' . $category->nama_kategori,
            $category
        );

        // Notifikasi ke admin lain
        NotificationService::notifyCategoryCreated($category, auth()->user());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id,
        ]);

        $category->update($validated);

        ActivityLog::createLog(
            auth()->id(),
            'Mengupdate kategori: ' . $category->nama_kategori,
            $category
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate.');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Category $category)
    {
        $name = $category->nama_kategori;
        $category->delete();

        ActivityLog::createLog(
            auth()->id(),
            'Menghapus kategori: ' . $name,
            null
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

