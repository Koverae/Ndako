<?php $__env->startSection('content'); ?>
    <h1>Hello World</h1>

    <p>Module: <?php echo config('contact.name'); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('contact::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/Contact\resources/views/index.blade.php ENDPATH**/ ?>