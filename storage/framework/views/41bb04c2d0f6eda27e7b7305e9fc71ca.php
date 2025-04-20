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

<div class="mt-3 ps-3">
    <?php if($value->label): ?>
    <span class="" style="margin-right: 5px;">
        <?php echo e($value->label); ?>

    </span>
    <?php endif; ?>
    <div class="d-inline-block">
        <input type="<?php echo e($value->type); ?>" wire:model="<?php echo e($value->model); ?>" class="form-check-input" style="" id="<?php echo e($value->model); ?>" onclick="checkStatus(this)" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
    </div>
    
    
    
</div><?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\input\checkbox.blade.php ENDPATH**/ ?>