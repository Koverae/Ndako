<?php $__env->startSection('title', "Settings"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::navbar.setting-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-80489791-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<!-- Page Content -->
<section class="page-body">
    <!-- Settings -->
    <div class="k-row">
        <!-- Left Sidebar -->
        <div class="settings_tab border-end">

            <!-- Paramètre Généraux -->
            <div class="cursor-pointer tab selected">
                <!-- App Icon -->
                <div class="icon d-none d-md-block">
                    <img src="<?php echo e(asset('assets/images/apps/settings.png')); ?>" alt="">
                </div>
                <!-- App Name -->
                <span class="app_name">
                    General Setting
                </span>
            </div>

        </div>

        <!-- Right Sidebar -->
        <div class="settings">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::settings.general', ['setting' => settings()]);

$__html = app('livewire')->mount($__name, $__params, 'lw-80489791-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        </div>
    </div>
</section>
<!-- Page Content End -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/general-setting.blade.php ENDPATH**/ ?>