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

        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12">
                <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                    <span><?php echo e($value->label); ?></span>
                </div>
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <span>
                        <?php echo e($value->description); ?>

                    </span>
                </div>
            </div>
            <div class="mt16">
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted" data-bs-toggle="tooltip" data-bs-placement="right" >
                    <!-- What is ordered -->
                    <div>
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model.live="<?php echo e($value->model); ?>" name="<?php echo e($value->model); ?>" id="on_invitation" value="on_invitation" onclick="checkStatus(this)"/>
                            <label class="form-check-label k_form_label" for="on_invitation">
                                <?php echo e(__('Sur Invitation')); ?>

                            </label>
                        </div>
                    </div>
                    <!-- What is free_signup -->
                    <div>
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model.live="<?php echo e($value->model); ?>" name="<?php echo e($value->model); ?>" id="free_signup" value="free_signup" onclick="checkStatus(this)"/>
                            <label class="form-check-label k_form_label" for="free_signup">
                                <?php echo e(__('Inscription gratuite')); ?>

                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\ratio\customer-account.blade.php ENDPATH**/ ?>