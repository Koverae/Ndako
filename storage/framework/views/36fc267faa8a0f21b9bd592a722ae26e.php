<?php $__env->startSection('title', "Roles & Permissions"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.role-permission-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-431421131-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('settings::table.role-permission-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-431421131-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\Settings\resources\views\livewire\roles\lists.blade.php ENDPATH**/ ?>