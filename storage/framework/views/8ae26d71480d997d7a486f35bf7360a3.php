<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="row gap-1 justify-content-md-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">

    <div class="m-3 text-center">
        <h1 class="h1"><?php echo e(__('From the list below, which property category is similar to your place?')); ?></h1>
    </div>
    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">
            <div class="row">
                <!-- Category -->
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->propertyType['hotel']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" wire:click="pickCategory('<?php echo e($category['slug']); ?>')" style="min-height: 122px;">
                    <div class="p-2 border rounded" style="min-height: 122px;">
                        <h3 class="h3"><?php echo e($category['name']); ?></h3>
                        <div class="mt-3">
                            <p>
                                <?php echo e($category['description']); ?>

                            </p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>&nbsp;</span>
                            <span class="<?php echo e($this->category == $category['slug'] ? '' : 'd-none'); ?> selected-card text-end"><i class="fas fa-check-circle"></i></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                <!-- Category End -->
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/wizard/step-page/special/property/pick-category.blade.php ENDPATH**/ ?>