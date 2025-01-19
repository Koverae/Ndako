<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value','id']));

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

foreach (array_filter((['value','id']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php if (isset($component)) { $__componentOriginal24943706478fe6dd801cd358ffb1a66e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal24943706478fe6dd801cd358ffb1a66e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'app::components.table.column.special.date.timestamps','data' => ['value' => $value,'id' => $id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app::table.column.special.date.timestamps'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($value),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($id)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal24943706478fe6dd801cd358ffb1a66e)): ?>
<?php $attributes = $__attributesOriginal24943706478fe6dd801cd358ffb1a66e; ?>
<?php unset($__attributesOriginal24943706478fe6dd801cd358ffb1a66e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal24943706478fe6dd801cd358ffb1a66e)): ?>
<?php $component = $__componentOriginal24943706478fe6dd801cd358ffb1a66e; ?>
<?php unset($__componentOriginal24943706478fe6dd801cd358ffb1a66e); ?>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\koverae-saas\storage\framework\views/bacc0f9770aae94abefe4d86cc74eec3.blade.php ENDPATH**/ ?>