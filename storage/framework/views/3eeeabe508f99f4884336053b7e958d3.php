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

<!--[if BLOCK]><![endif]--><?php if($value == 'pending'): ?>
    <span class="text-white badge bg-warning">
       <?php echo e(__('Pending')); ?>

    </span>
<?php elseif($value == 'confirmed'): ?>
    <span class="text-white badge" style="background-color: #0E6163;">
        <?php echo e(__('Confirmed')); ?>

    </span>
<?php elseif($value == 'canceled'): ?>
    <span class="text-white badge bg-danger">
        <?php echo e(__('Canceled')); ?>

    </span>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/table/column/special/booking/booking-status.blade.php ENDPATH**/ ?>