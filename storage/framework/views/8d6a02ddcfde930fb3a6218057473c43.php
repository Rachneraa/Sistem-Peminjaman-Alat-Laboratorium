<?php if($paginator->hasPages()): ?>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 w-full text-sm">
        <div class="text-gray-400">
            Showing 
            <span class="font-bold text-white"><?php echo e($paginator->firstItem()); ?></span> 
            to 
            <span class="font-bold text-white"><?php echo e($paginator->lastItem()); ?></span> 
            of 
            <span class="font-bold text-white"><?php echo e($paginator->total()); ?></span> 
            results
        </div>

        <div class="flex gap-1">
            
            <?php if($paginator->onFirstPage()): ?>
                <span class="flex items-center justify-center w-8 h-8 rounded bg-gray-800/50 text-gray-600 border border-white/5 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                </a>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <span class="flex items-center justify-center w-8 h-8 rounded bg-transparent text-gray-500">
                        <?php echo e($element); ?>

                    </span>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <span class="flex items-center justify-center w-8 h-8 rounded bg-primary text-white border border-primary font-bold shadow-lg shadow-primary/20">
                                <?php echo e($page); ?>

                            </span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                                <?php echo e($page); ?>

                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="flex items-center justify-center w-8 h-8 rounded bg-panel-dark text-gray-300 border border-gray-700 hover:bg-gray-700 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </a>
            <?php else: ?>
                <span class="flex items-center justify-center w-8 h-8 rounded bg-gray-800/50 text-gray-600 border border-white/5 cursor-not-allowed">
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\UKKfix\resources\views/vendor/pagination/industrial.blade.php ENDPATH**/ ?>