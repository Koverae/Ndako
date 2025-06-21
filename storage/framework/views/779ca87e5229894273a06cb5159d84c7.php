<?php $__env->startSection('title', __('Slow Down')); ?>

<?php $__env->startSection('code', '429'); ?>

<?php $__env->startSection('image'); ?>
    <img src="<?php echo e(asset('assets/images/illustrations/errors/429.svg')); ?>" height="350px" alt="">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message', __("You’re sending too many requests. Please take a short break and try again.")); ?>


<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views\errors\429.blade.php ENDPATH**/ ?>