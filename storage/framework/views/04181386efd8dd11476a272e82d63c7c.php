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
<!--[if BLOCK]><![endif]--><?php if($value->parent): ?>
<div class="mt-3 ps-3">
    <!--[if BLOCK]><![endif]--><?php if($value->label): ?>
    <span>
        <?php echo e($value->label); ?> :
    </span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


    <input type="text" wire:model="<?php echo e($value->model); ?>" class="w-auto k-input" placeholder="<?php echo e($value->placeholder); ?>" id="<?php echo e($value->model); ?>">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold" wire:click="addDomain" wire:target="addDomain"></i>

    <span class="mt-3 d-block">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->authorizedDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($domain); ?>" target="__blank" class="cursor-pointer badge rounded-pill k_web_settings_users">
            <i class="fas fa-external-link-alt fs-4"></i>
            <?php echo e($domain); ?>

            <i wire:click.prevent="removeDomain('<?php echo e($domain); ?>')" wire:target="removeDomain" wire:confirm="<?php echo e(__('Are you sure you want to remove this domain?')); ?>" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Remove this domain')); ?>"></i>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </span>

</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/blocks/boxes/input/special/authorized-domain.blade.php ENDPATH**/ ?>