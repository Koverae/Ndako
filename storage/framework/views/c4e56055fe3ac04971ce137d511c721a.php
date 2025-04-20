<?php $__env->startSection('title', $this->invoice->reference); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::navbar.control-panel.booking-invoice-panel', ['invoice' => $invoice,'event' => 'update-invoice','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3092944093-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('channelmanager::form.booking-invoice-form', ['invoice' => $invoice]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3092944093-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules\ChannelManager\resources\views\livewire\booking-ivoices\show.blade.php ENDPATH**/ ?>