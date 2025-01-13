<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'data'
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
    'data'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    <!--[if BLOCK]><![endif]--><?php if($value->label): ?>
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <label class="k_form_label">
            <?php echo e($value->label); ?>

            <!--[if BLOCK]><![endif]--><?php if($value->help): ?>
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="<?php echo e($value->help); ?>"></i></sup>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </label>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <!-- Input Form -->
    <div class="gap-1 k_cell k_wrap_input flex-grow-1 d-flex">
        <!--[if BLOCK]><![endif]--><?php if($this->unitPrice): ?>
        <span>
            <?php echo e(format_currency($this->unitPrice) ?? ''); ?> / <?php echo e(__('Night')); ?> 
            
        </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>
</div>

<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/form/input/unit-price.blade.php ENDPATH**/ ?>