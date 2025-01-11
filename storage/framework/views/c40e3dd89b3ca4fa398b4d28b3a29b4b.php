<?php $__env->startSection('title', $this->property->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::navbar.control-panel.property-panel', ['property' => $property,'event' => 'update-property','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-148417482-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('properties::form.property-form', ['property' => $property]);

$__html = app('livewire')->mount($__name, $__params, 'lw-148417482-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content --><?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/Properties\resources/views/livewire/properties/show.blade.php ENDPATH**/ ?>