<div>
    <div class="border-0 rounded-lg shadow-lg modal-content">
        <div class="modal-header">
            <h5 class="font-semibold modal-title"><?php echo e(__("Opening Control")); ?></h5>
            <button type="button" class="btn-close" wire:click="$dispatch('closeModal')" aria-label="Close"></button>
        </div>

        <form wire:submit.prevent="open">
            <div class="pt-4 pb-2 modal-body">
                <div class="mb-4">
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
                <div class="mb-3">
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
            <div class="rounded-b-lg modal-footer bg-light">
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo e(__('Open Register')); ?></button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/modal/opening-control-modal.blade.php ENDPATH**/ ?>