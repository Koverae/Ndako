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
<div>
    <li
    class="gap-2 cursor-pointer dropdown-item dropdown-hover kover-link"
    <?php if($value->isConfirm): ?>
        wire:confirm="<?php echo e($value->confirmText); ?>"
    <?php endif; ?>
    wire:click="<?php echo e($value->action); ?>"
    wire:target="<?php echo e($value->action); ?>"
>
        <i class="<?php echo e($value->icon); ?>"></i> <span><?php echo e($value->label); ?></span>
    </li>

    <?php if($value->separator): ?>
    <li><hr class="separator"></li>
    <?php endif; ?>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\button\action\dropdown.blade.php ENDPATH**/ ?>