<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Tool;
use App\Models\Category;
use App\Models\ReturnModel;
use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Buat notifikasi peminjaman disetujui
     */
    public static function notifyBorrowingApproved(Borrowing $borrowing)
    {
        return Notification::createNotification(
            $borrowing->user_id,
            'peminjaman_disetujui',
            'Peminjaman Disetujui',
            "Peminjaman Anda (#{$borrowing->id}) telah disetujui. Jatuh tempo: " . ($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')),
            $borrowing->id,
            Borrowing::class
        );
    }

    /**
     * Buat notifikasi peminjaman ditolak
     */
    public static function notifyBorrowingRejected(Borrowing $borrowing)
    {
        return Notification::createNotification(
            $borrowing->user_id,
            'peminjaman_ditolak',
            'Peminjaman Ditolak',
            "Peminjaman Anda (#{$borrowing->id}) telah ditolak. " . ($borrowing->keterangan ? "Alasan: {$borrowing->keterangan}" : ''),
            $borrowing->id,
            Borrowing::class
        );
    }

    /**
     * Buat notifikasi peminjaman baru diajukan (untuk petugas)
     */
    public static function notifyNewBorrowingRequest(Borrowing $borrowing)
    {
        // Notifikasi ke semua petugas dan admin
        $petugas = User::whereIn('role', ['petugas', 'admin'])->get();
        
        foreach ($petugas as $user) {
            Notification::createNotification(
                $user->id,
                'peminjaman_baru',
                'Peminjaman Baru',
                "Peminjaman baru (#{$borrowing->id}) dari {$borrowing->user->name} menunggu persetujuan.",
                $borrowing->id,
                Borrowing::class
            );
        }
    }

    /**
     * Buat notifikasi pengembalian alat (untuk peminjam)
     */
    public static function notifyReturnProcessed(ReturnModel $return)
    {
        $borrowing = $return->borrowing;
        $dendaText = $return->denda > 0 
            ? " Denda: Rp " . number_format($return->denda, 0, ',', '.') . " ({$return->terlambat_hari} hari terlambat)"
            : "";

        return Notification::createNotification(
            $borrowing->user_id,
            'pengembalian_diproses',
            'Pengembalian Diproses',
            "Pengembalian peminjaman (#{$borrowing->id}) telah diproses pada " . $return->tanggal_kembali->format('d/m/Y') . ".{$dendaText}",
            $return->id,
            ReturnModel::class
        );
    }

    /**
     * Buat notifikasi pengembalian oleh peminjam (untuk petugas)
     */
    public static function notifyReturnByBorrower(Borrowing $borrowing)
    {
        $petugas = User::whereIn('role', ['petugas', 'admin'])->get();
        
        foreach ($petugas as $user) {
            Notification::createNotification(
                $user->id,
                'pengembalian_peminjam',
                'Pengembalian oleh Peminjam',
                "Peminjaman (#{$borrowing->id}) dari {$borrowing->user->name} telah dikembalikan. Silakan proses pengembalian.",
                $borrowing->id,
                Borrowing::class
            );
        }
    }

    /**
     * Buat notifikasi jatuh tempo
     */
    public static function notifyDueDate(Borrowing $borrowing)
    {
        return Notification::createNotification(
            $borrowing->user_id,
            'jatuh_tempo',
            'Peringatan Jatuh Tempo',
            "Peminjaman Anda (#{$borrowing->id}) akan jatuh tempo pada " . ($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')) . ". Segera kembalikan alat untuk menghindari denda.",
            $borrowing->id,
            Borrowing::class
        );
    }

    /**
     * Buat notifikasi untuk admin - User dibuat/diupdate/dihapus
     */
    public static function notifyUserCreated(User $user, $createdBy)
    {
        $admins = User::where('role', 'admin')->where('id', '!=', $createdBy->id)->get();
        foreach ($admins as $admin) {
            Notification::createNotification(
                $admin->id,
                'user_dibuat',
                'User Baru Dibuat',
                "User baru '{$user->name}' ({$user->email}) telah dibuat oleh {$createdBy->name}.",
                $user->id,
                User::class
            );
        }
    }

    /**
     * Buat notifikasi untuk admin - Alat dibuat/diupdate/dihapus
     */
    public static function notifyToolCreated(Tool $tool, $createdBy)
    {
        $admins = User::where('role', 'admin')->where('id', '!=', $createdBy->id)->get();
        foreach ($admins as $admin) {
            Notification::createNotification(
                $admin->id,
                'alat_dibuat',
                'Alat Baru Dibuat',
                "Alat baru '{$tool->nama_alat}' telah ditambahkan oleh {$createdBy->name}.",
                $tool->id,
                Tool::class
            );
        }
    }

    /**
     * Buat notifikasi untuk admin - Kategori dibuat/diupdate/dihapus
     */
    public static function notifyCategoryCreated(Category $category, $createdBy)
    {
        $admins = User::where('role', 'admin')->where('id', '!=', $createdBy->id)->get();
        foreach ($admins as $admin) {
            Notification::createNotification(
                $admin->id,
                'kategori_dibuat',
                'Kategori Baru Dibuat',
                "Kategori baru '{$category->nama_kategori}' telah ditambahkan oleh {$createdBy->name}.",
                $category->id,
                Category::class
            );
        }
    }

    /**
     * Buat notifikasi pengingat pengembalian dari petugas ke peminjam
     */
    public static function notifyReturnReminder(Borrowing $borrowing, $pesan, $sender)
    {
        $tanggalSelesai = $borrowing->tanggal_selesai 
            ? $borrowing->tanggal_selesai->format('d/m/Y')
            : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-');
            
        $defaultMessage = "Pengingat: Peminjaman Anda (#{$borrowing->id}) akan jatuh tempo pada {$tanggalSelesai}. Segera kembalikan alat untuk menghindari denda.";
        
        $finalMessage = $pesan ?: $defaultMessage;

        return Notification::createNotification(
            $borrowing->user_id,
            'pengingat_pengembalian',
            'Pengingat Pengembalian',
            "Dari {$sender->name}: {$finalMessage}",
            $borrowing->id,
            Borrowing::class
        );
    }

    /**
     * Buat notifikasi estimasi denda dari petugas ke peminjam
     */
    public static function notifyFineEstimation(Borrowing $borrowing, $estimatedFine, $terlambatHari, $sender)
    {
        $tanggalSelesai = $borrowing->tanggal_selesai 
            ? $borrowing->tanggal_selesai->format('d/m/Y')
            : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-');

        $dendaText = $estimatedFine > 0
            ? "Estimasi denda yang harus dibayar: Rp " . number_format($estimatedFine, 0, ',', '.') . " ({$terlambatHari} hari terlambat dari tanggal {$tanggalSelesai})."
            : "Belum ada denda. Jatuh tempo: {$tanggalSelesai}.";

        $message = "Pemberitahuan Denda - Peminjaman #{$borrowing->id}:\n";
        $message .= "{$dendaText}\n";
        $message .= "Segera kembalikan alat untuk menghentikan penambahan denda.";

        return Notification::createNotification(
            $borrowing->user_id,
            'estimasi_denda',
            'Pemberitahuan Estimasi Denda',
            "Dari {$sender->name}: {$message}",
            $borrowing->id,
            Borrowing::class
        );
    }
}

