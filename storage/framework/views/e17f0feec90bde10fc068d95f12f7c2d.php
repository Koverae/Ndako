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
<?php if (isset($component)) { $__componentOriginalea9ce8778bd9dd9079243ff85e055f8f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea9ce8778bd9dd9079243ff85e055f8f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.table.card.template.pos','data' => ['value' => $value,'model' => $model,'key' => $key,'id' => $id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::table.card.template.pos'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($model),'key' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($key),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea9ce8778bd9dd9079243ff85e055f8f)): ?>
<?php $attributes = $__attributesOriginalea9ce8778bd9dd9079243ff85e055f8f; ?>
<?php unset($__attributesOriginalea9ce8778bd9dd9079243ff85e055f8f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea9ce8778bd9dd9079243ff85e055f8f)): ?>
<?php $component = $__componentOriginalea9ce8778bd9dd9079243ff85e055f8f; ?>
<?php unset($__componentOriginalea9ce8778bd9dd9079243ff85e055f8f); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\ndako\storage\framework\views/ab9041ce54f2284068453323c9dc2def.blade.php ENDPATH**/ ?>