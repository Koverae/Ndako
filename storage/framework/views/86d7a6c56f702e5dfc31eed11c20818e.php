<div>
    <div class="modal-content rounded-lg shadow-lg border-0">
        <div class="modal-header p-2 d-flex flex-row justify-content-between text-truncate mb-1">
            <h5 class="modal-title font-semibold"><?php echo e(__("Closing Register")); ?></h5>
            <span class="fw-bolder fs-4"><?php echo e($session->orders()->count() ?? 0); ?> <?php echo e(__('orders')); ?>: <?php echo e(format_currency($session->orders()->sum('total_amount') ?? 0)); ?></span>
        </div>

        <form wire:submit.prevent="open">
            <div class="modal-body p-0">
                <div class="p-3">
                    <div class="mb-1">
                        <label for="opening_cash" class="form-label fw-bold"><?php echo e(__('Opening Cash')); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo e(settings()->currency->symbol ?? '$'); ?></span>
                            <input type="number" min="0" step="0.01" wire:model.defer="opening_cash" id="opening_cash" class="form-control" placeholder="0.00" required>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['opening_cash'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger small"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <div class="mb-2">
                        <label for="opening_note" class="form-label fw-bold"><?php echo e(__('Opening Note')); ?></label>
                        <textarea wire:model.defer="opening_note" id="opening_note" class="form-control" rows="3" placeholder="<?php echo e(__('Add a note (optional)')); ?>"></textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['opening_note'];
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