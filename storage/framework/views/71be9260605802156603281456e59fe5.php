

<?php $__env->startSection('title', 'Cetak Laporan'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Cetak Laporan</h1>
    <div class="h-1 w-16 bg-primary mt-2"></div>
    <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Pilih jenis laporan dan periode tanggal untuk mencetak laporan PDF.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Laporan Peminjaman -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border h-full flex flex-col">
        <div class="flex items-center gap-3 mb-4 border-b border-gray-200 dark:border-gray-700/50 pb-4">
            <span class="material-symbols-outlined text-primary text-[28px]">print</span>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Laporan Peminjaman</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">Data Transaksi Peminjaman</p>
            </div>
        </div>
        
        <div class="flex-1">
            <form method="GET" action="<?php echo e(route('admin.reports.borrowing')); ?>" target="_blank" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="<?php echo e(request('start_date', now()->startOfMonth()->format('Y-m-d'))); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="<?php echo e(request('end_date', now()->endOfMonth()->format('Y-m-d'))); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                
                <div class="pt-4 mt-auto">
                    <button type="submit" class="w-full px-6 py-3 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center justify-center gap-2 shadow-lg shadow-primary/20 group">
                        <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Laporan Pengembalian -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border h-full flex flex-col">
        <div class="flex items-center gap-3 mb-4 border-b border-gray-200 dark:border-gray-700/50 pb-4">
            <span class="material-symbols-outlined text-accent-green text-[28px]">assignment_turned_in</span>
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Laporan Pengembalian</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">Data Transaksi Pengembalian</p>
            </div>
        </div>
        
        <div class="flex-1">
            <form method="GET" action="<?php echo e(route('admin.reports.return')); ?>" target="_blank" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="<?php echo e(request('start_date', now()->startOfMonth()->format('Y-m-d'))); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="<?php echo e(request('end_date', now()->endOfMonth()->format('Y-m-d'))); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                </div>
                
                <div class="pt-4 mt-auto">
                    <button type="submit" class="w-full px-6 py-3 bg-transparent border border-gray-300 dark:border-gray-600 hover:border-accent-green hover:bg-accent-green/5 text-gray-600 dark:text-gray-300 hover:text-accent-green rounded-lg font-medium transition-all flex items-center justify-center gap-2 group">
                        <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">picture_as_pdf</span>
                        Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/reports/index.blade.php ENDPATH**/ ?>