<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Tool;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar peminjaman
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'borrowingDetails.tool', 'return']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();
        return view('admin.borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'borrowingDetails.tool.category', 'return']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    /**
     * Menampilkan form tambah peminjaman
     */
    public function create()
    {
        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $tools = Tool::where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->with('category')
            ->orderBy('nama_alat')
            ->get();
        return view('admin.borrowings.create', compact('users', 'tools'));
    }

    /**
     * Menyimpan peminjaman baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu,disetujui,ditolak,menunggu_pengembalian,dikembalikan',
            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Cek stok tersedia jika status menunggu atau disetujui
            if (in_array($validated['status'], ['menunggu', 'disetujui'])) {
                foreach ($validated['tools'] as $item) {
                    $tool = Tool::find($item['tool_id']);
                    if (!$tool->isAvailable($item['jumlah'])) {
                        DB::rollBack();
                        return back()->withErrors(['tools' => 'Stok alat ' . $tool->nama_alat . ' tidak mencukupi.'])
                            ->withInput();
                    }
                }
            }

            // Buat peminjaman
            $borrowing = Borrowing::create([
                'user_id' => $validated['user_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Buat detail peminjaman
            foreach ($validated['tools'] as $item) {
                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $item['tool_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            // Jika status menunggu atau disetujui, kurangi stok
            if (in_array($validated['status'], ['menunggu', 'disetujui'])) {
                foreach ($borrowing->borrowingDetails as $detail) {
                    $tool = Tool::find($detail->tool_id);
                    $tool->decrement('stok', $detail->jumlah);
                    $tool->updateStatusFromStock();
                }
            }

            ActivityLog::createLog(
                auth()->id(),
                'Membuat peminjaman baru ID: ' . $borrowing->id,
                $borrowing
            );

            DB::commit();
            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Peminjaman berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan form edit peminjaman
     */
    public function edit(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'borrowingDetails.tool']);
        $users = User::where('role', 'peminjam')->orderBy('name')->get();
        $tools = Tool::with('category')->orderBy('nama_alat')->get();
        return view('admin.borrowings.edit', compact('borrowing', 'users', 'tools'));
    }

    /**
     * Update peminjaman
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:menunggu,disetujui,ditolak,menunggu_pengembalian,dikembalikan',
            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $borrowing->status;
            $newStatus = $validated['status'];

            // Kembalikan stok jika sebelumnya menunggu atau disetujui
            if (in_array($oldStatus, ['menunggu', 'disetujui']) && !in_array($newStatus, ['menunggu', 'disetujui'])) {
                foreach ($borrowing->borrowingDetails as $detail) {
                    $tool = Tool::find($detail->tool_id);
                    $tool->increment('stok', $detail->jumlah);
                    $tool->updateStatusFromStock();
                }
            }

            // Update peminjaman
            $borrowing->update([
                'user_id' => $validated['user_id'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $newStatus,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Hapus detail lama
            $borrowing->borrowingDetails()->delete();

            // Buat detail baru
            $newDetails = [];
            foreach ($validated['tools'] as $item) {
                $newDetails[] = BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $item['tool_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            // Refresh relasi
            $borrowing->load('borrowingDetails');

            // Jika status baru menunggu atau disetujui, kurangi stok
            if (in_array($newStatus, ['menunggu', 'disetujui'])) {
                // Cek stok tersedia
                foreach ($validated['tools'] as $item) {
                    $tool = Tool::find($item['tool_id']);
                    if (!$tool->isAvailable($item['jumlah'])) {
                        DB::rollBack();
                        return back()->withErrors(['tools' => 'Stok alat ' . $tool->nama_alat . ' tidak mencukupi.'])
                            ->withInput();
                    }
                }

                // Kurangi stok
                foreach ($borrowing->borrowingDetails as $detail) {
                    $tool = Tool::find($detail->tool_id);
                    $tool->decrement('stok', $detail->jumlah);
                    $tool->updateStatusFromStock();
                }
            }

            ActivityLog::createLog(
                auth()->id(),
                'Mengupdate peminjaman ID: ' . $borrowing->id,
                $borrowing
            );

            DB::commit();
            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Peminjaman berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus peminjaman
     */
    public function destroy(Borrowing $borrowing)
    {
        DB::beginTransaction();
        try {
            // Kembalikan stok alat jika status menunggu atau disetujui
            if (in_array($borrowing->status, ['menunggu', 'disetujui'])) {
                foreach ($borrowing->borrowingDetails as $detail) {
                    $tool = $detail->tool;
                    $tool->increment('stok', $detail->jumlah);
                    $tool->updateStatusFromStock();
                }
            }

            $borrowing->delete();

            ActivityLog::createLog(
                auth()->id(),
                'Menghapus peminjaman ID: ' . $borrowing->id,
                null
            );

            DB::commit();
            return redirect()->route('admin.borrowings.index')
                ->with('success', 'Peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus peminjaman: ' . $e->getMessage());
        }
    }
}

