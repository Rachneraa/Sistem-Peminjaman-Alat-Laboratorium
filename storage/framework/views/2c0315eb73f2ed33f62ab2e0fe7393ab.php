<?php $__env->startSection('title', 'Dashboard Peminjam'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Dashboard Peminjam</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Sedang Dipinjam -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border relative group overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-[64px] text-blue-500">pending_actions</span>
        </div>
        <div class="relative z-10">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-1">Sedang Dipinjam</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['peminjaman_aktif']); ?></h3>
            <div class="h-1 w-full bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 w-1/2"></div>
            </div>
        </div>
    </div>

    <!-- Menunggu Persetujuan -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border relative group overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-[64px] text-yellow-500">assignment_late</span>
        </div>
        <div class="relative z-10">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-1">Menunggu Persetujuan</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['pending_borrowings']); ?></h3>
            <div class="h-1 w-full bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-500 w-1/3"></div>
            </div>
        </div>
    </div>

    <!-- Sudah Dikembalikan -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border relative group overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-[64px] text-green-500">check_circle</span>
        </div>
        <div class="relative z-10">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-1">Sudah Dikembalikan</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['returned_borrowings']); ?></h3>
            <div class="h-1 w-full bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 w-3/4"></div>
            </div>
        </div>
    </div>

    <!-- Terlambat -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border relative group overflow-hidden">
        <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
            <span class="material-symbols-outlined text-[64px] text-red-500">warning</span>
        </div>
        <div class="relative z-10">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest mb-1">Terlambat</p>
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($stats['overdue_count']); ?></h3>
            <div class="h-1 w-full bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="h-full bg-red-500 w-full"></div>
            </div>
        </div>
    </div>
</div>

<!-- Overdue Alert -->
<?php if($stats['overdue_count'] > 0): ?>
    <div class="bg-white dark:bg-panel-dark rounded-xl shadow-lg border-l-4 border-red-500 p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-500/10 rounded-full">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">⚠️ Peminjaman Terlambat!</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">
                    Ada <strong class="text-red-500 dark:text-red-400"><?php echo e($stats['overdue_count']); ?></strong> peminjaman yang terlambat.<br>
                    <strong>Segera kembalikan!!</strong>
                </p>
                <a href="<?php echo e(route('peminjam.borrowings.index')); ?>" class="text-blue-500 dark:text-blue-400 hover:text-blue-400 dark:hover:text-blue-300 text-sm inline-flex items-center gap-1">
                    Lihat Semua Peminjaman →
                </a>
            </div>
        </div>
    </div>
<?php elseif($nearest_due): ?>
    <?php
        $daysUntilDue = now()->diffInDays($nearest_due->jatuh_tempo, false);
        $isDueToday = $daysUntilDue == 0;
    ?>
    
    <div class="card p-6 mb-6 border-l-4 <?php echo e($isDueToday ? 'border-orange-500 bg-orange-500/5' : 'border-yellow-500 bg-yellow-500/5'); ?>">
        <div class="flex items-start gap-4">
            <div class="p-3 <?php echo e($isDueToday ? 'bg-orange-500/10' : 'bg-yellow-500/10'); ?> rounded-full">
                <svg class="w-6 h-6 <?php echo e($isDueToday ? 'text-orange-500' : 'text-yellow-500'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                    <?php if($isDueToday): ?>
                        ⚠️ Kembalikan Hari Ini!
                    <?php else: ?>
                        ⏰ Jatuh Tempo Terdekat
                    <?php endif; ?>
                </h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    Peminjaman #<?php echo e($nearest_due->id); ?> - 
                    Jatuh tempo: <strong><?php echo e($nearest_due->jatuh_tempo->format('d/m/Y')); ?></strong>
                    <?php if($isDueToday): ?>
                        <span class="text-orange-500 dark:text-orange-400">(Hari ini)</span>
                    <?php else: ?>
                        <span class="text-yellow-600 dark:text-yellow-400">(<?php echo e(abs(round($daysUntilDue))); ?> hari lagi)</span>
                    <?php endif; ?>
                </p>
                <a href="<?php echo e(route('peminjam.borrowings.show', $nearest_due)); ?>" class="text-blue-400 hover:text-blue-300 text-sm mt-2 inline-flex items-center gap-1">
                    Lihat Detail →
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Alat Tersedia -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700/50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Alat Tersedia</h2>
            <a href="<?php echo e(route('peminjam.tools.index')); ?>" class="text-sm text-primary hover:text-primary/80 font-bold uppercase tracking-wide inline-flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-4">
                <?php $__empty_1 = true; $__currentLoopData = $available_tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/50 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 transition flex items-center gap-4">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white"><?php echo e($tool->nama_alat); ?></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($tool->category->nama_kategori); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Stok: <?php echo e($tool->stok_tersedia); ?></p>
                            <div class="flex gap-2 mt-2">
                                <?php if($tool->status == 'tersedia' && $tool->stok_tersedia > 0): ?>
                                    <a href="<?php echo e(route('peminjam.borrowings.create', ['tool_id' => $tool->id])); ?>" class="px-3 py-1.5 bg-green-600 hover:bg-green-500 text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 transition-all shadow-sm shadow-green-600/20">
                                        <span class="material-symbols-outlined text-[14px]">add</span>
                                        Pinjam
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('peminjam.tools.show', $tool)); ?>" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-white rounded-lg text-xs font-medium inline-flex items-center gap-1 transition-all">
                                    <span class="material-symbols-outlined text-[14px]">visibility</span>
                                    Detail
                                </a>
                            </div>
                        </div>
                        <?php if($tool->gambar): ?>
                            <div class="flex-shrink-0">
                                <img src="<?php echo e(asset($tool->gambar)); ?>" alt="<?php echo e($tool->nama_alat); ?>" class="w-12 h-12 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                            </div>
                        <?php else: ?>
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-200 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada alat tersedia</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Peminjaman Saya -->
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700/50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Peminjaman Saya</h2>
            <a href="<?php echo e(route('peminjam.borrowings.index')); ?>" class="text-sm text-primary hover:text-primary/80 font-bold uppercase tracking-wide inline-flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        <div class="p-6">
            <?php $__empty_1 = true; $__currentLoopData = $my_borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700/50 rounded-lg hover:border-gray-300 dark:hover:border-gray-600 transition">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white">#<?php echo e($borrowing->id); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></p>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded uppercase tracking-wider border
                            <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-500 border-green-500/20
                            <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-500 border-red-500/20
                            <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-500 border-blue-500/20
                            <?php else: ?> bg-yellow-500/10 text-yellow-500 border-yellow-500/20
                            <?php endif; ?>">
                            <?php echo e(ucfirst($borrowing->status)); ?>

                        </span>
                    </div>
                    <div class="mb-2">
                        <p class="text-sm font-medium mb-1 text-gray-600 dark:text-gray-300">Alat:</p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400">
                            <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>- <?php echo e($detail->tool->nama_alat); ?> (<?php echo e($detail->jumlah); ?>)</li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <a href="<?php echo e(route('peminjam.borrowings.show', $borrowing)); ?>" class="text-primary hover:text-primary/80 text-sm inline-flex items-center gap-1 font-bold uppercase tracking-wide transition-colors">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        Detail
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada peminjaman</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl industrial-border p-6">
    <a href="<?php echo e(route('peminjam.borrowings.create')); ?>" class="btn btn-primary text-white inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Ajukan Peminjaman Baru
    </a>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/peminjam/dashboard.blade.php ENDPATH**/ ?>