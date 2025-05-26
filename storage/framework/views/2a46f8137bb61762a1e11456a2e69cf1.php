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

<div class="mt-3 container-fluid <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="search-container w-100 w-md-50">
            <input type="search" class="form-control" wire:model.live="search" id="" placeholder="Search guests by name or email...">
            <i class="fas fa-search"></i>
            <!--[if BLOCK]><![endif]--><?php if($this->search): ?>
            <div wire:loading wire:target="search">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})" class="gap-2 text-end btn btn-primary"><?php echo e(__('Add Guest')); ?> <i class="fas fa-user-plus"></i></span>
    </div>
    <div class="row">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->guests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-1 cursor-pointer col-12 col-sm-6 col-md-4" wire:key="guest-<?php echo e($guest->id); ?>">
            <a class="card <?php if($this->guest): ?> <?php echo e($this->guest->id == $guest->id ? 'active-pick' : ''); ?> <?php endif; ?>" wire:click="pickGuest('<?php echo e($guest->id); ?>')" wire:navigate>
                <div class="d-flex">
                    <img src="<?php echo e($guest->avatar ? Storage::url('avatars/' . $guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="<?php echo e($guest->name); ?>" class="img img-fluid" height="120px" width="120px">
                    <div class="p-2 card-body" style="flex-grow: 1; overflow: hidden;">
                        <h5 class="mb-2 card-title text-truncate"><?php echo e($guest->name); ?> <i class="bi bi-pencil-square" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: <?php echo e($guest->id); ?>})"></i></h5>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-envelope"></i> <?php echo e($guest->email); ?></span>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-phone"></i> <?php echo e($guest->phone); ?></span>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-geo"></i> <?php echo e($guest->email); ?></span>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($guest->bookings()->isActive()->count() >= 1): ?>
                <span class="badge-active">Active</span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="mb-1 col-12 text-center py-5">
            <i class="fas fa-users text-gray-400" style="font-size: 2.5rem;"></i>
            <p class="text-gray-600 mt-2">No guests found. Try adjusting your search or add a new guest.</p>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/booking/pick-guest.blade.php ENDPATH**/ ?>