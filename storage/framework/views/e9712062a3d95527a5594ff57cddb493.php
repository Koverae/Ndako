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
    <!-- Customer Portal -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="<?php echo e($value->key); ?>">

        <!-- Left pane -->
        <?php if($value->checkbox): ?>
        <div class="k_setting_left_pane">
            <div class="k_field_widget k_field_boolean">
                <div class="k-checkbox form-check d-inline-block">
                    <input type="checkbox" wire:model.live="<?php echo e($value->model); ?>" class="form-check-input" onclick="checkStatus(this)">
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12">
                <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                    <span><?php echo e($value->label); ?></span>
                    <?php if($value->help): ?>
                    <a href="<?php echo e($value->help); ?>" target="__blank" title="documentation" class="k_doc_link">
                        <i class="bi bi-question-circle-fill"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <span>
                        <?php echo e($value->description); ?>

                    </span>
                </div>
                <?php if($this->has_storage_locations): ?>
                <div class="mt8" style="height: 23px;">
                    <button class="outline-none btn btn-link">
                        <i class="bi bi-arrow-right k_button_icon"></i> <span><?php echo e(__('Locations')); ?></span>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\inventory\storage-location.blade.php ENDPATH**/ ?>