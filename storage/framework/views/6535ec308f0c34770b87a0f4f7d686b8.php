<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value','model','key','id']));

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

foreach (array_filter((['value','model','key','id']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal5563e1b5db76142d22441490708b7dbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5563e1b5db76142d22441490708b7dbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.table.card.simple','data' => ['value' => $value,'model' => $model,'key' => $key,'id' => $id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::table.card.simple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($model),'key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5563e1b5db76142d22441490708b7dbf)): ?>
<?php $attributes = $__attributesOriginal5563e1b5db76142d22441490708b7dbf; ?>
<?php unset($__attributesOriginal5563e1b5db76142d22441490708b7dbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5563e1b5db76142d22441490708b7dbf)): ?>
<?php $component = $__componentOriginal5563e1b5db76142d22441490708b7dbf; ?>
<?php unset($__componentOriginal5563e1b5db76142d22441490708b7dbf); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\koverae-saas\storage\framework\views/8ce6b9a97a7e79286493e8bad44ba208.blade.php ENDPATH**/ ?>