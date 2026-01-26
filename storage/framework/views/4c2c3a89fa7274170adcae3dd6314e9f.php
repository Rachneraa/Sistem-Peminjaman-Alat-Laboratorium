<?php $__env->startSection('title', 'Peminjaman Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Peminjaman Saya</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <a href="<?php echo e(route('peminjam.borrowings.create')); ?>" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Ajukan Peminjaman
    </a>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estimasi Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            <?php $__empty_1 = true; $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#<?php echo e($borrowing->id); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <div><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Selesai: <?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : '-'); ?></div>
                    </td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <?php
                            $estimatedFine = $borrowing->status == 'disetujui' ? $borrowing->calculateEstimatedFine() : ['denda' => 0, 'terlambat_hari' => 0];
                        ?>
                        <?php if($borrowing->status == 'disetujui'): ?>
                            <?php if($estimatedFine['denda'] > 0): ?>
                                <div class="text-red-500 dark:text-red-400 font-semibold">Rp <?php echo e(number_format($estimatedFine['denda'], 0, ',', '.')); ?></div>
                                <div class="text-xs text-red-400 dark:text-red-300"><?php echo e($estimatedFine['terlambat_hari']); ?> hari terlambat</div>
                            <?php else: ?>
                                <span class="text-green-500 dark:text-green-400">Rp 0</span>
                            <?php endif; ?>
                        <?php elseif($borrowing->status == 'dikembalikan' && $borrowing->return): ?>
                            <div class="text-red-500 dark:text-red-400 font-semibold">Rp <?php echo e(number_format($borrowing->return->denda, 0, ',', '.')); ?></div>
                            <?php if($borrowing->return->terlambat_hari > 0): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($borrowing->return->terlambat_hari); ?> hari terlambat</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-gray-400 dark:text-gray-500">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border
                            <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-500 dark:text-green-400 border-green-500/20
                            <?php elseif($borrowing->status == 'menunggu_pengembalian'): ?> bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20
                            <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-500 dark:text-red-400 border-red-500/20
                            <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-500 dark:text-blue-400 border-blue-500/20
                            <?php else: ?> bg-yellow-500/10 text-yellow-500 dark:text-yellow-400 border-yellow-500/20
                            <?php endif; ?>">
                            <?php if($borrowing->status == 'menunggu_pengembalian'): ?>
                                Menunggu
                            <?php else: ?>
                                <?php echo e(ucfirst(str_replace('_', ' ', $borrowing->status))); ?>

                            <?php endif; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col gap-2">
                            <a href="<?php echo e(route('peminjam.borrowings.show', $borrowing)); ?>" class="text-primary hover:text-primary/80 dark:text-blue-400 dark:hover:text-blue-300 inline-flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                Detail
                            </a>
                            <?php if($borrowing->status == 'disetujui'): ?>
                                <form method="POST" action="<?php echo e(route('peminjam.borrowings.return', $borrowing)); ?>" class="inline return-form" data-id="<?php echo e($borrowing->id); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="button" onclick="handleReturnBorrowing(this)" class="text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300 inline-flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">assignment_return</span>
                                        Kembalikan
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12">
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-[64px] mb-4">inbox</span>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">Belum ada peminjaman</p>
                            <p class="text-gray-500 dark:text-gray-500 text-sm mb-4">Mulai dengan mengajukan peminjaman pertama Anda</p>
                            <a href="<?php echo e(route('peminjam.borrowings.create')); ?>" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center justify-center gap-2 text-sm font-medium shadow-lg shadow-primary/20 w-fit mx-auto">
                                <span class="material-symbols-outlined text-[20px]">add</span>
                                Ajukan Peminjaman
                            </a>
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

<script>
function handleReturnBorrowing(button) {
    const form = button.closest('form');
    const borrowingId = form.dataset.id;
    
    showConfirmModal({
        title: 'Kembalikan Alat',
        message: 'Yakin ingin mengembalikan alat? Pastikan semua alat sudah lengkap dan dalam kondisi baik.',
        type: 'success',
        okText: 'Ya, Kembalikan',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/peminjam/borrowings/index.blade.php ENDPATH**/ ?>