<div>
    <style>
        .k-file-upload {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            transition: border-color 0.2s ease;
        }
        .k-file-upload:hover {
            border-color: #097274;
        }
        .file-display {
            min-height: 40px;
            border-radius: 4px;
            background: #f8f9fa;
        }
        .file-name {
            font-size: 0.9rem;
            color: #1f2937;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-primary {
            background: #097274;
            border: none;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: #07595a;
        }
        .btn-outline-danger {
            border-color: #dc2626;
            color: #dc2626;
            padding: 0.2rem 0.5rem;
        }
        .btn-outline-danger:hover {
            background: #dc2626;
            color: #fff;
        }
        .error {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }
        @media (max-width: 576px) {
            .k-file-upload {
                width: 100%;
            }
            .file-name {
                max-width: 150px;
            }
            .file-display {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-break">Ndako</h4>
                <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
            </div>
            <form>
                <div class="modal-body position-relative">

                <div class="k_form_renderer k_form_nosheet k_form_editable d-block">

                    <div class="k_inner_group">

                        <!-- Emails -->
                        <div class="mb-3">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    <?php echo e(__('Recipients')); ?> :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="gap-3 k_cell k_wrap_input flex-grow-1">
                                <input type="text" wire:model="email" class="k-input" style="padding: 1px 0 0; width: 75%;" id="date_0">

                                <span class="gap-2 btn btn-primary" wire:click="addEmail">
                                    <i class="fas fa-user-plus"></i> <?php echo e(__('Add')); ?>

                                </span>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <span class="loader-spin-1" wire:loading wire:target="addEmail"></span>

                            <div class="mt-2 w-75 d-flex">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $recipient_emails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $email): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="background-color: #097274;">
                                    < <?php echo e($email); ?> >
                                    <i class="bi bi-x cancelled_icon" wire:click="removeEmail('<?php echo e($email); ?>')" wire:target="removeEmail('<?php echo e($email); ?>')" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Remove')); ?>"></i>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                            </div>
                        </div>
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    Sujet :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1">
                                <input type="text" wire:model.live="subject" class="k-input" style="padding: 1px 0 0" id="date_0">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                        </div>
                        
                        <div x-data="{ content: <?php if ((object) ('content') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('content'->value()); ?>')<?php echo e('content'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('content'); ?>')<?php endif; ?> }">
                            <div class="koverae-editable-editor koverae-editor k-input" x-on:blur="content = $event.target.innerHTML" contenteditable="true"><?php echo $content; ?></div>
                        </div>

                    </div>

                    <!-- Attachment -->
                    <div class="mb-3 k_inner_group" style="margin-bottom: 25px;">
                        <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                            <label for="file" class="k_form_label"><?php echo e(__('Attachment (Optional PDF)')); ?>:</label>
                        </div>
                        <div class="k_cell k_wrap_input flex-grow-1">
                            <div class="k-file-upload" style="width: 75%;">
                                <input type="file" wire:model="file" class="d-none" id="file" accept=".pdf">
                                <div class="p-2 file-display d-flex align-items-center justify-content-between">
                                    <!--[if BLOCK]><![endif]--><?php if($file || $attachment): ?>
                                        <div class="gap-2 d-flex align-items-center">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 1.6rem;"></i>
                                            <span class="mb-3 file-name text-900">
                                                <?php echo e($file ? $file->getClientOriginalName() : basename($attachment)); ?>

                                            </span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearFile" title="<?php echo e(__('Remove')); ?>">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e(__('No file selected')); ?></span>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('file').click()">
                                            <?php echo e(__('Choose File')); ?>

                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>

                    <div class="mt-6 k_inner_group">
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    Modèle d'email :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1">
                                <select wire:model.blur="template_id" class="k-input" style="padding: 1px 10px 1px 0; width: 372px;" id="model_0">
                                    <option value=""></option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($t['id']); ?>"><?php echo e($t['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select><!--[if BLOCK]><![endif]--><?php $__errorArgs = ['template_id'];
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
                    <button wire:click="sendEmail" class="btn btn-primary">Send <i class="bi bi-send-fill"></i></button>
                    <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
                </div>
            </form>
        </div>

</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/modal/send-by-email-modal.blade.php ENDPATH**/ ?>