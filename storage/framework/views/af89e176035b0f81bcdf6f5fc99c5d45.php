<?php $__env->startSection('title', __('New Floor Plan')); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos::navbar.control-panel.floor-panel', ['event' => 'create-floor','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3076869278-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('pos::form.floor-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3076869278-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/floor/create.blade.php ENDPATH**/ ?>