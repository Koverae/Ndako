<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
]));

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

foreach (array_filter(([
    'value',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php
    $type = \Modules\Properties\Models\Property\PropertyUnitType::find($value);
?>
<div>
    <!--[if BLOCK]><![endif]--><?php if($type->price): ?>
    <a style="text-decoration: none" class="primary" tabindex="-1">
        <?php echo e(format_currency($type->price) ?? ''); ?> / <?php echo e(__('Night')); ?>

        
    </a>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/table/column/special/unit-price.blade.php ENDPATH**/ ?>