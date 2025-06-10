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
<?php if (isset($component)) { $__componentOriginalb0abe9b34fb9c101ed90aad0e75fd551 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb0abe9b34fb9c101ed90aad0e75fd551 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.form.input.simple','data' => ['value' => $value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::form.input.simple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb0abe9b34fb9c101ed90aad0e75fd551)): ?>
<?php $attributes = $__attributesOriginalb0abe9b34fb9c101ed90aad0e75fd551; ?>
<?php unset($__attributesOriginalb0abe9b34fb9c101ed90aad0e75fd551); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb0abe9b34fb9c101ed90aad0e75fd551)): ?>
<?php $component = $__componentOriginalb0abe9b34fb9c101ed90aad0e75fd551; ?>
<?php unset($__componentOriginalb0abe9b34fb9c101ed90aad0e75fd551); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\ndako\storage\framework\views/3f96e337012d287d08867d6656ce31a2.blade.php ENDPATH**/ ?>