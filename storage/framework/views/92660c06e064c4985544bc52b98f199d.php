<?php $__env->startSection('title', 'Tambah User'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tambah User</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
    
    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Nama</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required placeholder="Masukkan nama lengkap...">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required placeholder="contoh@email.com">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Password</label>
                <input type="password" name="password" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required placeholder="Minimal 8 karakter">
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Role</label>
                <select name="role" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" required>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="peminjam">Peminjam</option>
                </select>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">save</span>
                    Simpan
                </button>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="px-6 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\UKKfix\resources\views/admin/users/create.blade.php ENDPATH**/ ?>