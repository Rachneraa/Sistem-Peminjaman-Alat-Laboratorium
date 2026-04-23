<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\DatabaseBackupController;
use App\Http\Controllers\Admin\DendaSettingController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Petugas\BorrowingController as PetugasBorrowingController;
use App\Http\Controllers\Petugas\ReturnController as PetugasReturnController;
use App\Http\Controllers\Peminjam\ToolController as PeminjamToolController;
use App\Http\Controllers\Peminjam\BorrowingController as PeminjamBorrowingController;
use App\Http\Controllers\Peminjam\HistoryController;
use App\Http\Controllers\Peminjam\ReturnController as PeminjamReturnController;
use App\Http\Controllers\Petugas\ReportController as PetugasReportController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Landing Page & Public Routes
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing.index');
Route::get('/alat', [App\Http\Controllers\LandingController::class, 'tools'])->name('landing.tools');
Route::get('/pinjam/{id}', [App\Http\Controllers\LandingController::class, 'pinjam'])->name('landing.pinjam');

// Middleware auth
Route::middleware(['auth'])->group(function () {
    // Notifications (untuk semua user)
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Dashboard routes
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard')->middleware('role:petugas');
    Route::get('/peminjam/dashboard', [DashboardController::class, 'peminjam'])->name('peminjam.dashboard')->middleware('role:peminjam');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::resource('users', UserController::class);
        Route::get('users-export', [App\Http\Controllers\Admin\UserExportImportController::class, 'export'])->name('users.export');
        Route::post('users-import', [App\Http\Controllers\Admin\UserExportImportController::class, 'import'])->name('users.import');
        Route::get('users-template', [App\Http\Controllers\Admin\UserExportImportController::class, 'template'])->name('users.template');

        // Categories
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::get('categories-export', [App\Http\Controllers\Admin\CategoryExportImportController::class, 'export'])->name('categories.export');
        Route::post('categories-import', [App\Http\Controllers\Admin\CategoryExportImportController::class, 'import'])->name('categories.import');
        Route::get('categories-template', [App\Http\Controllers\Admin\CategoryExportImportController::class, 'template'])->name('categories.template');

        // Tools
        Route::resource('tools', ToolController::class);
        Route::get('tools-export', [App\Http\Controllers\Admin\ToolExportImportController::class, 'export'])->name('tools.export');
        Route::post('tools-import', [App\Http\Controllers\Admin\ToolExportImportController::class, 'import'])->name('tools.import');
        Route::get('tools-template', [App\Http\Controllers\Admin\ToolExportImportController::class, 'template'])->name('tools.template');

        // Fine settings
        Route::get('denda', [DendaSettingController::class, 'index'])->name('denda.index');
        Route::get('denda-export', [DendaSettingController::class, 'export'])->name('denda.export');
        Route::post('denda', [DendaSettingController::class, 'update'])->name('denda.update');

        // Database export
        Route::post('database-export', [DatabaseBackupController::class, 'export'])->name('database.export');

        // Borrowings
        Route::resource('borrowings', AdminBorrowingController::class);

        // Returns
        Route::resource('returns', AdminReturnController::class);

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/borrowing', [ReportController::class, 'borrowingReport'])->name('reports.borrowing');
        Route::get('reports/return', [ReportController::class, 'returnReport'])->name('reports.return');
        Route::get('reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('reports/goods', [ReportController::class, 'goodsReport'])->name('reports.goods');
    });

    // Petugas routes
    Route::middleware(['role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
        // Halaman Menyetujui Peminjaman
        Route::get('borrowings', [PetugasBorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('borrowings/{borrowing}', [PetugasBorrowingController::class, 'show'])->name('borrowings.show');
        Route::post('borrowings/{borrowing}/approve', [PetugasBorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::post('borrowings/{borrowing}/reject', [PetugasBorrowingController::class, 'reject'])->name('borrowings.reject');
        Route::post('borrowings/{borrowing}/reminder', [PetugasBorrowingController::class, 'sendReminder'])->name('borrowings.reminder');
        Route::post('borrowings/{borrowing}/fine-notification', [PetugasBorrowingController::class, 'sendFineNotification'])->name('borrowings.fine-notification');
        Route::get('borrowings/{borrowing}/print', [PetugasBorrowingController::class, 'print'])->name('borrowings.print');

        // Halaman Memantau Pengembalian
        Route::get('returns', [PetugasReturnController::class, 'index'])->name('returns.index');
        Route::post('borrowings/{borrowing}/return', [PetugasReturnController::class, 'processReturn'])->name('borrowings.return');

        // Halaman Semua Peminjaman (lengkap)
        Route::get('borrowings-all', [PetugasBorrowingController::class, 'all'])->name('borrowings.all');

        // Halaman Semua Pengembalian (lengkap)
        Route::get('returns-all', [PetugasReturnController::class, 'all'])->name('returns.all');

        // Reports untuk Petugas
        Route::get('reports', [PetugasReportController::class, 'index'])->name('reports.index');
        Route::get('reports/borrowing', [PetugasReportController::class, 'borrowingReport'])->name('reports.borrowing');
        Route::get('reports/return', [PetugasReportController::class, 'returnReport'])->name('reports.return');
        Route::get('reports/financial', [PetugasReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('reports/goods', [PetugasReportController::class, 'goodsReport'])->name('reports.goods');
    });

    // Peminjam routes
    Route::middleware(['role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
        Route::get('tools', [PeminjamToolController::class, 'index'])->name('tools.index');
        Route::get('tools/{tool}', [PeminjamToolController::class, 'show'])->name('tools.show');
        Route::resource('borrowings', PeminjamBorrowingController::class)->except(['edit', 'update', 'destroy']);
        Route::get('history', [HistoryController::class, 'index'])->name('history.index');
        Route::post('borrowings/{borrowing}/return', [PeminjamReturnController::class, 'requestReturn'])->name('borrowings.return');
        Route::get('borrowings/{borrowing}/print', [PeminjamBorrowingController::class, 'print'])->name('borrowings.print');
    });

    // Petugas routes (continued check)
    Route::middleware(['role:petugas,admin'])->prefix('petugas')->name('petugas.')->group(function () {
        // ... existing routes ...
    });
});

