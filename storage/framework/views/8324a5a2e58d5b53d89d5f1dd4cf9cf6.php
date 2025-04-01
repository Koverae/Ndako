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
<?php if (isset($component)) { $__componentOriginal2e8e80413fc0eb7836153d8d62fce467 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e8e80413fc0eb7836153d8d62fce467 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.form.input.radio.simple','data' => ['value' => $value]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::form.input.radio.simple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e8e80413fc0eb7836153d8d62fce467)): ?>
<?php $attributes = $__attributesOriginal2e8e80413fc0eb7836153d8d62fce467; ?>
<?php unset($__attributesOriginal2e8e80413fc0eb7836153d8d62fce467); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e8e80413fc0eb7836153d8d62fce467)): ?>
<?php $component = $__componentOriginal2e8e80413fc0eb7836153d8d62fce467; ?>
<?php unset($__componentOriginal2e8e80413fc0eb7836153d8d62fce467); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\ndako\storage\framework\views/ddeef9aff8df35d6f8cde656e205d192.blade.php ENDPATH**/ ?>