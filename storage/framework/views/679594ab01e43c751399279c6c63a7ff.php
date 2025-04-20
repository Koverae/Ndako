<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value'
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
    'value'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
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
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <?php if($value->label): ?>
        <label class="k_form_label">
            <?php echo e($value->label); ?>

            <?php if($value->help): ?>
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="<?php echo e($value->help); ?>"></i></sup>
            <?php endif; ?>
        </label>
        <?php endif; ?>
    </div>
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1">

        <?php if(settings()->default_currency_position == 'prefix'): ?>
            <span class="mt-0" style="margin-right: 10px; "><?php echo e(settings()->currency->symbol); ?></span>
            <input type="<?php echo e($value->type); ?>" wire:model="<?php echo e($value->model); ?>" class="p-0 k-input w-100" placeholder="<?php echo e($value->placeholder); ?>" id="date_0" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
        <?php else: ?>
            <input type="<?php echo e($value->type); ?>" wire:model="<?php echo e($value->model); ?>" class="p-0 k-input" placeholder="<?php echo e($value->placeholder); ?>" id="date_0" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
            <span class="" style="margin-right: 10px; "><?php echo e(settings()->currency->symbol); ?></span>
        <?php endif; ?>
        <?php $__errorArgs = [$value->model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\form\input\minimum-amount.blade.php ENDPATH**/ ?>