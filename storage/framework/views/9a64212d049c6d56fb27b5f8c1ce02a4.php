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
<!-- Weight -->
<div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="<?php echo e($value->key); ?>">

    <!-- Right pane -->
    <div class="k_setting_right_pane">
        <div class="mt12">
            <div>
                <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                    <?php if($value->icon): ?>
                        <i class="inline-block bi <?php echo e($value->icon); ?>"></i>
                    <?php endif; ?>
                    <span class="ml-2"><?php echo e($value->label); ?></span>
                    <?php if($value->help): ?>
                    <a href="<?php echo e($value->help); ?>" target="__blank" title="documentation" class="k_doc_link">
                        <i class="bi bi-question-circle-fill"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php if($value->description): ?>
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <span>
                        <?php echo e($value->description); ?>

                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-12">
            <div class="k_field_widget k_field_radio k_light_label ps-3">
                <div class="k_horizontal">
                    <div class="form-check k_radio_item">
                        <input type="radio" class="form-check-input k_radio_input" name="<?php echo e($value->model); ?>" wire:model="<?php echo e($value->model); ?>" id="kg" value="kilograms"/>
                        <label class="form-check-label k_form_label" for="kg">
                            <?php echo e(__('Kg')); ?>

                        </label>
                    </div>
                    <div class="form-check k_radio_item">
                        <input type="radio" class="form-check-input k_radio_input" name="<?php echo e($value->model); ?>" wire:model="<?php echo e($value->model); ?>" id="ib" value="pounds"/>
                        <label class="form-check-label k_form_label" for="ib">
                            <?php echo e(__('Ib')); ?>

                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\uom\weight.blade.php ENDPATH**/ ?>