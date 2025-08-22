<?php $__env->startSection('title', "Expense Categories"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('revenuemanager::navbar.control-panel.expense-category-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1896006411-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<section class="w-100">
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('revenuemanager::table.expense-category-table', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1896006411-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/RevenueManager\resources/views/livewire/expense-category/lists.blade.php ENDPATH**/ ?>