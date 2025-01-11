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
<!--[if BLOCK]><![endif]--><?php if($value->label): ?>
<span for="" class="k_form_label font-weight-bold"><?php echo e($value->label); ?></span>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<h1 class="flex-row d-flex align-items-center">
    <input type="<?php echo e($value->type); ?>" wire:model="<?php echo e($value->model); ?>" class="k-input" id="name-k" placeholder="<?php echo e($value->placeholder); ?>" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
    <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$value->model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
</h1>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/form/input/ke-title.blade.php ENDPATH**/ ?>