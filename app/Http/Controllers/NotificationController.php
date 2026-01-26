<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tandai notifikasi sebagai dibaca
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['status_baca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Hapus notifikasi
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }
}





