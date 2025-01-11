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
<?php
    $users = \App\Models\User::isCompany(current_company()->id)->get();
?>

<div class="mt-3 container-fluid <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="search" class="w-50 form-control" wire:model.live="search" id="" placeholder="Search guests by name or email...">
        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})" class="gap-2 text-end btn btn-primary"><?php echo e(__('Add Guest')); ?> <i class="fas fa-user-plus"></i></span>
    </div>
    <div class="row">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $this->guests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-1 cursor-pointer col-sm-3" wire:click="pickGuest('<?php echo e($guest->id); ?>')">
            <a class="card <?php if($this->guest): ?> <?php echo e($this->guest->id == $guest->id ? 'active-pick' : ''); ?> <?php endif; ?>" wire:navigate>
                <div class="d-flex">
                    <img src="<?php echo e($guest->avatar ? Storage::url('avatars/' . $guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="<?php echo e($guest->name); ?>" class="img img-fluid" height="120px" width="120px">
                    <div class="p-2 card-body text-truncate">
                        <h5 class="mb-2 card-title"><?php echo e($guest->name); ?></h5>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-envelope"></i> <?php echo e($guest->email); ?></span> <br>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-phone"></i> <?php echo e($guest->phone); ?></span> <br>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-geo"></i> <?php echo e($guest->email); ?></span> <br>
                        
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="mb-1 col-sm-12" style="height: 400px;">
                No data...
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/wizard/step-page/special/pick-guest.blade.php ENDPATH**/ ?>