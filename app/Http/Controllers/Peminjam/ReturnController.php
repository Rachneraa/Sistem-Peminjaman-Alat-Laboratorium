<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\Tool;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use App\Helpers\DendaHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnController extends Controller
{
    /**
     * Request pengembalian alat (hanya mengubah status, menunggu persetujuan petugas)
     */
    public function requestReturn(Borrowing $borrowing)
    {
        // Validasi: hanya peminjam yang memiliki peminjaman ini
        if ($borrowing->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengembalikan peminjaman ini.');
        }

        // Validasi: hanya peminjaman yang disetujui yang bisa dikembalikan
        if ($borrowing->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang bisa dikembalikan.');
        }

        try {
            // Update status menjadi menunggu pengembalian
            $borrowing->update(['status' => 'menunggu_pengembalian']);

            // Log aktivitas
            ActivityLog::createLog(
                Auth::id(),
                'Mengajukan pengembalian peminjaman ID: ' . $borrowing->id,
                $borrowing
            );

            // Notifikasi ke petugas
            NotificationService::notifyReturnByBorrower($borrowing);
            
            return back()->with('success', 'Permintaan pengembalian telah diajukan. Menunggu persetujuan petugas.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengajukan pengembalian: ' . $e->getMessage());
        }
    }
}

