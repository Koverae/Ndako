<?php $__env->startSection('page_title', "Getting Started"); ?>

<?php $__env->startSection('page_content'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::getting-started', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4223074459-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views\auth\get-started.blade.php ENDPATH**/ ?>