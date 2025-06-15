<div>
    <div class="modal-content rounded-lg shadow-lg border-0">
        <div class="modal-header p-2 d-flex flex-row justify-content-between text-truncate mb-1">
            <h5 class="modal-title font-semibold"><?php echo e(__("Closing Register")); ?></h5>
            <span class="fw-bolder fs-4"><?php echo e($session->orders()->count() ?? 0); ?> <?php echo e(__('orders')); ?>: <?php echo e(format_currency($session->orders()->sum('total_amount') ?? 0)); ?></span>
        </div>

        <form wire:submit.prevent="open">
            <div class="modal-body p-0">
                <!-- Payment Method Overview -->
                <div class="payment-methods-overview p-3">

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span class="fs-3 fw-bold"><?php echo e(__('Cash')); ?></span>
                        <span class="fs-3"><?php echo e(format_currency(126700)); ?></span>
                    </div>
                    <div class="pl-2">
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted"><?php echo e(__('Opening')); ?></span>
                            <span class="fs-4 text-muted"><?php echo e(format_currency($session->starting_balance ?? 0)); ?></span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted"><i class="fas fa-caret-right"></i> <?php echo e(__('Cash In/Out')); ?></span>
                            <span class="fs-4 text-muted"><?php echo e(format_currency(126700)); ?></span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted"><?php echo e(__('Counted')); ?></span>
                            <span class="fs-4 text-muted"><?php echo e(format_currency($closing_cash)); ?></span>
                        </div>
                        <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                            <span class="fs-4 text-muted"><?php echo e(__('Difference')); ?></span>
                            <span class="fs-4 text-muted"><?php echo e(format_currency(126700)); ?></span>
                        </div>
                    </div>

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span class="fs-3 fw-bold"><?php echo e(__('Card')); ?></span>
                        <span class="fs-3"><?php echo e(format_currency(126700)); ?></span>
                    </div>

                    <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                        <span class="fs-3 fw-bold"><?php echo e(__('Paystack')); ?></span>
                        <span class="fs-3"><?php echo e(format_currency(126700)); ?></span>
                    </div>

                </div>
                <div class="p-3">
                    <div class="mb-1">
                        <label for="closing_cash" class="form-label fw-bold"><?php echo e(__('Opening Cash')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo e(settings()->currency->symbol ?? '$'); ?></span>
                            <input type="number" min="0" step="0.01" wire:model.live="closing_cash" id="closing_cash" class="form-control" placeholder="0.00" required>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['closing_cash'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger small"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="mb-2">
                        <label for="closing_note" class="form-label fw-bold"><?php echo e(__('Opening Note')); ?></label>
                        <textarea wire:model.defer="closing_note" id="closing_note" class="form-control" rows="3" placeholder="<?php echo e(__('Add a note (optional)')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['closing_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger small"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
            <div class="modal-footer left-0 bg-light rounded-b-lg">
                <button type="submit" class="btn btn-primary fs-3"><?php echo e(__('Close Register')); ?></button>
                <button type="button" class="btn btn-secondary fs-3" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/modal/closing-register-modal.blade.php ENDPATH**/ ?>