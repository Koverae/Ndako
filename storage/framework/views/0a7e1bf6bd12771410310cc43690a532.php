<?php $__env->startSection('title', "Users"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.user-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3412639752-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('settings::table.user-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3412639752-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section><?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/Settings\resources/views/livewire/users/lists.blade.php ENDPATH**/ ?>