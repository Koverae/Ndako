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
<button  title="<?php echo e(ucfirst($value->key)); ?> view" class="k_switch_view btn btn-secondary <?php echo e($value->key == $this->view_type ? 'active' : ''); ?> k_list" wire:click.prevent="switchView('<?php echo e($value->key); ?>')">
    <i class="bi <?php echo e($value->icon); ?>"></i>
</button>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/navbar/switch-button.blade.php ENDPATH**/ ?>