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
<div>
    <li class="cursor-pointer dropdown-item kover-navlink" wire:click="<?php echo e($value->action); ?>" wire:target="<?php echo e($value->action); ?>">
        <?php echo $value->label; ?>

    </li>
    <!--[if BLOCK]><![endif]--><?php if($value->separator): ?>
    <li><hr class="separator"></li>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/button/action/special-button.blade.php ENDPATH**/ ?>