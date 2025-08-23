<?php $__env->startSection('title', __('New Product')); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos::navbar.control-panel.product-panel', ['event' => 'create-product','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1952691106-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('pos::form.product-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1952691106-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/product/create.blade.php ENDPATH**/ ?>