<?php $__env->startSection('title', $category->name); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pos::navbar.control-panel.product-category-panel', ['category' => $category,'event' => 'update-category','isForm' => true]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3376709463-0', $__slots ?? [], get_defined_vars());

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
[$__name, $__params] = $__split('pos::form.product-category-form', ['category' => $category]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3376709463-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</section>
<!-- Page Content -->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/product-category/show.blade.php ENDPATH**/ ?>