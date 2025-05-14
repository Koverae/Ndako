<?php $__env->startSection('title', $subject); ?>

<?php $__env->startSection('preview'); ?>
    Preview
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php echo $content; ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.email', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/emails/template.blade.php ENDPATH**/ ?>