<?php $__env->startSection('content'); ?>
    <h1>Hello World</h1>

    <p>Module: <?php echo config('app.name'); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\index.blade.php ENDPATH**/ ?>