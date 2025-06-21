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
<!--[if BLOCK]><![endif]--><?php if($value->parent): ?>
<div class="mt-3 ps-3" wire:transition.duration.500ms>
    <!--[if BLOCK]><![endif]--><?php if($value->label): ?>
    <span>
        <?php echo e($value->label); ?> :
    </span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <input type="<?php echo e($value->type); ?>" disabled wire:model="<?php echo e($value->model); ?>" class="w-auto k-input" placeholder="<?php echo e($value->placeholder); ?>" id="<?php echo e($value->model); ?>">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
    <i class="cursor-pointer fas fa-copy fw-bold" title="Copy"></i>
    
    
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/blocks/boxes/input/special/api.blade.php ENDPATH**/ ?>