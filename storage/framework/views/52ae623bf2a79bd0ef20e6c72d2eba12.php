<?php $__env->startSection('title', "New"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.user-panel', ['isForm' => true,'event' => 'create-user']);

$__html = app('livewire')->mount($__name, $__params, 'lw-1316071968-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<section class="page-body">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::form.user-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1316071968-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section><?php /**PATH D:\My Laravel Startup\ndako\Modules\Settings\resources\views\livewire\users\create.blade.php ENDPATH**/ ?>