<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e(__('Add Expense')); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold"><?php echo e(__('Name')); ?></span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="e.g. Monthly internet subscription - Safaricom Fibre">
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
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


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Expense Category')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="category" class="k-input" id="model_0">
                        <option value=""><?php echo e(__("----- Select Category ------")); ?></option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categoryOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Property')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="property" class="k-input" id="model_0">
                        <option value=""><?php echo e(__("----- Select Property ------")); ?></option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = current_company()->properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($property->id); ?>" <?php echo e($property->id == current_property()->id ? 'selected' : ''); ?>><?php echo e($property->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Room/Unit')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="propertyUnit" class="k-input" id="model_0">
                        <option value=""><?php echo e(__("----- Select Room/Unit ------")); ?></option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $unitOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?> - <?php echo e($unit->unitType->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>
              </div>


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Amount')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="number" wire:model="amount" class="k-input" id="model_0" placeholder="e.g. 4500.00">
                </div>
              </div>


              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Date')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="date" wire:model="date" class="k-input" id="model_0">
                </div>
              </div>

              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Note')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="note" class="k-input" id="model_0" placeholder="e.g. 123 Riverside Drive, Westlands, Nairobi, Kenya">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        <button class="btn btn-primary" wire:click.prevent="saveExpense"><?php echo e(__('Add')); ?></button>
      </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/RevenueManager\resources/views/livewire/modal/add-expense-modal.blade.php ENDPATH**/ ?>