<?php $__env->startSection('title', 'Detail Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Detail Peminjaman</h1>
            <div class="flex items-center gap-3 mt-2">
                <div class="h-1 w-16 bg-primary"></div>
                <span class="text-sm text-gray-500 dark:text-gray-400 font-mono">#<?php echo e($borrowing->id); ?></span>
            </div>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="<?php echo e(route('admin.borrowings.index')); ?>" class="flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center justify-center gap-2 font-medium">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali
            </a>
            <a href="<?php echo e(route('admin.borrowings.edit', $borrowing)); ?>" class="flex-1 sm:flex-initial px-4 sm:px-5 py-2.5 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition-all flex items-center justify-center gap-2 font-medium shadow-lg shadow-yellow-600/20">
                <span class="material-symbols-outlined text-[20px]">edit</span>
                Edit
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- User Info -->
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-5 sm:p-8 industrial-border">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">person</span>
                Informasi Peminjam
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Nama Lengkap</p>
                    <p class="text-gray-900 dark:text-white font-medium text-lg"><?php echo e($borrowing->user->name); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Email</p>
                    <p class="text-gray-600 dark:text-gray-300 font-medium"><?php echo e($borrowing->user->email); ?></p>
                </div>
            </div>
        </div>

        <!-- Borrowing Status -->
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-5 sm:p-8 industrial-border">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Status Peminjaman
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                            <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-400 border border-green-500/20
                            <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-400 border border-red-500/20
                            <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-400 border border-blue-500/20
                            <?php else: ?> bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                            <?php endif; ?>">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <?php echo e(ucfirst($borrowing->status)); ?>

                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Total Alat</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($borrowing->borrowingDetails->sum('jumlah')); ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="bg-gray-50 dark:bg-background-dark p-3 rounded-lg border border-gray-200 dark:border-gray-700/50">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Mulai</p>
                        <p class="text-gray-900 dark:text-white font-mono"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-background-dark p-3 rounded-lg border border-gray-200 dark:border-gray-700/50">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Selesai</p>
                        <p class="text-gray-900 dark:text-white font-mono"><?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : '-'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tools List -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">construction</span>
                Alat yang Dipinjam
            </h3>
        </div>
        <div class="p-5 sm:p-6 grid gap-4 grid-cols-1 md:grid-cols-2">
            <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-background-dark rounded-xl border border-gray-200 dark:border-gray-700/50 hover:border-primary/30 transition-all group">
                    <div class="flex-shrink-0">
                        <?php if($detail->tool->gambar): ?>
                            <img src="<?php echo e(asset($detail->tool->gambar)); ?>" 
                                 alt="<?php echo e($detail->tool->nama_alat); ?>" 
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-700 group-hover:border-primary/50 transition-colors">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 group-hover:border-primary/50 transition-colors">
                                <span class="material-symbols-outlined text-gray-600 text-3xl">image_not_supported</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1 truncate group-hover:text-primary transition-colors"><?php echo e($detail->tool->nama_alat); ?></h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2"><?php echo e($detail->tool->category->nama_kategori); ?></p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-800 rounded text-[10px] text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700">
                                Stok: <?php echo e($detail->tool->stok); ?>

                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 text-center px-4">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Jumlah</p>
                        <div class="text-2xl font-bold text-primary"><?php echo e($detail->jumlah); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php if($borrowing->return): ?>
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700/50 flex justify-between items-center bg-gray-50 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">assignment_return</span>
                    Informasi Pengembalian
                </h3>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    Sudah Dikembalikan
                </span>
            </div>
            <div class="p-5 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-2">Tanggal Kembali</p>
                        <p class="text-xl font-mono text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 dark:text-gray-500">calendar_today</span>
                            <?php echo e($borrowing->return->tanggal_kembali->format('d/m/Y')); ?>

                        </p>
                    </div>
                    
                    <?php
                        $totalDenda = $borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0);
                    ?>
                    
                    <div class="bg-gray-50 dark:bg-gray-800/50 p-5 sm:p-6 rounded-xl border border-gray-200 dark:border-gray-700/50">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-3">Rincian Denda</p>
                        <div class="space-y-2 mb-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <?php if($borrowing->return->denda > 0): ?>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Keterlambatan <?php if($borrowing->return->terlambat_hari > 0): ?> (<?php echo e($borrowing->return->terlambat_hari); ?> hari) <?php endif; ?></span>
                                    <span class="text-red-500 dark:text-red-400 font-mono">Rp <?php echo e(number_format($borrowing->return->denda, 0, ',', '.')); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if(($borrowing->return->denda_kerusakan ?? 0) > 0): ?>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Kerusakan</span>
                                    <span class="text-red-500 dark:text-red-400 font-mono">Rp <?php echo e(number_format($borrowing->return->denda_kerusakan ?? 0, 0, ',', '.')); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($totalDenda == 0): ?>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-500 italic">Tidak ada denda</span>
                                    <span class="text-gray-500 dark:text-gray-500 font-mono">-</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-between items-end">
                            <span class="text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Denda</span>
                            <span class="text-2xl font-bold text-red-500 font-mono">Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/borrowings/show.blade.php ENDPATH**/ ?>