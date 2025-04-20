

<?php $__env->startSection('title', 'Verify Your Email Address'); ?>

<?php $__env->startSection('preview'); ?>
    Verify Your Email
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="pb-0 content" align="center">
            <table class="icon icon-lg bg-green" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="middle" align="center">
                        <img src="<?php echo e(public_path('assets/images/email/icons-white-check.png')); ?>" class=" va-middle" width="40" height="40" alt="check" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="text-center content">
            <p class="h1">We're glad to have you here, Arden!</p>
            <p>Thanks for registering and your willingness to try <?php echo e(config('app.name')); ?> out. To authenticate your email address, please click on below button.</p>
        </td>
    </tr>
    <tr>
        <td class="pt-0 text-center content pb-xl">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center">
                        <table cellpadding="0" cellspacing="0" border="0" class="w-auto rounded bg-green">
                            <tr>
                                <td align="center" valign="top" class="lh-1">
                                    <a href="<?php echo e($verificationUrl); ?>" class="btn bg-green border-green">
                                        <span class="btn-span">Confirm&nbsp;your&nbsp;email&nbsp;address</span>
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="text-center content border-top">
            <p>
                Yours sincerely,<br>
                Arden BOUET, CEO at <a href="https://koverae.com/emails?utm_source=email" class="text-default font-weight-bold"><?php echo e(config('app.name')); ?></a>
            </p>
            <p>
                <img src="<?php echo e(public_path('assets/images/email/founder.jpg')); ?>" class=" avatar" width="72" height="72" alt="Arden BOUET" />
            </p>
        </td>
    </tr>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.email', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views\emails\verify-email.blade.php ENDPATH**/ ?>