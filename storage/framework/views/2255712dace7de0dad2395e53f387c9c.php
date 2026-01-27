<?php $__env->startSection('title', 'Detail Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Detail Peminjaman #<?php echo e($borrowing->id); ?></h1>
    
    <div class="card p-6 bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')); ?></p>
                </div>
            </div>
            <?php
                $estimatedFine = $borrowing->status == 'disetujui' ? $borrowing->calculateEstimatedFine() : ['denda' => 0, 'terlambat_hari' => 0];
            ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        <?php if($borrowing->status == 'disetujui'): ?> bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400
                        <?php elseif($borrowing->status == 'menunggu_pengembalian'): ?> bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400
                        <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400
                        <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400
                        <?php else: ?> bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400
                        <?php endif; ?>">
                        <?php if($borrowing->status == 'menunggu_pengembalian'): ?>
                            Menunggu Persetujuan
                        <?php else: ?>
                            <?php echo e(ucfirst(str_replace('_', ' ', $borrowing->status))); ?>

                        <?php endif; ?>
                    </span>
                </div>
                <?php if($borrowing->status == 'disetujui'): ?>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Estimasi Denda</p>
                        <?php if($estimatedFine['denda'] > 0): ?>
                            <p class="font-medium text-lg text-red-600 dark:text-red-400">Rp <?php echo e(number_format($estimatedFine['denda'], 0, ',', '.')); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($estimatedFine['terlambat_hari']); ?> hari terlambat</p>
                        <?php else: ?>
                            <p class="font-medium text-green-600 dark:text-green-400">Rp 0</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tidak ada denda</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if($borrowing->status == 'disetujui' && isset($estimatedFine) && $estimatedFine['denda'] > 0): ?>
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-red-600 dark:text-red-400 mb-1">Peringatan Denda</p>
                            <p class="text-sm text-red-500 dark:text-red-300">Peminjaman Anda telah melewati tanggal jatuh tempo. Segera kembalikan alat untuk menghentikan penambahan denda. Denda akan terus bertambah setiap harinya.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Alat yang Dipinjam</p>
                <div class="space-y-3">
                    <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-4 p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            
                            <div class="flex-shrink-0">
                                <?php if($detail->tool->gambar): ?>
                                    <img src="<?php echo e(asset($detail->tool->gambar)); ?>" 
                                         alt="<?php echo e($detail->tool->nama_alat); ?>" 
                                         class="w-10 h-10 object-cover rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center border-2 border-gray-200 dark:border-gray-700">
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1"><?php echo e($detail->tool->nama_alat); ?></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($detail->tool->category->nama_kategori); ?></p>
                            </div>
                            
                            
                            <div class="flex-shrink-0">
                                <div class="bg-blue-50 dark:bg-blue-500/20 border border-blue-200 dark:border-blue-500/30 rounded-lg px-4 py-2 text-center">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-1">Jumlah</p>
                                    <p class="text-lg font-bold text-blue-700 dark:text-blue-300"><?php echo e($detail->jumlah); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php if($borrowing->keterangan): ?>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Keterangan</p>
                    <p class="font-medium text-gray-900 dark:text-white"><?php echo e($borrowing->keterangan); ?></p>
                </div>
            <?php endif; ?>
            <?php if($borrowing->return): ?>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Informasi Pengembalian</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Kembali</p>
                            <p class="font-medium text-gray-900 dark:text-white"><?php echo e($borrowing->return->tanggal_kembali->format('d/m/Y')); ?></p>
                        </div>
                        <?php
                            $totalDenda = $borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0);
                        ?>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Denda Dibayar</p>
                            <p class="font-medium text-lg text-red-600 dark:text-red-400">Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></p>
                            <?php if($borrowing->return->denda > 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Keterlambatan: Rp <?php echo e(number_format($borrowing->return->denda, 0, ',', '.')); ?>

                                    <?php if($borrowing->return->terlambat_hari > 0): ?>
                                        (<?php echo e($borrowing->return->terlambat_hari); ?> hari)
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if(($borrowing->return->denda_kerusakan ?? 0) > 0): ?>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Kerusakan: Rp <?php echo e(number_format($borrowing->return->denda_kerusakan ?? 0, 0, ',', '.')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-6">
        <?php if($borrowing->status == 'disetujui'): ?>
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/30 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-300">Klik tombol di bawah untuk mengajukan pengembalian. Petugas akan memeriksa kondisi alat dan menentukan denda jika ada kerusakan.</p>
            </div>
        <?php elseif($borrowing->status == 'menunggu_pengembalian'): ?>
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/30 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-300 font-semibold">Pengembalian Anda sedang menunggu persetujuan petugas. Petugas akan memeriksa kondisi alat dan menentukan denda jika ada kerusakan.</p>
            </div>
        <?php endif; ?>
        
        <div class="flex gap-3">
            <a href="<?php echo e(route('peminjam.borrowings.index')); ?>" class="px-5 py-2.5 bg-transparent border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                Kembali
            </a>
            <?php if($borrowing->status == 'disetujui' || $borrowing->status == 'menunggu_pengembalian' || $borrowing->status == 'dikembalikan'): ?>
                <a href="<?php echo e(route('peminjam.borrowings.print', $borrowing)); ?>" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-all flex items-center gap-2 text-sm shadow-lg shadow-blue-600/20">
                    <span class="material-symbols-outlined text-[20px]">print</span>
                    Cetak Bukti
                </a>
            <?php endif; ?>
            <?php if($borrowing->status == 'disetujui'): ?>
                <form method="POST" action="<?php echo e(route('peminjam.borrowings.return', $borrowing)); ?>" class="return-form" data-id="<?php echo e($borrowing->id); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="button" onclick="handleReturnBorrowing(this)" class="px-5 py-2.5 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all flex items-center gap-2 text-sm shadow-lg shadow-green-600/20">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        Kembalikan Alat
                    </button>
                </form>
            <?php endif; ?>
        </div>
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


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/peminjam/borrowings/show.blade.php ENDPATH**/ ?>