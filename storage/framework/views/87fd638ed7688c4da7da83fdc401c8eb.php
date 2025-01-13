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
    <div class="m-2 d-flex justify-content-between">
        <div class="border shadow-sm col-12 col-md-8 card">
            <div class="p-4 card-body">
                
                <!-- Property Summary -->
                <div class="mb-4 ">
                    <h2 class="h2"><i class="fas fa-hotel"></i> Property Summary</h2>
                    <ul class="list-unstyled fs-3">
                        <li><strong>Property Name:</strong> <?php echo e($this->name); ?></li>
                        <li><strong>Type:</strong> <?php echo e($this->type->name); ?></li>
                        <li class="<?php echo e($this->street ? '' : 'd-none'); ?>"><strong>Location:<?php echo e($this->street); ?>, <?php echo e($this->city); ?></strong> </li>
                        <li><strong>Number of Floors:</strong> <?php echo e($this->floors); ?></li>
                        <li>
                            <strong>Room Types:</strong>
                            <br>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->propertyUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="ml-2"><?php echo e($unit['unitName']); ?>: <?php echo e($unit['numberUnits']); ?> Units (<?php echo e(format_currency($unit['price'])); ?> each)</span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span><?php echo e(__('No room types...')); ?></span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </li>
                        <li><strong>Amenities:</strong> </li>
                    </ul>
                </div>
                <div class="d-flex justify-content-between">
                    <span><?php echo e(__('Is everything okay?')); ?></span>
                    <button type="submit" wire:click='confirm' class="btn btn-primary text-end">Confirm 👌🏾</button>
                </div>
                <!-- Property Summary End -->
            </div>
        </div>
        <div class="d-none d-lg-block col-lg-4">
            <img src="<?php echo e(asset('assets/images/illustrations/kwame-bot/kwame-6.svg')); ?>" alt="" class="img-fluid text-end">
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/wizard/step-page/special/property/confirmation.blade.php ENDPATH**/ ?>