<?php $__env->startSection('title', __('Under Maintenance 🚧🔧🛠️')); ?>

<?php $__env->startSection('code', '503'); ?>

<?php $__env->startSection('image'); ?>
    <img src="<?php echo e(asset('assets/images/illustrations/errors/503.svg')); ?>" height="350px" alt="">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('message'); ?>
    <span><?php echo e(__("We're currently performing some maintenance to keep things running smoothly")); ?></span>
    <br>
    <span><?php echo e(__('Please check back shortly. Your patience means everything 💛')); ?></span>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.error', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views/errors/503.blade.php ENDPATH**/ ?>