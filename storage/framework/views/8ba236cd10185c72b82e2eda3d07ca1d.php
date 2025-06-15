<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'model',
    'id'
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
    'model',
    'id'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-1 col-md-6" style="border-left: 4px solid #0E6163">
    <div class="card">
        <div class="p-2 card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a class="text-decoration-none flex-grow-1" wire:navigate href="<?php echo e($this->showRoute($id)); ?>">
                    <h5 class="m-0 mb-2 card-title"> <?php echo e($model[$value->title]); ?></h5>
                </a>

                <span class="badge bg-info text-white"><?php echo e(__('Opening Control')); ?></span>
                

                <div class="dropdown ms-2">
                    <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear fs-3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a wire:navigate href="<?php echo e(route('orders.lists')); ?>" class="dropdown-item">
                            <?php echo e(__('Orders')); ?>

                        </a>
                        <a wire:navigate href="<?php echo e(route('pos-sessions.lists')); ?>" class="dropdown-item">
                            <?php echo e(__('Sessions')); ?>

                        </a>
                    </div>
                </div>
            </div>

            <!-- Displaying POS details -->
            <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                <span><?php echo e(__('Close')); ?></span>
                <span>06/15/2025</span>
            </div>

            <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                <span><?php echo e(__('Balance')); ?></span>
                <span><?php echo e(format_currency(126700)); ?></span>
            </div>

            <div class="gap-2 d-flex">
                <a href="<?php echo e(route('pos.ui', $id)); ?>" target="_blank" class="mt-2 btn btn-primary"><?php echo e(__('Open Session')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/table/card/template/pos.blade.php ENDPATH**/ ?>