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
    <!-- Picking Policy -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="<?php echo e($value->key); ?>">

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
            </div>
            <div class="mt16">
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <div class=" content-group">
                        <input id="annual_inventory_day_0 " type="text" class="w-5 k_input" wire:model="annual_inventory_day">
                        <select class="k_input w-50" id="" wire:model="annual_inventory_month">
                            <option value=""></option>
                            <option value="january"><?php echo e(__('January')); ?></option>
                            <option value="february"><?php echo e(__('February')); ?></option>
                            <option value="march"><?php echo e(__('March')); ?></option>
                            <option value="april"><?php echo e(__('April')); ?></option>
                            <option value="may"><?php echo e(__('May')); ?></option>
                            <option value="june"><?php echo e(__('June')); ?></option>
                            <option value="july"><?php echo e(__('July')); ?></option>
                            <option value="august"><?php echo e(__('August')); ?></option>
                            <option value="september"><?php echo e(__('September')); ?></option>
                            <option value="october"><?php echo e(__('October')); ?></option>
                            <option value="november"><?php echo e(__('November')); ?></option>
                            <option value="december"><?php echo e(__('December')); ?></option>
                        </select>

                    </div>
                </div>
            </div>
        </div>

    </div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\inventory\annual-inventory.blade.php ENDPATH**/ ?>