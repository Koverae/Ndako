<?php $__env->startSection('title', "Companies"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.company-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1238991152-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<section class="w-100">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::table.company-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1238991152-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section><?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/companies/lists.blade.php ENDPATH**/ ?>