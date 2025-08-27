<?php $__env->startSection('title', $subject); ?>

<?php $__env->startSection('preview'); ?>
    Preview
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<tr>
    <td class="pb-0 content" align="center" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px 0;">
        <h1 class="m-0 text-center mt-md" style="font-weight: 600; color: #232b42; font-size: 28px; line-height: 130%; margin: 16px 0 0;" align="center">
            <?php echo e($subject); ?>

        </h1>
    </td>
</tr>
<tr>
    <td class="content" style="font-family: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; padding: 40px 48px;">
        <?php echo $content; ?>

    </td>
</tr>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.email', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/emails/template.blade.php ENDPATH**/ ?>