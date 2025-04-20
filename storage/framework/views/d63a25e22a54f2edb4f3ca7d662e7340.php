<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">
                <?php echo e($unitType->name); ?> <?php echo e(__('Pricing')); ?>

            </h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>
        <div class="modal-body">
            <div class="k_form_nosheet">
                <div class="k_inner_group row">
                    <div class="col-12">
                        <label class="form-label h3">
                            <?php echo e(__('How much do you want to charge?')); ?>

                        </label>

                        <?php if(count($unitPrices) > 0): ?>
                            <div class="row mt-2">
                                <?php $__currentLoopData = $unitPrices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <!-- Rate Type -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="rateType-<?php echo e($i); ?>" class="form-label">
                                            <?php echo e(__('Rate Type')); ?>

                                        </label>
                                        <select wire:model="unitPrices.<?php echo e($i); ?>.rate_type" id="rateType-<?php echo e($i); ?>" class="form-control" style="width: 200px;">
                                            <option value=""><?php echo e(__('--- Choose ---')); ?></option>
                                            <?php $__currentLoopData = $this->leaseTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ["unitPrices.$i.rate_type"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Rate -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="unitRate-<?php echo e($i); ?>" class="form-label"><?php echo e(__('Rate')); ?></label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon font-weight-bolder">
                                                <?php echo e(settings()->currency->symbol); ?>

                                            </span>
                                            <input type="number" placeholder="18,900" class="form-control <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="unitRate-<?php echo e($i); ?>" wire:model="unitPrices.<?php echo e($i); ?>.rate" style="width: 200px;">
                                        </div>
                                        <?php $__errorArgs = ["unitPrices.$i.rate"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><?php echo e(__('Including taxes and charges')); ?></span>
                                        </div>
                                    </div>

                                    <!-- Is Default -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="unitDefault-<?php echo e($i); ?>" class="form-label mb-2">
                                            <?php echo e(__('Is Default')); ?>

                                        </label>
                                        <input type="checkbox" class="form-control form-check-input <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.default'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="unitDefault-<?php echo e($i); ?>" wire:model="unitPrices.<?php echo e($i); ?>.default" wire:change="setDefault(<?php echo e($i); ?>)">
                                        <?php $__errorArgs = ["unitPrices.$i.default"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="mt-1 text-danger"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">&nbsp;</span>
                                            <span class="cursor-pointer text-end" wire:click.prevent="removePricing(<?php echo e($i); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Add Pricing Button -->
                        <span class="cursor-pointer fw-bolder border rounded p-2" wire:click.prevent="addPricing">
                            <i class="bi bi-plus-circle"></i> <?php echo e(__('Add Pricing')); ?>

                        </span>

                        <!-- Success Message -->
                        <?php if(session()->has('message')): ?>
                            <div class="alert alert-success mt-3">
                                <?php echo e(session('message')); ?>

                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

        <div class="p-0 modal-footer">
            <button class="btn btn-secondary text-uppercase" wire:click="$dispatch('closeModal')"><?php echo e(__('Close')); ?></button>
            <button class="btn btn-primary text-uppercase" wire:click.prevent="save"><?php echo e(__('Save')); ?></button>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\Properties\resources\views\livewire\modal\pricing-modal.blade.php ENDPATH**/ ?>