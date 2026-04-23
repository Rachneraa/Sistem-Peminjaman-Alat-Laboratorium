<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Tool;
use App\Models\ReturnModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman pilih laporan
     */
    public function index(Request $request)
    {
        $startDateInput = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDateInput = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $start_date = Carbon::parse($startDateInput)->startOfDay();
        $end_date = Carbon::parse($endDateInput)->endOfDay();

        // Swap tanggal jika start_date lebih besar dari end_date (filter maju mundur)
        if ($start_date > $end_date) {
            [$start_date, $end_date] = [$end_date, $start_date];
            session()->flash('info', 'Tanggal dibalikkan secara otomatis karena tanggal mulai lebih besar dari tanggal selesai.');
        }

        // ===== TAB PERSEWAAN (Laporan Keuangan) - Semua peminjaman (belum & sudah kembali) =====
        $returns = Borrowing::with(['user', 'borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->paginate(10);

        $totalTransactions = $returns->count();
        $totalLateFine = $returns->sum(function ($b) {
            return $b->return?->denda ?? 0;
        });
        $totalDamageFine = $returns->sum(function ($b) {
            return $b->return?->denda_kerusakan ?? 0;
        });

        // ===== TAB BARANG (Laporan Alat) =====
        $borrowings = Borrowing::with(['borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->get();

        // Get all tools dengan condition breakdown
        $allTools = \App\Models\Tool::all();

        $toolsCollection = $allTools->map(function ($tool) use ($borrowings, $start_date, $end_date) {
            // Count borrowing details untuk tool ini dalam periode
            $toolBorrowings = $borrowings->flatMap(function ($b) {
                return $b->borrowingDetails;
            })
                ->where('tool_id', $tool->id);

            // Breakdown kondisi langsung dari stok alat
            $goodCondition = $tool->stok ?? 0;
            $needRepair = $tool->stok_perbaikan ?? 0;
            $broken = $tool->stok_rusak ?? 0;

            // Total stock alat di sistem (total fungsional & non fungsional)
            $totalStock = $goodCondition + $needRepair + $broken;

            // Unique peminjam dalam periode (bisa tetap dipakai jika ingin info ini)
            $uniqueBorrowers = $borrowings->filter(function ($b) use ($tool) {
                return $b->borrowingDetails->where('tool_id', $tool->id)->count() > 0;
            })->pluck('user_id')->unique()->count();

            return [
                'id' => $tool->id,
                'nama_alat' => $tool->nama_alat,
                'kondisi_baik' => $goodCondition,
                'kondisi_perbaikan' => $needRepair,
                'kondisi_rusak' => $broken,
                'persediaan' => $totalStock,
                'banyak_peminjam' => $uniqueBorrowers,
            ];
        });

        // Paginate tools collection
        $page = request('tools_page', 1);
        $per_page = 10;
        $tools = new \Illuminate\Pagination\Paginator(
            $toolsCollection->forPage($page, $per_page)->values(),
            $per_page,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $totalTools = $toolsCollection->count();
        $totalBadCondition = $toolsCollection->sum('kondisi_rusak');
        $totalNeedRepair = $toolsCollection->sum('kondisi_perbaikan');

        return view('admin.reports.index', compact(
            'start_date',
            'end_date',
            'returns',
            'totalTransactions',
            'totalLateFine',
            'totalDamageFine',
            'tools',
            'totalTools',
            'totalBadCondition',
            'totalNeedRepair'
        ));
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

    /**
     * Laporan Keuangan (Financial)
     */
    public function financialReport(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $end_date = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));

        $isReverseRange = $start_date->greaterThan($end_date);
        $fromDate = $isReverseRange ? $end_date->copy() : $start_date->copy();
        $toDate = $isReverseRange ? $start_date->copy() : $end_date->copy();
        $orderDirection = $isReverseRange ? 'desc' : 'asc';

        $returns = Borrowing::with(['user', 'borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('tanggal_pinjam', $orderDirection)
            ->get();

        $totalFine = $returns->sum(function ($b) {
            return ($b->return?->denda ?? 0) + ($b->return?->denda_kerusakan ?? 0);
        });

        $display_start_date = $start_date->toDateString();
        $display_end_date = $end_date->toDateString();

        $pdf = Pdf::loadView('admin.reports.financial', compact(
            'returns',
            'totalFine',
            'start_date',
            'end_date',
            'display_start_date',
            'display_end_date',
            'orderDirection'
        ));
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Barang (Goods)
     */
    public function goodsReport(Request $request)
    {
        $start_date = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $end_date = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));

        $isReverseRange = $start_date->greaterThan($end_date);
        $fromDate = $isReverseRange ? $end_date->copy() : $start_date->copy();
        $toDate = $isReverseRange ? $start_date->copy() : $end_date->copy();
        $orderDirection = $isReverseRange ? 'desc' : 'asc';

        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('tanggal_pinjam', $orderDirection)
            ->get();

        $allTools = \App\Models\Tool::all();
        $tools = $allTools->map(function ($tool) use ($borrowings) {
            // Breakdown kondisi langsung dari stok alat
            $goodCondition = $tool->stok ?? 0;
            $needRepair = $tool->stok_perbaikan ?? 0;
            $broken = $tool->stok_rusak ?? 0;

            // Total stock alat di sistem
            $totalStock = $goodCondition + $needRepair + $broken;

            // Unique peminjam dalam periode
            $uniqueBorrowers = $borrowings->filter(function ($b) use ($tool) {
                return $b->borrowingDetails->where('tool_id', $tool->id)->count() > 0;
            })->pluck('user_id')->unique()->count();

            return [
                'id' => $tool->id,
                'nama_alat' => $tool->nama_alat,
                'kondisi_baik' => $goodCondition,
                'kondisi_perbaikan' => $needRepair,
                'kondisi_rusak' => $broken,
                'persediaan' => $totalStock,
                'banyak_peminjam' => $uniqueBorrowers,
            ];
        });

        $display_start_date = $start_date->toDateString();
        $display_end_date = $end_date->toDateString();

        $pdf = Pdf::loadView('admin.reports.goods', compact(
            'tools',
            'start_date',
            'end_date',
            'display_start_date',
            'display_end_date',
            'orderDirection'
        ));
        return $pdf->download('laporan-barang-' . date('Y-m-d') . '.pdf');
    }
}





