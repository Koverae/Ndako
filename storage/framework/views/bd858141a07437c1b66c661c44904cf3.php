<?php $__env->startSection('title', "New"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.company-panel', ['isForm' => true,'event' => 'create-company']);

$__html = app('livewire')->mount($__name, $__params, 'lw-3703083543-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('settings::form.company-form', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-3703083543-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/companies/create.blade.php ENDPATH**/ ?>