<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'status'
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
    'status'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="<?php echo e($value->parent ? 'd-none' : ''); ?>">
    <button class="d-none d-lg-inline-flex <?php echo e($this->status == $value->primary ? 'btn btn-primary active' : ''); ?>" type="button" wire:click="<?php echo e($value->action); ?>" wire:target="<?php echo e($value->action); ?>"  id="top-button">
        <span>
            <?php echo e($value->label); ?> <span wire:loading wire:target="<?php echo e($value->action); ?>" >...</span>
        </span>
    </button>
    <li class="d-lg-none"><a class="dropdown-item" wire:click="<?php echo e($value->action); ?>" wire:target="<?php echo e($value->action); ?>"><?php echo e($value->label); ?> <span wire:loading wire:target="<?php echo e($value->action); ?>" >...</span></a></li>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/form/button/action-bar/simple.blade.php ENDPATH**/ ?>