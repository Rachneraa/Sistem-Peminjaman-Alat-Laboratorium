<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Tool;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar peminjaman saya
     */
    public function index()
    {
        $borrowings = Borrowing::with(['borrowingDetails.tool'])
            ->where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_pengembalian'])
            ->latest()
            ->paginate(10);
        return view('peminjam.borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan form ajukan peminjaman
     */
    public function create(Request $request)
    {
        $tools = Tool::where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->with('category')
            ->get();
        
        $selectedToolId = $request->get('tool_id');
        $selectedTool = null;
        
        if ($selectedToolId) {
            $selectedTool = Tool::where('id', $selectedToolId)
                ->where('status', 'tersedia')
                ->where('stok', '>', 0)
                ->with('category')
                ->first();
        }
        
        return view('peminjam.borrowings.create', compact('tools', 'selectedToolId', 'selectedTool'));
    }

    /**
     * Menyimpan peminjaman baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_pinjam',
            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Cek stok tersedia
            foreach ($validated['tools'] as $item) {
                $tool = Tool::find($item['tool_id']);
                if (!$tool->isAvailable($item['jumlah'])) {
                    DB::rollBack();
                    return back()->withErrors(['tools' => 'Alat ' . $tool->nama_alat . ' tidak tersedia atau stok tidak mencukupi.'])
                        ->withInput();
                }
            }

            // Buat peminjaman
            $borrowing = Borrowing::create([
                'user_id' => Auth::id(),
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => 'menunggu',
            ]);

            // Buat detail peminjaman
            foreach ($validated['tools'] as $item) {
                BorrowingDetail::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $item['tool_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            ActivityLog::createLog(
                Auth::id(),
                'Mengajukan peminjaman baru',
                $borrowing
            );

            // Notifikasi ke petugas dan admin
            NotificationService::notifyNewBorrowingRequest($borrowing);

            DB::commit();
            return redirect()->route('peminjam.borrowings.index')
                ->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuan petugas.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['borrowingDetails.tool.category']);
        return view('peminjam.borrowings.show', compact('borrowing'));
    }

    /**
     * Cetak bukti peminjaman
     */
    public function print(Borrowing $borrowing)
    {
        if ($borrowing->user_id !== Auth::id()) {
            abort(403);
        }

        $borrowing->load(['user', 'borrowingDetails.tool.category']);
        return view('borrowings.print', compact('borrowing'));
    }
}

