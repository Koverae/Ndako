<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e(__('Add Guest')); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group row">
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <span for="" class="k_form_label font-weight-bold"><?php echo e(__('Name')); ?></span>
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="e.g. Wanjiku Mwangi">
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
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        <!--[if BLOCK]><![endif]--><?php if($this->photo != null): ?>
                        <img src="<?php echo e($this->photo->temporaryUrl()); ?>" alt="image" class="img img-fluid">
                        <?php else: ?>
                        <img src="<?php echo e($this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="image" class="img img-fluid">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!-- Image selector -->
                        <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" onclick="document.getElementById('photo').click();">
                                <i class="bi bi-pencil"></i>
                                <input type="file" wire:model.blur="photo" id="photo" style="display: none;" />
                            </span>
                            <!--[if BLOCK]><![endif]--><?php if($this->photo || $this->image_path): ?>
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="$cancelUpload('photo')" wire:target="$cancelUpload('photo')">
                                <i class="bi bi-trash"></i>
                            </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Email')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="email" wire:model="email" class="k-input" id="model_0" placeholder="e.g. wanjikumwangi@koverae.com">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Date of Birth')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="date" wire:model="birthday" class="k-input" id="model_0">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Gender')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <select wire:model="gender" class="k-input" id="model_0">
                        <option value=""></option>
                        <option value="male"><?php echo e(__('Male')); ?></option>
                        <option value="female"><?php echo e(__('Female')); ?></option>
                    </select>
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Job')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="job" class="k-input" id="model_0" placeholder="e.g. Software Engineer">
                </div>
              </div>
              <div class="d-flex col-12" style="margin-bottom: 8px;">
                <!-- Input Label -->
                <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                    <label class="k_form_label">
                      <?php echo e(__('Address')); ?> :
                    </label>
                </div>
                <!-- Input Form -->
                <div class="k_cell k_wrap_input flex-grow-1">
                    <input type="text" wire:model="address" class="k-input" id="model_0" placeholder="e.g. 123 Riverside Drive, Westlands, Nairobi, Kenya">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        <button class="btn btn-primary" wire:click.prevent="addGuest"><?php echo e(__('Add')); ?></button>
      </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/ChannelManager\resources/views/livewire/modal/add-guest-modal.blade.php ENDPATH**/ ?>