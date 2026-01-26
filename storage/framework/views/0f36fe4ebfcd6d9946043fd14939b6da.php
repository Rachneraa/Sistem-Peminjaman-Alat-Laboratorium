<?php $__env->startSection('title', 'Detail Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Detail Alat</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.tools.index')); ?>" class="px-5 py-2.5 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all flex items-center gap-2 font-medium">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Kembali
            </a>
            <a href="<?php echo e(route('admin.tools.edit', $tool)); ?>" class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 font-medium shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[20px]">edit</span>
                Edit Alat
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Main Info Card -->
        <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border md:col-span-2">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-wide mb-1"><?php echo e($tool->nama_alat); ?></h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                        <?php if($tool->status == 'tersedia'): ?> bg-green-500/10 text-green-400 border border-green-500/20
                        <?php elseif($tool->status == 'rusak'): ?> bg-red-500/10 text-red-400 border border-red-500/20
                        <?php elseif($tool->status == 'dipinjam'): ?> bg-blue-500/10 text-blue-400 border border-blue-500/20
                        <?php else: ?> bg-yellow-500/10 text-yellow-400 border border-yellow-500/20
                        <?php endif; ?>">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        <?php echo e(ucfirst($tool->status ?? 'tersedia')); ?>

                    </span>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Total Stok</p>
                    <p class="text-3xl font-bold text-primary"><?php echo e($tool->stok + $tool->stok_rusak + $tool->stok_perbaikan); ?></p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-background-dark p-4 rounded-lg border border-gray-700/50">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Tersedia</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($tool->stok); ?></p>
                </div>
                <div class="bg-background-dark p-4 rounded-lg border border-gray-700/50">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Rusak</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($tool->stok_rusak); ?></p>
                </div>
                <div class="bg-background-dark p-4 rounded-lg border border-gray-700/50">
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Perbaikan</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($tool->stok_perbaikan); ?></p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-3">Deskripsi</p>
                <div class="text-gray-300 text-sm leading-relaxed whitespace-pre-line bg-background-dark p-4 rounded-lg border border-gray-700/50 min-h-[100px]">
                    <?php echo e($tool->deskripsi ?? 'Tidak ada deskripsi.'); ?>

                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Kategori</p>
                    <p class="text-white font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">category</span>
                        <?php echo e($tool->category->nama_kategori); ?>

                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Terakhir Diupdate</p>
                    <p class="text-white font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[18px]">update</span>
                        <?php echo e($tool->updated_at->format('d M Y, H:i')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Image Card -->
        <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border flex flex-col items-center justify-center">
             <?php if($tool->gambar): ?>
                <img src="<?php echo e(asset($tool->gambar)); ?>" alt="<?php echo e($tool->nama_alat); ?>" class="w-full h-auto object-contain rounded-lg shadow-lg border border-gray-700/50">
            <?php else: ?>
                <div class="w-full aspect-square bg-background-dark rounded-lg flex flex-col items-center justify-center border border-gray-700 border-dashed">
                    <span class="material-symbols-outlined text-gray-600 text-[64px] mb-2">image_not_supported</span>
                    <p class="text-gray-500 text-sm">Tidak ada gambar</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Borrowing History -->
    <div class="bg-panel-dark border border-white/5 rounded-xl overflow-hidden industrial-border">
        <div class="p-6 border-b border-gray-700/50">
            <h3 class="text-lg font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Riwayat Peminjaman
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-300 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $tool->borrowingDetails->sortByDesc('borrowing.created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-primary font-medium">#<?php echo e($detail->borrowing->id); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white"><?php echo e($detail->borrowing->user->name); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-white font-bold"><?php echo e($detail->jumlah); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300"><?php echo e($detail->borrowing->tanggal_pinjam->format('d/m/Y')); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                    <?php if($detail->borrowing->status == 'disetujui'): ?> bg-green-500/20 text-green-400
                                    <?php elseif($detail->borrowing->status == 'dikembalikan'): ?> bg-blue-500/20 text-blue-400
                                    <?php elseif($detail->borrowing->status == 'ditolak'): ?> bg-red-500/20 text-red-400
                                    <?php else: ?> bg-yellow-500/20 text-yellow-400
                                    <?php endif; ?>">
                                    <?php echo e($detail->borrowing->status); ?>

                                </span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8">
                                <div class="text-center">
                                    <span class="material-symbols-outlined text-gray-600 text-[48px] mb-2">history_toggle_off</span>
                                    <p class="text-gray-400 font-medium">Belum ada riwayat peminjaman</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/tools/show.blade.php ENDPATH**/ ?>