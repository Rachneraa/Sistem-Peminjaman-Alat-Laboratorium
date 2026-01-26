<?php $__env->startSection('title', 'Manajemen Pengembalian'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Manajemen Pengembalian</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    <div class="flex flex-wrap gap-3 w-full md:w-auto">
        <a href="<?php echo e(route('admin.returns.create')); ?>" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Pengembalian
        </a>
        <a href="<?php echo e(route('admin.reports.return')); ?>" class="flex-1 md:flex-none justify-center px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-green-600/20">
            <span class="material-symbols-outlined text-[20px]">print</span>
            Cetak Laporan
        </a>
    </div>
</div>

<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<!-- Filter & Pencarian -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="<?php echo e(route('admin.returns.index')); ?>" class="flex flex-wrap gap-4">
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
            <a href="<?php echo e(route('admin.returns.index')); ?>" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <div class="overflow-auto max-h-[75vh]">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Kembali</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">#<?php echo e($return->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($return->borrowing->user->name); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($return->tanggal_kembali->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <ul class="list-disc list-inside text-gray-600 dark:text-gray-300">
                                <?php $__currentLoopData = $return->borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($detail->tool->nama_alat); ?> (<?php echo e($detail->jumlah); ?>)</li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            <?php
                                $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                            ?>
                            <span class="font-semibold <?php echo e($totalDenda > 0 ? 'text-red-400' : 'text-green-400'); ?>">Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col gap-2">
                                <a href="<?php echo e(route('admin.returns.show', $return)); ?>" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    Detail
                                </a>
                                <a href="<?php echo e(route('admin.returns.edit', $return)); ?>" class="text-primary hover:text-primary/80 inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                    Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.returns.destroy', $return)); ?>" class="inline delete-form" data-id="<?php echo e($return->id); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" onclick="handleDeleteReturn(this)" class="text-red-400 hover:text-red-300 inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4">
                            <div class="text-center py-12">
                                <span class="material-symbols-outlined text-gray-600 text-[64px] mb-4">assignment_return</span>
                                <p class="text-gray-400 text-lg font-medium">Tidak ada data</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
        <?php echo e($returns->links('vendor.pagination.industrial')); ?>

    </div>
</div>

<script>
function handleDeleteReturn(button) {
    const form = button.closest('form');
    const returnId = form.dataset.id;
    
    showConfirmModal({
        title: 'Hapus Pengembalian',
        message: `Yakin ingin menghapus pengembalian #${returnId}? Tindakan ini akan mengembalikan status peminjaman menjadi disetujui.`,
        type: 'warning',
        okText: 'Ya, Hapus',
        onConfirm: function() {
            form.submit();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/returns/index.blade.php ENDPATH**/ ?>