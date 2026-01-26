<?php $__env->startSection('title', 'Detail Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Detail Peminjaman <span class="text-primary">#<?php echo e($borrowing->id); ?></span></h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="bg-panel-dark border border-white/5 rounded-xl p-6 mb-6 industrial-border">
        <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-700/50 pb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">info</span>
            Informasi Peminjaman
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Peminjam</p>
                    <p class="font-medium text-white text-lg"><?php echo e($borrowing->user->name); ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Email</p>
                    <p class="font-medium text-gray-300 font-mono"><?php echo e($borrowing->user->email); ?></p>
                </div>
                <div>
                   <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Status</p>
                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded uppercase tracking-wider border
                        <?php if($borrowing->status == 'disetujui'): ?> bg-green-500/10 text-green-400 border-green-500/20
                        <?php elseif($borrowing->status == 'ditolak'): ?> bg-red-500/10 text-red-400 border-red-500/20
                        <?php elseif($borrowing->status == 'dikembalikan'): ?> bg-blue-500/10 text-blue-400 border-blue-500/20
                        <?php else: ?> bg-yellow-500/10 text-yellow-400 border-yellow-500/20
                        <?php endif; ?>">
                        <?php echo e(ucfirst($borrowing->status)); ?>

                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                 <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Mulai</p>
                    <div class="flex items-center gap-2 text-white">
                        <span class="material-symbols-outlined text-gray-500 text-[20px]">calendar_today</span>
                        <?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?>

                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Selesai</p>
                    <div class="flex items-center gap-2 text-white">
                        <span class="material-symbols-outlined text-gray-500 text-[20px]">event_busy</span>
                        <?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')); ?>

                    </div>
                </div>
                
                <?php if($borrowing->status == 'disetujui'): ?>
                    <?php
                        $estimatedFine = $borrowing->calculateEstimatedFine();
                    ?>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Estimasi Denda</p>
                        <?php if($estimatedFine['denda'] > 0): ?>
                            <p class="font-bold text-lg text-red-400">Rp <?php echo e(number_format($estimatedFine['denda'], 0, ',', '.')); ?></p>
                            <p class="text-xs text-red-400/70 font-bold uppercase tracking-wide"><?php echo e($estimatedFine['terlambat_hari']); ?> hari terlambat</p>
                        <?php else: ?>
                            <p class="font-bold text-lg text-green-400">Rp 0</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Tidak ada denda</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if($borrowing->keterangan): ?>
                <div class="md:col-span-2 pt-4 border-t border-gray-700/50">
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-2">Keterangan</p>
                    <div class="p-4 bg-background-dark/50 rounded-lg border border-gray-700/50 text-gray-300 italic">
                        "<?php echo e($borrowing->keterangan); ?>"
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-panel-dark border border-white/5 rounded-xl p-6 mb-6 industrial-border">
        <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-700/50 pb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-accent-cyan">construction</span>
            Alat yang Dipinjam
        </h2>
        <div class="space-y-4">
            <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-4 p-4 bg-background-dark/80 rounded-lg border border-gray-700 hover:border-primary/50 transition-all group">
                    
                    <div class="flex-shrink-0">
                        <?php if($detail->tool->gambar): ?>
                            <img src="<?php echo e(asset($detail->tool->gambar)); ?>" 
                                 alt="<?php echo e($detail->tool->nama_alat); ?>" 
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-600 group-hover:border-primary transition-colors">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-800 rounded-lg flex items-center justify-center border border-gray-700 group-hover:border-primary transition-colors">
                                <span class="material-symbols-outlined text-gray-600 text-[32px]">construction</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    
                    <div class="flex-1">
                        <h3 class="font-bold text-white text-lg mb-1 group-hover:text-primary transition-colors"><?php echo e($detail->tool->nama_alat); ?></h3>
                        <div class="flex items-center gap-2">
                             <span class="px-2 py-0.5 rounded text-[10px] bg-gray-700 text-gray-300 font-bold uppercase tracking-wider">
                                <?php echo e($detail->tool->category->nama_kategori); ?>

                            </span>
                        </div>
                    </div>
                    
                    
                    <div class="flex-shrink-0 text-right">
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Jumlah</p>
                        <div class="text-2xl font-bold text-white font-mono bg-white/5 px-4 py-1 rounded border border-white/10">
                            <?php echo e($detail->jumlah); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php if($borrowing->status == 'menunggu'): ?>
        <div class="bg-panel-dark border border-white/5 rounded-xl p-6 mb-6 industrial-border">
            <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-700/50 pb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-yellow-500">pending</span>
                Aksi Peminjaman
            </h2>
            <div class="flex gap-4">
                <form method="POST" action="<?php echo e(route('petugas.borrowings.approve', $borrowing)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="h-12 px-6 bg-green-600 text-white hover:bg-green-500 rounded-lg font-bold uppercase tracking-wider transition-all inline-flex items-center gap-2 shadow-lg shadow-green-600/20" onclick="return confirm('Setujui peminjaman ini?')">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        Setujui Peminjaman
                    </button>
                </form>
                <button onclick="showRejectModal()" class="h-12 px-6 bg-red-600 text-white hover:bg-red-500 rounded-lg font-bold uppercase tracking-wider transition-all inline-flex items-center gap-2 shadow-lg shadow-red-600/20">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                    Tolak Peminjaman
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if($borrowing->status == 'disetujui'): ?>
        <?php
            $estimatedFine = $borrowing->calculateEstimatedFine();
        ?>
        <div class="bg-panel-dark border border-white/5 rounded-xl p-6 mb-6 industrial-border">
            <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-700/50 pb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-500">manage_accounts</span>
                Aksi Petugas
            </h2>
            <div class="flex flex-wrap gap-4">
                <?php if($estimatedFine['denda'] > 0): ?>
                    <form method="POST" action="<?php echo e(route('petugas.borrowings.fine-notification', $borrowing)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="h-10 px-4 bg-orange-600 text-white hover:bg-orange-500 rounded-lg font-bold uppercase tracking-wider transition-all inline-flex items-center gap-2 shadow-lg shadow-orange-600/20 text-xs">
                           <span class="material-symbols-outlined text-[18px]">notifications_active</span>
                            Kirim Notifikasi Denda
                        </button>
                    </form>
                <?php endif; ?>
                <button onclick="showReminderModal('<?php echo e(route('petugas.borrowings.reminder', $borrowing)); ?>')" class="h-10 px-4 bg-yellow-600 text-white hover:bg-yellow-500 rounded-lg font-bold uppercase tracking-wider transition-all inline-flex items-center gap-2 shadow-lg shadow-yellow-600/20 text-xs">
                    <span class="material-symbols-outlined text-[18px]">alarm</span>
                    Kirim Pengingat Pengembalian
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if($borrowing->return): ?>
        <div class="bg-panel-dark border border-white/5 rounded-xl p-6 mb-6 industrial-border">
            <h2 class="text-lg font-bold text-white mb-6 uppercase tracking-wider border-b border-gray-700/50 pb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-green-500">assignment_turned_in</span>
                Informasi Pengembalian
            </h2>
            <?php
                $totalDenda = $borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0);
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Tanggal Kembali</p>
                    <div class="flex items-center gap-2 text-white">
                        <span class="material-symbols-outlined text-green-500 text-[20px]">event_available</span>
                        <?php echo e($borrowing->return->tanggal_kembali->format('d/m/Y')); ?>

                    </div>
                </div>
                <div>
                     <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Total Denda Ditagihkan</p>
                    <p class="font-bold text-2xl font-mono <?php echo e($totalDenda > 0 ? 'text-red-400' : 'text-green-400'); ?>">Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></p>
                    
                    <div class="mt-2 space-y-1">
                        <?php if($borrowing->return->denda > 0): ?>
                            <div class="flex justify-between text-xs text-gray-400 border-b border-dashed border-gray-700 pb-1">
                                <span>Keterlambatan (<?php echo e($borrowing->return->terlambat_hari); ?> hari)</span>
                                <span class="text-red-400">Rp <?php echo e(number_format($borrowing->return->denda, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(($borrowing->return->denda_kerusakan ?? 0) > 0): ?>
                           <div class="flex justify-between text-xs text-gray-400 border-b border-dashed border-gray-700 pb-1">
                                <span>Kerusakan Alat</span>
                                <span class="text-red-400">Rp <?php echo e(number_format($borrowing->return->denda_kerusakan ?? 0, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex gap-4">
        <a href="<?php echo e(route('petugas.borrowings.index')); ?>" class="h-10 px-6 flex items-center bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all uppercase tracking-wide text-xs">
            <span class="material-symbols-outlined text-[18px] mr-2">arrow_back</span>
            Kembali
        </a>
        <?php if($borrowing->status != 'menunggu' && $borrowing->status != 'ditolak'): ?>
            <a href="<?php echo e(route('petugas.borrowings.print', $borrowing)); ?>" target="_blank" class="h-10 px-6 flex items-center bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all uppercase tracking-wide text-xs shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px] mr-2">print</span>
                Cetak Bukti
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-panel-dark border border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500">block</span>
                </div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Tolak Peminjaman</h3>
            </div>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form method="POST" action="<?php echo e(route('petugas.borrowings.reject', $borrowing)); ?>">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Alasan</label>
                    <textarea name="keterangan" rows="4" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 transition-all" required placeholder="Alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-700/50">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-red-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">block</span>
                        Tolak
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengingat -->
<div id="reminderModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-panel-dark border border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-500">alarm</span>
                </div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Kirim Pengingat</h3>
            </div>
            <button onclick="closeReminderModal()" class="text-gray-400 hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="reminderForm" method="POST">
            <?php echo csrf_field(); ?>
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Pesan Pengingat</label>
                    <textarea name="pesan" rows="4" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 transition-all" placeholder="Pesan pengingat (opsional, kosongkan untuk menggunakan pesan default)..."></textarea>
                    <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Jika dikosongkan, akan menggunakan pesan default.</p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-700/50">
                    <button type="button" onclick="closeReminderModal()" class="px-4 py-2 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-yellow-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showReminderModal(actionUrl) {
    document.getElementById('reminderForm').action = actionUrl;
    document.getElementById('reminderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReminderModal() {
    document.getElementById('reminderModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on background click
document.addEventListener('DOMContentLoaded', function() {
    const reminderModal = document.getElementById('reminderModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (reminderModal) {
        reminderModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReminderModal();
            }
        });
    }
    
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (reminderModal && !reminderModal.classList.contains('hidden')) {
                closeReminderModal();
            }
            if (rejectModal && !rejectModal.classList.contains('hidden')) {
                closeRejectModal();
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/petugas/borrowings/show.blade.php ENDPATH**/ ?>