<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e(__('Select Service Type')); ?></h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>

        <div class="modal-body">
            <div class="container-fluid mt-3">
                <div class="row g-3 justify-content-center">

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card text-center border shadow-sm h-100 cursor-pointer hover-shadow-sm"
                                 wire:click="selectService('<?php echo e($key); ?>')">
                                <div class="card-body py-4">
                                    <div class="mb-2 fs-2 " style="color: #04464A">
                                        <i class="<?php echo e($service['icon']); ?>"></i>
                                    </div>
                                    <h5 class="card-title mb-0"><?php echo e(__($service['label'])); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/modal/service-type-modal.blade.php ENDPATH**/ ?>