<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Halaman form cetak laporan
     */
    public function index()
    {
        return view('petugas.reports.index');
    }

    /**
     * Laporan peminjaman
     */
    public function borrowingReport(Request $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

        // Parse tanggal jika string
        if (is_string($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (is_string($end_date)) {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->get();

        $pdf = Pdf::loadView('admin.reports.borrowing', compact('borrowings', 'start_date', 'end_date'));
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan pengembalian
     */
    public function returnReport(Request $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

        // Parse tanggal jika string
        if (is_string($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (is_string($end_date)) {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        $returns = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool'])
            ->whereBetween('tanggal_kembali', [$start_date, $end_date])
            ->get();

        $pdf = Pdf::loadView('admin.reports.return', compact('returns', 'start_date', 'end_date'));
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }
}





