<?php $__env->startSection('title', "New"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::navbar.control-panel.unit-type-panel', ['isForm' => true,'event' => 'create-unit-type']);

$__html = app('livewire')->mount($__name, $__params, 'lw-93865291-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<!-- Page Content -->
<section class="">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::wizard.add-unit-wizard', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-93865291-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content --><?php /**PATH D:\My Laravel Startup\ndako\Modules/Properties\resources/views/livewire/unit-types/create.blade.php ENDPATH**/ ?>