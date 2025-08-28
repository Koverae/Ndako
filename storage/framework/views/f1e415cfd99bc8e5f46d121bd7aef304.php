<?php $__env->startSection('title', $this->guest->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>

<?php $__env->stopSection(); ?>
<!-- Page Content -->
<section class="">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::guest-form', ['guest' => $guest]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3719340597-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/livewire/guests/show.blade.php ENDPATH**/ ?>