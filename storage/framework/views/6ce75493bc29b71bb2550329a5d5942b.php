<?php $__env->startSection('title', __('Page Not Found')); ?>

<?php $__env->startSection('code', '404'); ?>

<?php $__env->startSection('image'); ?>
    <img src="<?php echo e(asset('assets/images/illustrations/errors/404.svg')); ?>" height="350px" alt="">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __("Oops! The page you’re looking for doesn’t exist.")); ?>


<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views/errors/404.blade.php ENDPATH**/ ?>