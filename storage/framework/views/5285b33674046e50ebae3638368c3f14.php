<?php $__env->startSection('title', 'Riwayat Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Riwayat Peminjaman</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="<?php echo e(route('peminjam.history.index')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Status</label>
            <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Status</option>
                <option value="menunggu" <?php echo e(request('status') == 'menunggu' ? 'selected' : ''); ?>>Menunggu</option>
                <option value="disetujui" <?php echo e(request('status') == 'disetujui' ? 'selected' : ''); ?>>Disetujui</option>
                <option value="ditolak" <?php echo e(request('status') == 'ditolak' ? 'selected' : ''); ?>>Ditolak</option>
                <option value="dikembalikan" <?php echo e(request('status') == 'dikembalikan' ? 'selected' : ''); ?>>Dikembalikan</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            <a href="<?php echo e(route('peminjam.history.index')); ?>" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#<?php echo e($borrowing->id); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 dark:bg-gray-500"></span>
                                        <?php echo e($detail->tool->nama_alat); ?> <span class="text-gray-500 dark:text-gray-500">(<?php echo e($detail->jumlah); ?>)</span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')); ?>

                            <?php if($borrowing->tanggal_selesai && $borrowing->status == 'disetujui' && now()->gt($borrowing->tanggal_selesai)): ?>
                                <span class="text-red-500 dark:text-red-400 text-xs block">(Terlambat)</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($borrowing->return ? $borrowing->return->tanggal_kembali->format('d/m/Y') : '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border
                                <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-500 dark:text-green-400 border-green-500/20
                                <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20
                                <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-500 dark:text-blue-400 border-blue-500/20
                                <?php else: ?> bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20
                                <?php endif; ?>">
                                <?php echo e(ucfirst($borrowing->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            <?php if($borrowing->return && $borrowing->return->denda > 0): ?>
                                <span class="text-red-500 dark:text-red-400 font-semibold">Rp <?php echo e(number_format($borrowing->return->denda, 0, ',', '.')); ?></span>
                                <span class="text-gray-500 dark:text-gray-400 text-xs block">(<?php echo e($borrowing->return->terlambat_hari); ?> hari)</span>
                            <?php else: ?>
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('peminjam.borrowings.show', $borrowing)); ?>" class="text-primary hover:text-primary/80 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px] mb-4">history</span>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Tidak ada riwayat peminjaman</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        <?php echo e($borrowings->links('vendor.pagination.industrial')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/peminjam/history/index.blade.php ENDPATH**/ ?>