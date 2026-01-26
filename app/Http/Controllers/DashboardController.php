<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tool;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Category;
use App\Models\ReturnModel;
use App\Models\BorrowingDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function admin(Request $request)
    {
        $period = $request->get('period', 'year');
        
        $stats = [
            'total_users' => User::count(),
            'total_tools' => Tool::count(),
            'total_categories' => Category::count(),
            'tools_tersedia' => Tool::where('status', 'tersedia')->sum('stok'),
            'tools_dipinjam' => Tool::where('status', 'dipinjam')->count(),
            'pending_borrowings' => Borrowing::where('status', 'menunggu')->count(),
            'active_borrowings' => Borrowing::where('status', 'disetujui')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'disetujui')
                ->where('jatuh_tempo', '<', now())
                ->count(),
            'total_denda_bulan_ini' => ReturnModel::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->selectRaw('SUM(denda + COALESCE(denda_kerusakan, 0)) as total')
                ->value('total') ?? 0,
            'total_borrowings' => Borrowing::count(),
            'total_returns' => ReturnModel::count(),
            'total_fines' => ReturnModel::selectRaw('SUM(denda + COALESCE(denda_kerusakan, 0)) as total')
                ->value('total') ?? 0,
            'returns_today' => ReturnModel::whereDate('created_at', today())->count(),
        ];

        $recent_borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->latest()
            ->limit(5)
            ->get();

        // Chart Data Logic
        $labels = [];
        $chart_data = [];
        $return_data = [];
        $active_data = [];
        $overdue_data = [];

        if ($period == 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d M');
                
                $chart_data[] = Borrowing::whereDate('tanggal_pinjam', $date->toDateString())->count();
                $return_data[] = ReturnModel::whereDate('created_at', $date->toDateString())->count();
                $active_data[] = Borrowing::whereDate('tanggal_pinjam', $date->toDateString())->where('status', 'disetujui')->count();
                $overdue_data[] = Borrowing::whereDate('jatuh_tempo', $date->toDateString())
                    ->where('jatuh_tempo', '<', now())
                    ->where(function($q) use ($date) {
                        $q->where('status', 'disetujui')
                          ->orWhere(function($sq) use ($date) {
                              $sq->where('status', 'dikembalikan')
                                ->whereHas('return', function($rq) use ($date) {
                                    $rq->whereColumn('created_at', '>', 'borrowings.jatuh_tempo');
                                });
                          });
                    })->count();
            }
        } elseif ($period == 'month') {
            // Days in current month
            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = now()->setDay($i);
                $labels[] = $i;
                
                $chart_data[] = Borrowing::whereDate('tanggal_pinjam', $date->toDateString())->count();
                $return_data[] = ReturnModel::whereDate('created_at', $date->toDateString())->count();
                $active_data[] = Borrowing::whereDate('tanggal_pinjam', $date->toDateString())->where('status', 'disetujui')->count();
                $overdue_data[] = Borrowing::whereDate('jatuh_tempo', $date->toDateString())
                    ->where('jatuh_tempo', '<', now())
                    ->where(function($q) {
                        $q->where('status', 'disetujui')
                          ->orWhere(function($sq) {
                              $sq->where('status', 'dikembalikan')
                                ->whereHas('return', function($rq) {
                                    $rq->whereColumn('created_at', '>', 'borrowings.jatuh_tempo');
                                });
                          });
                    })->count();
            }
        } else {
            // Year (Monthly breakdown)
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            
            $borrowing_chart = Borrowing::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as count')
                ->whereYear('tanggal_pinjam', date('Y'))
                ->groupBy('month')->pluck('count', 'month')->toArray();

            $return_chart = ReturnModel::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')->pluck('count', 'month')->toArray();

            $active_chart = Borrowing::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as count')
                ->whereYear('tanggal_pinjam', date('Y'))
                ->where('status', 'disetujui')
                ->groupBy('month')->pluck('count', 'month')->toArray();

            $overdue_chart = Borrowing::selectRaw('MONTH(jatuh_tempo) as month, COUNT(*) as count')
                ->whereYear('jatuh_tempo', date('Y'))
                ->where('jatuh_tempo', '<', now())
                ->where(function($q) {
                    $q->where('status', 'disetujui')
                      ->orWhere(function($sq) {
                          $sq->where('status', 'dikembalikan')
                            ->whereHas('return', function($rq) {
                                $rq->whereColumn('created_at', '>', 'borrowings.jatuh_tempo');
                            });
                      });
                })
                ->groupBy('month')->pluck('count', 'month')->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $chart_data[] = $borrowing_chart[$i] ?? 0;
                $return_data[] = $return_chart[$i] ?? 0;
                $active_data[] = $active_chart[$i] ?? 0;
                $overdue_data[] = $overdue_chart[$i] ?? 0;
            }
        }

        // Top 10 Alat Paling Sering Dipinjam
        $popular_tools = BorrowingDetail::select('tool_id', DB::raw('count(*) as total'))
            ->groupBy('tool_id')
            ->orderByDesc('total')
            ->with('tool')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'recent_borrowings', 
            'labels',
            'chart_data', 
            'return_data', 
            'active_data', 
            'overdue_data', 
            'popular_tools',
            'period'
        ));
    }

    /**
     * Dashboard Petugas
     */
    public function petugas()
    {
        $stats = [
            'pending_borrowings' => Borrowing::where('status', 'menunggu')->count(),
            'active_borrowings' => Borrowing::where('status', 'disetujui')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'disetujui')
                ->where('jatuh_tempo', '<', now())
                ->count(),
            'borrowed_today' => Borrowing::whereDate('tanggal_pinjam', today())
                ->whereIn('status', ['disetujui', 'dikembalikan'])
                ->withCount('borrowingDetails')
                ->get()
                ->sum('borrowing_details_count'),
        ];

        $pending_borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->where('status', 'menunggu')
            ->latest()
            ->limit(3)
            ->get();

        $active_borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->where('status', 'disetujui')
            ->latest()
            ->limit(3)
            ->get();

        $overdue_borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->where('status', 'disetujui')
            ->where('jatuh_tempo', '<', now())
            ->latest()
            ->get();

        return view('petugas.dashboard', compact('stats', 'pending_borrowings', 'active_borrowings', 'overdue_borrowings'));
    }

    /**
     * Dashboard Peminjam
     */
    public function peminjam()
    {
        $user = Auth::user();
        
        $stats = [
            'total_peminjaman' => Borrowing::where('user_id', $user->id)->count(),
            'peminjaman_aktif' => Borrowing::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->count(),
            'pending_borrowings' => Borrowing::where('user_id', $user->id)
                ->where('status', 'menunggu')
                ->count(),
            'returned_borrowings' => Borrowing::where('user_id', $user->id)
                ->where('status', 'dikembalikan')
                ->count(),
            'overdue_count' => Borrowing::where('user_id', $user->id)
                ->where('status', 'disetujui')
                ->where('jatuh_tempo', '<', now())
                ->count(),
            'total_denda' => ReturnModel::whereHas('borrowing', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->selectRaw('SUM(denda + COALESCE(denda_kerusakan, 0)) as total')
            ->value('total') ?? 0,
        ];
        
        // Get nearest due date from active borrowings
        $nearest_due = Borrowing::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->whereNotNull('jatuh_tempo')
            ->orderBy('jatuh_tempo', 'asc')
            ->first();
        
        $my_borrowings = Borrowing::with(['borrowingDetails.tool', 'return'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $available_tools = Tool::where('status', 'tersedia')
            ->where('stok', '>', 0)
            ->with('category')
            ->latest()
            ->limit(3)
            ->get();

        $notifications = $user->notifications()->unread()->latest()->limit(5)->get();

        return view('peminjam.dashboard', compact('stats', 'my_borrowings', 'available_tools', 'notifications', 'nearest_due'));
    }
}

