<?php $__env->startSection('title', 'Pengembalian Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pengembalian Alat</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="<?php echo e(route('petugas.returns.all')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
             <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari</label>
            <div class="relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama atau email peminjam..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 placeholder-gray-400 dark:placeholder-gray-500 transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-gray-400 dark:text-gray-500 text-[20px]">search</span>
                </div>
            </div>
        </div>
        <div class="flex-1 min-w-[150px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Denda</label>
            <select name="denda" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua</option>
                <option value="ada" <?php echo e(request('denda') == 'ada' ? 'selected' : ''); ?>>Ada Denda</option>
                <option value="tidak_ada" <?php echo e(request('denda') == 'tidak_ada' ? 'selected' : ''); ?>>Tidak Ada Denda</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            <a href="<?php echo e(route('petugas.returns.all')); ?>" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Denda Keterlambatan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Denda Kerusakan</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Denda</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#<?php echo e($return->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($return->borrowing->user->name); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($return->tanggal_kembali->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                <?php $__currentLoopData = $return->borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                        <?php echo e($detail->tool->nama_alat); ?> <span class="text-gray-500 dark:text-gray-400">(<?php echo e($detail->jumlah); ?>)</span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="font-bold <?php echo e($return->denda > 0 ? 'text-yellow-400' : 'text-green-400'); ?>">
                                Rp <?php echo e(number_format($return->denda, 0, ',', '.')); ?>

                            </span>
                            <?php if($return->denda > 0 && $return->terlambat_hari > 0): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($return->terlambat_hari); ?> hari</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="font-bold <?php echo e(($return->denda_kerusakan ?? 0) > 0 ? 'text-orange-400' : 'text-green-400'); ?>">
                                Rp <?php echo e(number_format($return->denda_kerusakan ?? 0, 0, ',', '.')); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php
                                $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                            ?>
                            <span class="font-bold <?php echo e($totalDenda > 0 ? 'text-red-400' : 'text-green-400'); ?>">
                                Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?>

                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <span class="material-symbols-outlined text-gray-600 text-[64px] mb-4">history</span>
                                <p class="text-gray-400 text-lg font-medium">Tidak ada data pengembalian</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="bg-gray-50/50 dark:bg-gray-800/50">
                <?php
                    $totalDendaKeterlambatan = $returns->sum('denda');
                    $totalDendaKerusakan = $returns->sum('denda_kerusakan');
                    $totalKeseluruhan = $totalDendaKeterlambatan + $totalDendaKerusakan;
                ?>
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td colspan="6" class="px-6 py-3 text-right text-xs uppercase tracking-widest font-bold text-gray-500 dark:text-gray-400">Total Denda Keterlambatan:</td>
                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-yellow-500 dark:text-yellow-400">
                        Rp <?php echo e(number_format($totalDendaKeterlambatan, 0, ',', '.')); ?>

                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="px-6 py-3 text-right text-xs uppercase tracking-widest font-bold text-gray-500 dark:text-gray-400">Total Denda Kerusakan:</td>
                    <td class="px-6 py-3 whitespace-nowrap text-sm font-bold text-orange-500 dark:text-orange-400">
                        Rp <?php echo e(number_format($totalDendaKerusakan, 0, ',', '.')); ?>

                    </td>
                </tr>
                <tr class="border-t-2 border-gray-300 dark:border-gray-600 bg-white/5">
                    <td colspan="6" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Total Keseluruhan:</td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-red-600 dark:text-red-500">
                        Rp <?php echo e(number_format($totalKeseluruhan, 0, ',', '.')); ?>

                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        <?php echo e($returns->links('vendor.pagination.industrial')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/petugas/returns/all.blade.php ENDPATH**/ ?>