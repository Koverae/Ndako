<?php $__env->startSection('title', "New"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos::navbar.control-panel.pos-panel', ['event' => 'create-pos','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-4257389883-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('pos::form.pos-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4257389883-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/pos/create.blade.php ENDPATH**/ ?>