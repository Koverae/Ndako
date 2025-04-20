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
    <span>
        <?php echo e($value->label); ?> :
    </span>
    <?php endif; ?>

    <select wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->model); ?>" class="k-input">
        <option value=""></option>
        <?php $__currentLoopData = $value->data['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <span class="mt-3 d-block">
        <?php $__currentLoopData = $value->data['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="cursor-pointer badge rounded-pill k_web_settings_users">
            <?php echo e($text); ?>

            <i wire:click.prevent="" wire:confirm="Êtes-vous sûr de vouloir annuler l'invitation de ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de"></i>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </span>

    
    
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\boxes\input\tag\select-tag-input.blade.php ENDPATH**/ ?>