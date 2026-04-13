<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use App\Models\Tool;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Menampilkan daftar peminjaman untuk disetujui
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'borrowingDetails.tool']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        } else {
            // Default: tampilkan yang menunggu, disetujui, dan menunggu pengembalian
            $query->whereIn('status', ['menunggu', 'disetujui', 'menunggu_pengembalian']);
        }

        $borrowings = $query->latest()->paginate(15);

        return view('petugas.borrowings.index', compact('borrowings'));
    }

    /**
     * Menampilkan detail peminjaman
     */
    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'borrowingDetails.tool.category', 'return']);
        return view('petugas.borrowings.show', compact('borrowing'));
    }

    /**
     * Menyetujui peminjaman
     */
    public function approve(Request $request, Borrowing $borrowing)
    {
        // Fallback untuk request dari form lama yang belum mengirim jaminan_tipe.
        $request->merge([
            'jaminan_tipe' => $request->input('jaminan_tipe', 'ktp'),
        ]);

        $validated = $request->validate([
            'jaminan_tipe' => 'required|string|in:ktp,sim,kartu_pelajar,lainnya',
        ]);

        DB::beginTransaction();
        try {
            // Cek stok tersedia
            foreach ($borrowing->borrowingDetails as $detail) {
                $tool = Tool::find($detail->tool_id);
                if (!$tool->isAvailable($detail->jumlah)) {
                    DB::rollBack();
                    return back()->with('error', 'Stok alat ' . $tool->nama_alat . ' tidak mencukupi.');
                }
            }

            // Kurangi stok dan update status alat
            foreach ($borrowing->borrowingDetails as $detail) {
                $tool = Tool::find($detail->tool_id);
                $tool->decrement('stok', $detail->jumlah);
                $tool->updateStatusFromStock();
            }

            // Set jatuh tempo = tanggal selesai (dari form peminjaman)
            $jatuh_tempo = $borrowing->tanggal_selesai;

            // Simpan jaminan saat approval dan langsung aktifkan peminjaman
            $borrowing->update([
                'status' => 'disetujui',
                'jatuh_tempo' => $jatuh_tempo,
                'jaminan_tipe' => $validated['jaminan_tipe'],
                'ktp_diterima_at' => now(),
                'ktp_diterima_oleh' => auth()->id(),
                'ktp_dikembalikan_at' => null,
                'ktp_dikembalikan_oleh' => null,
            ]);

            NotificationService::notifyBorrowingApproved($borrowing);

            ActivityLog::createLog(
                auth()->id(),
                'Menyetujui peminjaman ID: ' . $borrowing->id . ' dengan jaminan ' . strtoupper($validated['jaminan_tipe']),
                $borrowing
            );

            DB::commit();
            return back()->with('success', 'Peminjaman berhasil disetujui dengan jaminan ' . strtoupper($validated['jaminan_tipe']) . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui peminjaman: ' . $e->getMessage());
        }
    }

    /**
     * Menolak peminjaman
     */
    public function reject(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'keterangan' => 'required|string',
        ]);

        $borrowing->update([
            'status' => 'ditolak',
            'keterangan' => $validated['keterangan'],
        ]);

        // Buat notifikasi
        NotificationService::notifyBorrowingRejected($borrowing);

        ActivityLog::createLog(
            auth()->id(),
            'Menolak peminjaman ID: ' . $borrowing->id,
            $borrowing
        );

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Kirim pesan pengingat pengembalian ke peminjam
     */
    public function sendReminder(Request $request, Borrowing $borrowing)
    {
        $validated = $request->validate([
            'pesan' => 'nullable|string|max:500',
        ]);

        // Hanya bisa kirim reminder untuk peminjaman yang disetujui atau menunggu pengembalian
        if (!in_array($borrowing->status, ['disetujui', 'menunggu_pengembalian'])) {
            return back()->with('error', 'Hanya bisa mengirim pengingat untuk peminjaman yang disetujui atau menunggu pengembalian.');
        }

        // Kirim notifikasi pengingat
        NotificationService::notifyReturnReminder($borrowing, $validated['pesan'] ?? null, auth()->user());

        ActivityLog::createLog(
            auth()->id(),
            'Mengirim pengingat pengembalian ke peminjam ID: ' . $borrowing->user_id . ' untuk peminjaman #' . $borrowing->id,
            $borrowing
        );

        return back()->with('success', 'Pesan pengingat berhasil dikirim ke peminjam.');
    }

    /**
     * Kirim notifikasi estimasi denda ke peminjam
     */
    public function sendFineNotification(Borrowing $borrowing)
    {
        // Hanya bisa kirim notifikasi denda untuk peminjaman yang disetujui atau menunggu pengembalian
        if (!in_array($borrowing->status, ['disetujui', 'menunggu_pengembalian'])) {
            return back()->with('error', 'Hanya bisa mengirim notifikasi denda untuk peminjaman yang disetujui atau menunggu pengembalian.');
        }

        // Hitung estimasi denda
        $fineData = $borrowing->calculateEstimatedFine($borrowing->calculateDendaPerHariTotal());

        // Kirim notifikasi estimasi denda
        NotificationService::notifyFineEstimation(
            $borrowing,
            $fineData['denda'],
            $fineData['terlambat_hari'],
            auth()->user()
        );

        ActivityLog::createLog(
            auth()->id(),
            'Mengirim notifikasi estimasi denda ke peminjam ID: ' . $borrowing->user_id . ' untuk peminjaman #' . $borrowing->id . ' (Denda: Rp ' . number_format($fineData['denda'], 0, ',', '.') . ')',
            $borrowing
        );

        return back()->with('success', 'Notifikasi estimasi denda berhasil dikirim ke peminjam.');
    }

    /**
     * Menampilkan semua peminjaman (semua status)
     */
    public function all(Request $request)
    {
        $query = Borrowing::with(['user', 'borrowingDetails.tool']);

        // Filter berdasarkan status
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

        $borrowings = $query->latest()->paginate(15)->withQueryString();

        return view('petugas.borrowings.all', compact('borrowings'));
    }

    /**
     * Cetak bukti peminjaman (untuk petugas)
     */
    public function print(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'borrowingDetails.tool.category']);
        return view('borrowings.print', compact('borrowing'));
    }
}

