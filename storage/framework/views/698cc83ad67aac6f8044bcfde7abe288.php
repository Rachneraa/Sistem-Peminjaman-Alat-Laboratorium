<?php $__env->startSection('title', 'Menyetujui Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Menyetujui Peminjaman</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Filter -->
<!-- Filter -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="<?php echo e(route('petugas.borrowings.index')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Status</label>
            <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua (Menunggu, Disetujui & Aktif)</option>
                <option value="menunggu" <?php echo e(request('status') == 'menunggu' ? 'selected' : ''); ?>>Menunggu Persetujuan</option>
                <option value="disetujui" <?php echo e(request('status') == 'disetujui' ? 'selected' : ''); ?>>Sudah Disetujui</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            <a href="<?php echo e(route('petugas.borrowings.index')); ?>" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table Peminjaman -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl overflow-hidden industrial-border">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Mulai</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Selesai</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alat</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#<?php echo e($borrowing->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="font-bold"><?php echo e($borrowing->user->name); ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($borrowing->user->email); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : '-'); ?>

                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <ul class="text-gray-600 dark:text-gray-300 space-y-1">
                                <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                        <?php echo e($detail->tool->nama_alat); ?> <span class="text-gray-500 dark:text-gray-400">(<?php echo e($detail->jumlah); ?>)</span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border
                                <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-400 border-green-500/20
                                <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-400 border-red-500/20
                                <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-400 border-blue-500/20
                                <?php else: ?> bg-yellow-500/10 text-yellow-400 border-yellow-500/20
                                <?php endif; ?>">
                                <?php echo e(ucfirst($borrowing->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col gap-2">
                                <a href="<?php echo e(route('petugas.borrowings.show', $borrowing)); ?>" class="text-blue-400 hover:text-blue-300 inline-flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    Detail
                                </a>
                                <?php if($borrowing->status == 'menunggu'): ?>
                                    <div class="flex gap-2">
                                        <form method="POST" action="<?php echo e(route('petugas.borrowings.approve', $borrowing)); ?>" class="inline approve-form" data-id="<?php echo e($borrowing->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="button" onclick="handleApproveBorrowing(this)" class="text-green-400 hover:text-green-300 inline-flex items-center gap-1 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                                Setujui
                                            </button>
                                        </form>
                                        <button onclick="showRejectModal(<?php echo e($borrowing->id); ?>, '<?php echo e(route('petugas.borrowings.reject', $borrowing)); ?>')" class="text-red-400 hover:text-red-300 inline-flex items-center gap-1 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                                            Tolak
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <span class="material-symbols-outlined text-gray-600 text-[64px] mb-4">assignment_turned_in</span>
                                <p class="text-gray-400 text-lg font-medium">Tidak ada data peminjaman</p>
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

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500">block</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tolak Peminjaman</h3>
            </div>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Alasan</label>
                    <textarea name="keterangan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 transition-all" required placeholder="Alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-red-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">block</span>
                        Tolak Permintaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(borrowingId, actionUrl) {
    document.getElementById('rejectForm').action = actionUrl;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function handleApproveBorrowing(button) {
    const form = button.closest('form');
    const borrowingId = form.dataset.id;
    
    showConfirmModal({
        title: 'Setujui Peminjaman',
        message: `Yakin ingin menyetujui peminjaman #${borrowingId}? Peminjaman akan aktif dan jatuh tempo akan dihitung dari tanggal selesai.`,
        type: 'success',
        okText: 'Ya, Setujui',
        onConfirm: function() {
            form.submit();
        }
    });
}

// Close modal on background click
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/petugas/borrowings/index.blade.php ENDPATH**/ ?>