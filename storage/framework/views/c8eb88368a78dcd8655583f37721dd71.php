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
<?php if($value->data['parent']): ?>
<div class="mt-3 ps-3">
    <?php if($value->label): ?>
    <span>
        <?php echo e($value->label); ?> : 
    </span>
    <?php endif; ?>
    
    <?php if($this->setting->default_currency_position == 'prefix'): ?>
    <span class="col-6" style="width: 30%; margin: 0 0 12px 0;"><?php echo e($this->setting->currency->symbol); ?></span>
    <input type="<?php echo e($value->type); ?>" style="width: 50%;" wire:model="<?php echo e($value->model); ?>" min="0" class="k-input" placeholder="<?php echo e($value->placeholder); ?>" id="amount">
    <?php else: ?>
        <input type="<?php echo e($value->type); ?>" style="width: 30%;" wire:model="<?php echo e($value->model); ?>" min="0" class="k-input" placeholder="<?php echo e($value->placeholder); ?>" id="amount">
        <span class="col-6" style="width: 30%; margin: 0 0 12px 0;"><?php echo e($this->setting->currency->symbol); ?></span>
    <?php endif; ?>
    
    
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>
<?php endif; ?><?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\input\minimum-amount.blade.php ENDPATH**/ ?>