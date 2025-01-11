<?php $__env->startSection('title', $this->channel->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::navbar.control-panel.channel-panel', ['channel' => $channel,'event' => 'update-channel','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2846421982-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('channelmanager::form.channel-form', ['channel' => $channel]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2846421982-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/ChannelManager\resources/views/livewire/channels/show.blade.php ENDPATH**/ ?>