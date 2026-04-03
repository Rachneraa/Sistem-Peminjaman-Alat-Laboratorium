<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
        $start_date = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $end_date = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));

        $isReverseRange = $start_date->greaterThan($end_date);
        $fromDate = $isReverseRange ? $end_date->copy() : $start_date->copy();
        $toDate = $isReverseRange ? $start_date->copy() : $end_date->copy();
        $orderDirection = $isReverseRange ? 'desc' : 'asc';

        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->whereBetween('tanggal_pinjam', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('tanggal_pinjam', $orderDirection)
            ->get();

        $display_start_date = $start_date->toDateString();
        $display_end_date = $end_date->toDateString();

        $pdf = Pdf::loadView('admin.reports.borrowing', compact(
            'borrowings',
            'display_start_date',
            'display_end_date',
            'orderDirection'
        ));
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan pengembalian
     */
    public function returnReport(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $end_date = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));

        $isReverseRange = $start_date->greaterThan($end_date);
        $fromDate = $isReverseRange ? $end_date->copy() : $start_date->copy();
        $toDate = $isReverseRange ? $start_date->copy() : $end_date->copy();
        $orderDirection = $isReverseRange ? 'desc' : 'asc';

        $returns = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool'])
            ->whereBetween('tanggal_kembali', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('tanggal_kembali', $orderDirection)
            ->get();

        $display_start_date = $start_date->toDateString();
        $display_end_date = $end_date->toDateString();

        $pdf = Pdf::loadView('admin.reports.return', compact(
            'returns',
            'display_start_date',
            'display_end_date',
            'orderDirection'
        ));
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }
}





