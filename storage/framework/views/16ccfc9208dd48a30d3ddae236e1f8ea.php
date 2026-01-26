<?php $__env->startSection('title', 'Tambah Alat'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white uppercase tracking-wider">Tambah Alat</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="bg-panel-dark border border-white/5 rounded-xl p-8 industrial-border">
        <form method="POST" action="<?php echo e(route('admin.tools.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Nama Alat</label>
                <input type="text" name="nama_alat" value="<?php echo e(old('nama_alat')); ?>" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required placeholder="Masukkan nama alat...">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Kategori</label>
                <select name="kategori_id" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    <option value="">Pilih Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->nama_kategori); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Kategori Kondisi</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] text-gray-500 mb-1 uppercase tracking-wider">Stok Baik</label>
                        <input type="number" name="stok" value="<?php echo e(old('stok', 0)); ?>" min="0" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 mb-1 uppercase tracking-wider">Stok Rusak</label>
                        <input type="number" name="stok_rusak" value="<?php echo e(old('stok_rusak', 0)); ?>" min="0" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 mb-1 uppercase tracking-wider">Stok Diperbaiki</label>
                        <input type="number" name="stok_perbaikan" value="<?php echo e(old('stok_perbaikan', 0)); ?>" min="0" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Status</label>
                <select name="status" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    <option value="tersedia" <?php echo e(old('status') == 'tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                    <option value="rusak" <?php echo e(old('status') == 'rusak' ? 'selected' : ''); ?>>Rusak</option>
                    <option value="perbaikan" <?php echo e(old('status') == 'perbaikan' ? 'selected' : ''); ?>>Perbaikan</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Tambahkan deskripsi alat..."><?php echo e(old('deskripsi')); ?></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest pl-1">Gambar Alat</label>
                <input type="file" name="gambar" accept="image/*" class="w-full bg-background-dark border border-gray-700 text-white text-sm rounded-lg file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 transition-all">
                <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-wide">Format: JPG, PNG, GIF (Max: 2MB)</p>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Simpan
                </button>
                <a href="<?php echo e(route('admin.tools.index')); ?>" class="px-6 py-2.5 bg-transparent border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-all flex items-center gap-2 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/tools/create.blade.php ENDPATH**/ ?>