<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal480a6c47453d5dba38704d1b8355f404 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal480a6c47453d5dba38704d1b8355f404 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.wizard.step-page.special.onboarding.identity','data' => ['value' => $value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::wizard.step-page.special.onboarding.identity'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal480a6c47453d5dba38704d1b8355f404)): ?>
<?php $attributes = $__attributesOriginal480a6c47453d5dba38704d1b8355f404; ?>
<?php unset($__attributesOriginal480a6c47453d5dba38704d1b8355f404); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal480a6c47453d5dba38704d1b8355f404)): ?>
<?php $component = $__componentOriginal480a6c47453d5dba38704d1b8355f404; ?>
<?php unset($__componentOriginal480a6c47453d5dba38704d1b8355f404); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\ndako\storage\framework\views/590c8e8ffa5f1b6a7f163600496f8a4e.blade.php ENDPATH**/ ?>