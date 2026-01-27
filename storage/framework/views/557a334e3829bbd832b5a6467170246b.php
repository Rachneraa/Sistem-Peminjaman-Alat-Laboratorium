

<?php $__env->startSection('title', 'Edit Pengembalian'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Edit Pengembalian #<?php echo e($return->id); ?></h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Borrowing Info -->
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">info</span>
                Informasi Peminjaman
            </h3>
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">Peminjam</p>
                    <p class="text-gray-900 dark:text-white font-medium text-lg"><?php echo e($return->borrowing->user->name); ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-widest font-bold mb-1">ID Peminjaman</p>
                    <p class="text-gray-600 dark:text-gray-300 font-mono">#<?php echo e($return->borrowing->id); ?></p>
                </div>
            </div>
        </div>

        <!-- Borrowed Tools -->
        <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">construction</span>
                Alat yang Dikembalikan
            </h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $return->borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-background-dark rounded-lg border border-gray-200 dark:border-gray-700/50">
                        <span class="text-sm text-gray-900 dark:text-gray-200 font-medium"><?php echo e($detail->tool->nama_alat); ?></span>
                        <span class="text-xs font-bold text-primary px-2 py-1 bg-primary/10 rounded border border-primary/20"><?php echo e($detail->jumlah); ?> Unit</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
        <form method="POST" action="<?php echo e(route('admin.returns.update', $return)); ?>" id="returnForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali <span class="text-red-400">*</span></label>
                <input type="date" name="tanggal_kembali" value="<?php echo e(old('tanggal_kembali', $return->tanggal_kembali->format('Y-m-d'))); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Keterlambatan (Rp)</label>
                <input type="number" name="denda" min="0" step="1000" value="<?php echo e(old('denda', $return->denda)); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
                <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-2 uppercase tracking-wide">Denda untuk keterlambatan pengembalian.</p>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Hari Terlambat</label>
                <input type="number" name="terlambat_hari" min="0" value="<?php echo e(old('terlambat_hari', $return->terlambat_hari)); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                <input type="number" name="denda_kerusakan" min="0" step="1000" value="<?php echo e(old('denda_kerusakan', $return->denda_kerusakan ?? 0)); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="0">
                <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-2 uppercase tracking-wide">Denda untuk kerusakan alat.</p>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan Kondisi Alat</label>
                <textarea name="keterangan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Catatan kondisi alat, kerusakan yang ditemukan, dll..."><?php echo e(old('keterangan', $return->keterangan)); ?></textarea>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">update</span>
                    Update
                </button>
                <a href="<?php echo e(route('admin.returns.index')); ?>" class="px-6 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/returns/edit.blade.php ENDPATH**/ ?>