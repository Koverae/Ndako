<?php $__env->startSection('title', 'New'); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::navbar.control-panel.property-type-panel', ['event' => 'create-property-type','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2359460802-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('properties::form.property-type-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2359460802-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content --><?php /**PATH D:\My Laravel Startup\ndako\Modules\Properties\resources\views\livewire\property-type\create.blade.php ENDPATH**/ ?>