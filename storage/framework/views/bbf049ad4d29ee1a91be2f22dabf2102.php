<?php $__env->startSection('title', 'Verifikasi Pengembalian'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Verifikasi Pengembalian</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

<!-- Filter -->
<!-- Filter -->
<div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 mb-6 industrial-border">
    <form method="GET" action="<?php echo e(route('petugas.returns.index')); ?>" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter</label>
            <select name="filter" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                <option value="">Semua Pengembalian Menunggu</option>
                <option value="terlambat" <?php echo e(request('filter') == 'terlambat' ? 'selected' : ''); ?>>Terlambat</option>
            </select>
        </div>
        <div class="flex items-end gap-2 pb-[1px]">
            <button type="submit" class="h-[42px] px-6 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            <a href="<?php echo e(route('petugas.returns.index')); ?>" class="h-[42px] px-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-all font-medium">
                <span class="material-symbols-outlined text-[20px] mr-1">refresh</span>
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Table Pengembalian Menunggu Persetujuan -->
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
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estimasi Denda</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $tanggalSelesai = $borrowing->tanggal_selesai ?? $borrowing->jatuh_tempo;
                        $isOverdue = $tanggalSelesai && now()->startOfDay()->gt(\Carbon\Carbon::parse($tanggalSelesai)->startOfDay());
                        $estimatedFine = $borrowing->calculateEstimatedFine();
                    ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition <?php echo e($isOverdue ? 'bg-red-50 dark:bg-red-900/10' : ''); ?>" data-borrowing-id="<?php echo e($borrowing->id); ?>">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-primary">#<?php echo e($borrowing->id); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <div class="font-bold"><?php echo e($borrowing->user->name); ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($borrowing->user->email); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                            <?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')); ?>

                            <?php if($isOverdue): ?>
                                <span class="block text-xs text-red-400 font-bold uppercase tracking-wider mt-1">Terlambat!</span>
                            <?php endif; ?>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <?php if($estimatedFine['denda'] > 0): ?>
                                <div class="text-red-400 font-bold">Rp <?php echo e(number_format($estimatedFine['denda'], 0, ',', '.')); ?></div>
                                <div class="text-xs text-red-400/70 font-medium"><?php echo e($estimatedFine['terlambat_hari']); ?> hari terlambat</div>
                            <?php else: ?>
                                <span class="text-green-400 font-bold">Rp 0</span>
                                <div class="text-xs text-gray-500">Tidak ada denda</div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-bold rounded uppercase tracking-wider border bg-yellow-500/10 text-yellow-400 border-yellow-500/20">
                                Menunggu Pengembalian
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col gap-2">
                                <a href="<?php echo e(route('petugas.borrowings.show', $borrowing)); ?>" class="text-blue-400 hover:text-blue-300 text-left inline-flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    Detail
                                </a>
                                <?php if($estimatedFine['denda'] > 0): ?>
                                    <form method="POST" action="<?php echo e(route('petugas.borrowings.fine-notification', $borrowing)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-orange-400 hover:text-orange-300 text-left inline-flex items-center gap-1 transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">notifications_active</span>
                                            Kirim Notif Denda
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <button onclick="showReminderModal(<?php echo e($borrowing->id); ?>, '<?php echo e(route('petugas.borrowings.reminder', $borrowing)); ?>')" class="text-yellow-400 hover:text-yellow-300 text-left inline-flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">alarm</span>
                                    Kirim Pengingat
                                </button>
                                <button onclick="showReturnModal(<?php echo e($borrowing->id); ?>, '<?php echo e(route('petugas.borrowings.return', $borrowing)); ?>')" class="text-green-400 hover:text-green-300 text-left inline-flex items-center gap-1 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">assignment_return</span>
                                    Setujui Pengembalian
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12">
                            <div class="flex flex-col items-center justify-center text-center">
                                <span class="material-symbols-outlined text-gray-600 text-[64px] mb-4">assignment_turned_in</span>
                                <p class="text-gray-400 text-lg font-medium">Tidak ada peminjaman aktif</p>
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

<!-- Modal Pengingat -->
<div id="reminderModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-500">alarm</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Kirim Pengingat</h3>
            </div>
            <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="reminderForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Pesan Pengingat</label>
                    <textarea name="pesan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 transition-all" placeholder="Pesan pengingat (opsional, kosongkan untuk menggunakan pesan default)..."></textarea>
                    <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-2 uppercase tracking-wide">Jika dikosongkan, akan menggunakan pesan default.</p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeReminderModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-yellow-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengembalian -->
<div id="returnModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500">assignment_return</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Menyetujui Pengembalian</h3>
            </div>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="returnForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <!-- Informasi Alat yang Dikembalikan -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-background-dark/50 rounded-lg border border-gray-200 dark:border-gray-700/50">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Alat yang Dikembalikan:</p>
                    <div id="returnToolsList" class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" value="<?php echo e(date('Y-m-d')); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all" required>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                    <input type="number" name="denda_kerusakan" min="0" step="1000" value="0" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all" placeholder="0">
                    <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-1 uppercase tracking-wide">Periksa kondisi alat dan masukkan jumlah denda jika ada kerusakan.</p>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan Kondisi</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Catatan kondisi alat, kerusakan yang ditemukan, dll..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        Setujui & Proses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showReminderModal(borrowingId, actionUrl) {
    document.getElementById('reminderForm').action = actionUrl;
    document.getElementById('reminderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReminderModal() {
    document.getElementById('reminderModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showReturnModal(borrowingId, actionUrl) {
    document.getElementById('returnForm').action = actionUrl;
    
    // Ambil data alat dari tabel
    const row = document.querySelector(`tr[data-borrowing-id="${borrowingId}"]`);
    if (row) {
        const toolsCell = row.querySelector('td:nth-child(5)'); // Kolom Alat
        const toolsList = document.getElementById('returnToolsList');
        if (toolsCell && toolsList) {
            toolsList.innerHTML = toolsCell.innerHTML.replace(/<ul[^>]*>|<\/ul>/g, '').replace(/<li[^>]*>/g, '<div class="py-1 border-b border-gray-700/50 last:border-0 flex items-center gap-2">').replace(/<\/li>/g, '</div>').replace(/<span class="w-1.5 h-1.5 rounded-full bg-gray-500"><\/span>/g, '<span class="material-symbols-outlined text-[16px] text-gray-500">construction</span>');
        }
    }
    
    document.getElementById('returnModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on background click
document.addEventListener('DOMContentLoaded', function() {
    const reminderModal = document.getElementById('reminderModal');
    const returnModal = document.getElementById('returnModal');
    
    if (reminderModal) {
        reminderModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReminderModal();
            }
        });
    }
    
    if (returnModal) {
        returnModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (reminderModal && !reminderModal.classList.contains('hidden')) {
                closeReminderModal();
            }
            if (returnModal && !returnModal.classList.contains('hidden')) {
                closeReturnModal();
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/petugas/returns/index.blade.php ENDPATH**/ ?>