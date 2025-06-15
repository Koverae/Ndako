<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e(__('Add Guest')); ?></h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>

        <div class="modal-body">
            <div class="container-fluid mt-3">

                <!-- Search & Add -->
                <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="w-100 w-md-50">
                        <input type="search" class="form-control" wire:model.live="guestSearch"
                               placeholder="Search guests by name or email...">
                        <!--[if BLOCK]><![endif]--><?php if($this->guestSearch): ?>
                        <div wire:loading wire:target="guestSearch">
                            <i class="fas fa-spinner fa-spin mt-1"></i>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <button class="btn btn-primary"
                            onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})">
                        <?php echo e(__('Add Guest')); ?> <i class="fas fa-user-plus ms-1"></i>
                    </button>
                </div>

                <!-- Guest Cards -->
                <div class="row g-3">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $guests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-12 col-md-6" wire:key="guest-<?php echo e($guest->id); ?>">
                        <div class="card shadow-sm hover-shadow-sm border cursor-pointer" wire:click="selectGuest('<?php echo e($guest->id); ?>')">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-2 text-truncate">
                                        <?php echo e($guest->name); ?>

                                        <i class="bi bi-pencil-square ms-2 text-muted small"
                                           onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: <?php echo e($guest->id); ?>})"></i>
                                    </h5>
                                    <!--[if BLOCK]><![endif]--><?php if($guest->bookings()->isActive()->count() >= 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <p class="mb-1 text-truncate"><i class="bi bi-envelope me-1"></i> <?php echo e($guest->email); ?></p>
                                <p class="mb-1 text-truncate"><i class="bi bi-phone me-1"></i> <?php echo e($guest->phone); ?></p>
                                <p class="mb-0 text-truncate"><i class="bi bi-geo me-1"></i> <?php echo e($guest->address ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No guests found. Try adjusting your search or add a new guest.</p>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/livewire/modal/guest-modal.blade.php ENDPATH**/ ?>