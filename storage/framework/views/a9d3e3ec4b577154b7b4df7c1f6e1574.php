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
    <!-- Left Side -->
    <div class="k_inner_group col-md-6 col-lg-6">
        <!-- separator -->
        <div class="g-col-sm-2">

            <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
                    <?php echo e($value->label); ?>

            </div>
        </div>

    </div>


<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\form\tab\group\special\unit-tenant.blade.php ENDPATH**/ ?>