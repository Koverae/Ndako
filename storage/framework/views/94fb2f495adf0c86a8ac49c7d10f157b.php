<?php $__env->startSection('title', $this->tenant->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::navbar.control-panel.tenant-panel', ['tenant' => $tenant,'event' => 'update-tenant','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1645531996-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<!-- Page Content -->
<section class="w-100">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::form.tenant-form', ['tenant' => $this->tenant]);

$__html = app('livewire')->mount($__name, $__params, 'lw-1645531996-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules\Properties\resources\views\livewire\tenants\show.blade.php ENDPATH**/ ?>