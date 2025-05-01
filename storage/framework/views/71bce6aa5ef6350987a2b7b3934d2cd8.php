<?php $__env->startSection('title', $this->user->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.control-panel.user-panel', ['user' => $user,'isForm' => true,'event' => 'update-user']);

$__html = app('livewire')->mount($__name, $__params, 'lw-3626448416-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<section class="">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::form.user-form', ['user' => $user]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3626448416-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section><?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/users/show.blade.php ENDPATH**/ ?>