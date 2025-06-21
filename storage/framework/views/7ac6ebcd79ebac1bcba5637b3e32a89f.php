<?php $__env->startSection('title', __('Access Denied')); ?>

<?php $__env->startSection('code', '403'); ?>

<?php $__env->startSection('image'); ?>
    <img src="<?php echo e(asset('assets/images/illustrations/errors/403.svg')); ?>" height="350px" alt="">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __("You do not have permission to access this page.")); ?>


<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views/errors/403.blade.php ENDPATH**/ ?>