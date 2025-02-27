<div>
    <div class="gap-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <!-- Breadcrumbs -->
            <div class="gap-1 k_control_panel_breadcrumbs d-flex align-items-center order-0 h-lg-100">
            <div class="gap-1 k_form_buttons_edit d-flex">
                <!-- Create Button -->
                <button wire:click="saveUpdate" wire:target="saveUpdate" class="btn btn-primary">
                    <?php echo e(__('Save')); ?> <span wire:loading wire:target="saveUpdate">...</span>
                </button>
                <!-- Create Button -->
                <button wire:click="cancelUpdate" wire:target="cancelUpdate" class="mr-2 btn btn-secondary k_form_button_cancel">
                    <?php echo e(__('Discard')); ?> <span wire:loading wire:target="cancelUpdate">...</span>
                </button>
                <!--[if BLOCK]><![endif]--><?php if($this->change): ?>
                <span class="p-0 fs-4"><?php echo e(__('Usaved changes')); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
                <div class="min-w-0 gap-1 k_last_breadcrumb_item active align-items-center lh-sm">
                    <div class="gap-1 d-flex text-truncate">
                        <span class="min-w-0 text-truncate" id="current-page">
                            <?php echo e($this->currentPage); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions / Search Bar -->
            <div class="order-2 k_panel_control_actions d-flex align-items-center justify-content-start order-lg-1 w-100 w-lg-auto justify-content-lg-around">

            </div>

        </div>
    </div>

    <!-- Loading -->
    

</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/navbar/template/simple.blade.php ENDPATH**/ ?>