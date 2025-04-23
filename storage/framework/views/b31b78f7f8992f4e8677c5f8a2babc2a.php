<?php $__env->startSection('title', 'New'); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::navbar.control-panel.booking-panel', ['event' => 'create-booking','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2549177246-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('channelmanager::wizard.add-booking-wizard', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2549177246-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/livewire/bookings/create.blade.php ENDPATH**/ ?>