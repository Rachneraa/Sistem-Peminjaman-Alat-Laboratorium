<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman
     */
    public function index(Request $request)
    {
        $query = Borrowing::with(['borrowingDetails.tool.category', 'return'])
            ->where('user_id', Auth::id());

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(15);

        return view('peminjam.history.index', compact('borrowings'));
    }
}





