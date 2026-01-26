<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan log aktivitas
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by aktivitas
        if ($request->filled('search')) {
            $query->where('aktivitas', 'like', "%{$request->search}%");
        }

        $logs = $query->latest()->paginate(20)->withQueryString();
        $users = \App\Models\User::select('id', 'name')->orderBy('name')->get();
        return view('admin.activity-logs.index', compact('logs', 'users'));
    }
}

