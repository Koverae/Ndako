<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e(__('Add Language')); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        <div class="k_form_nosheet">
            <div class="k_inner_group">
              <div class="d-flex" style="margin-bottom: 8px;">
                  <!-- Input Label -->
                  <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                      <label class="k_form_label">
                        <?php echo e(__('Language')); ?> :
                      </label>
                  </div>
                  <!-- Input Form -->
                  <div class="k_cell k_wrap_input flex-grow-1">
                      <select wire:model.blur="language" class="k-input" style="padding: 1px 10px 1px 0; width: 372px;" id="model_0">
                          <option value=""></option>
                          <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($language->id); ?>"> <img src="<?php echo e(asset("assets/images/flags/". $language->icon .".svg")); ?>" alt=""> <?php echo e($language->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                      </select><!--[if BLOCK]><![endif]--><?php $__errorArgs = ['language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                  </div>
              </div>
            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
        <button class="btn btn-primary" wire:click.prevent="addLanguage('<?php echo e($language->id); ?>')" onclick="Livewire.dispatch('openModal', {component: 'settings::modal.switch-language-modal', arguments: { language: <?php echo e($this->language); ?> }})"><?php echo e(__('Add')); ?></button>
      </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/Settings\resources/views/livewire/modal/add-language-modal.blade.php ENDPATH**/ ?>