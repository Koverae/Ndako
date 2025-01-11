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
<!-- Developer -->
<div class="k_settings_box col-12 col-lg-6 k_searchable_setting">

    <!-- Right pane -->
    <div class="k_setting_right_pane">
        <div class="mt12">
            <a href="https://ndako.koverae.com/docs" target="_blank" class="cursor-pointer d-block">
                <?php echo e(__('View the documentation')); ?>

            </a>
        </div>
    </div>

</div><?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/blocks/boxes/template/developer.blade.php ENDPATH**/ ?>