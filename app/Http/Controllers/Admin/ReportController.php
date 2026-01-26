<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman pilih laporan
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Laporan peminjaman
     */
    public function borrowingReport(Request $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

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

        $returns = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool'])
            ->whereBetween('tanggal_kembali', [$start_date, $end_date])
            ->get();

        $pdf = Pdf::loadView('admin.reports.return', compact('returns', 'start_date', 'end_date'));
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }
}





