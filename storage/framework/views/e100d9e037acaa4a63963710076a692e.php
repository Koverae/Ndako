<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e(__('Add')); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-100 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold"><?php echo e(__('Title')); ?></span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="title" class="k-input" id="name-k" placeholder="e.g. Prepare Room #A03">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </h1>
                    </div>
                </div>
                <div class="mt-2 d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Description')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <textarea wire:model="description" id="description" class="p-0 m-0 textearea k-input"></textarea>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Type')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="type" id="" class="k-input">
                            <option value=""></option>
                            <option value="task"><?php echo e(__('Task')); ?></option>
                            <option value="situation"><?php echo e(__('Situation')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Room')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="room" id="" class="k-input">
                            <option value=""></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($room->id); ?>"><?php echo e($room->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Priority')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="priority" id="" class="k-input">
                            <option value="low"><?php echo e(__('Low')); ?></option>
                            <option value="medium"><?php echo e(__('Medium')); ?></option>
                            <option value="high"><?php echo e(__('High')); ?></option>
                            <option value="critical"><?php echo e(__('Critical')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12 <?php echo e($this->type === 'task' ? 'd-none' : ''); ?>" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Reported By')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model.live="reportedBy" id="" class="k-input">
                            <option value=""></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = current_company()->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
                <div class="d-flex col-12" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                        <?php echo e(__('Assigned To')); ?> :
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model="assignedTo" id="" class="k-input">
                            <option value=""></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = current_company()->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>

            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        <button class="btn btn-primary" wire:click.prevent="addWorkItem"><?php echo e(__('Add')); ?></button>
      </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/modal/add-work-item-modal.blade.php ENDPATH**/ ?>