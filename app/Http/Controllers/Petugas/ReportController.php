<?php

namespace App\Http\Controllers\Petugas;

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
     * Halaman form cetak laporan
     */
    public function index(Request $request)
    {
        $startDateInput = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDateInput = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $start_date = is_string($startDateInput)
            ? Carbon::parse($startDateInput)->startOfDay()
            : $startDateInput;
        $end_date = is_string($endDateInput)
            ? Carbon::parse($endDateInput)->endOfDay()
            : $endDateInput;

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
        $allTools = Tool::all();

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

        return view('petugas.reports.index', compact(
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
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

        // Parse tanggal jika string
        if (is_string($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (is_string($end_date)) {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        // Swap tanggal jika start_date lebih besar dari end_date (filter maju mundur)
        if ($start_date > $end_date) {
            [$start_date, $end_date] = [$end_date, $start_date];
        }

        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->get();

        $pdf = Pdf::loadView('petugas.reports.borrowing', compact('borrowings', 'start_date', 'end_date'));
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

        // Swap tanggal jika start_date lebih besar dari end_date (filter maju mundur)
        if ($start_date > $end_date) {
            [$start_date, $end_date] = [$end_date, $start_date];
        }

        $returns = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool'])
            ->whereBetween('tanggal_kembali', [$start_date, $end_date])
            ->whereBetween('tanggal_kembali', [$start_date, $end_date])
            ->get();

        $pdf = Pdf::loadView('petugas.reports.return', compact('returns', 'start_date', 'end_date'));
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Keuangan (Financial)
     */
    public function financialReport(Request $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

        if (is_string($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (is_string($end_date)) {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        // Swap tanggal jika start_date lebih besar dari end_date (filter maju mundur)
        if ($start_date > $end_date) {
            [$start_date, $end_date] = [$end_date, $start_date];
        }

        $returns = Borrowing::with(['user', 'borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
            ->get();

        $totalFine = $returns->sum(function ($b) {
            return ($b->return?->denda ?? 0) + ($b->return?->denda_kerusakan ?? 0);
        });

        $pdf = Pdf::loadView('admin.reports.financial', compact('returns', 'totalFine', 'start_date', 'end_date'));
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Laporan Barang (Goods)
     */
    public function goodsReport(Request $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth());
        $end_date = $request->input('end_date', now()->endOfMonth());

        if (is_string($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
        }
        if (is_string($end_date)) {
            $end_date = Carbon::parse($end_date)->endOfDay();
        }

        // Swap tanggal jika start_date lebih besar dari end_date (filter maju mundur)
        if ($start_date > $end_date) {
            [$start_date, $end_date] = [$end_date, $start_date];
        }

        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool', 'return'])
            ->whereBetween('tanggal_pinjam', [$start_date, $end_date])
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

        $pdf = Pdf::loadView('admin.reports.goods', compact('tools', 'start_date', 'end_date'));
        return $pdf->download('laporan-barang-' . date('Y-m-d') . '.pdf');
    }
}





