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
    <!--[if BLOCK]><![endif]--><?php if($value->type == 'link'): ?>
    <a wire:navigate href="<?php echo e($value->action); ?>" class="outline-none btn btn-link k_web_settings_access_rights">
        <i class="bi <?php echo e($value->icon); ?> k_button_icon"></i> <span><?php echo e($value->label); ?></span>
    </a>
    <?php elseif($value->type == 'modal'): ?>
    <span class="p-4 outline-none cursor-pointer k_web_settings_access_rights"  onclick="Livewire.dispatch('openModal', <?php echo $value->action; ?>)">
        <i class="bi <?php echo e($value->icon); ?> k_button_icon"></i> <span><?php echo e($value->label); ?></span>
    </span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <br>
</div><?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/blocks/boxes/action/simple.blade.php ENDPATH**/ ?>