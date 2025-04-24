<?php $__env->startSection('title', "Import File"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::navbar.control-panel.import-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1008576848-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<!-- Page Content -->
<section class="w-100">
    <div class="bg-white empty k_nocontent_help h-100">
        <img src="<?php echo e(asset('assets/images/illustrations/file.svg')); ?>"style="height: 350px" alt="">
        <p class="empty-title"><?php echo e(__('Drop or upload a file to import')); ?></p>
        <p class="empty-subtitle"><?php echo e(__('Excel files are recommended as formatting is automatic. But, you can also use .csv files')); ?></p>
    </div>
</section>
<!-- Page Content --><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/import-file.blade.php ENDPATH**/ ?>