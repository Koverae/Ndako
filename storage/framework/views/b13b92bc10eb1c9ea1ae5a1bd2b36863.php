<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'data'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'value',
    'data'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
    <!-- Left Side -->
    <div class="k_inner_group col-md-6 col-lg-6">
        <!-- separator -->
        <div class="g-col-sm-2">

            <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
                    <?php echo e($value->label); ?>

            </div>
        </div>
        <!-- Photo Box -->
        <div class="mb-3 d-flex">
            <div class="gap-2 k-gallery-box">

                <span class="inline-flex bg-gray-200 border rounded k-image-box" onclick="document.getElementById('<?php echo e($this->inputId); ?>').click();">
                    <img src="<?php echo e(asset('assets/images/default/placeholder.png')); ?>" class="inline-flex rounded image">
                    
                    <input type="file" wire:model="newImages" id="<?php echo e($this->inputId); ?>" multiple style="display: none;">
                </span>

                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->existingImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="bg-gray-200 border rounded k-image-box">
                    <img src="<?php echo e(asset('storage/' . $image)); ?>" class="inline-flex rounded image" alt=""title="Tooltip on top">
                    
                    <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle">
                            
                        </span>
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="removeImage(<?php echo e($index); ?>)" wire:target="removeImage(<?php echo e($index); ?>)">
                            <i class="bi bi-trash"></i>
                        </span>
                    </div>
                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Loading -->
            <div wire:loading wire:target="newImages" class="absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center rounded-lg">
                <div class="text-white text-xs">Uploading...</div>
            </div>
            <div wire:loading wire:target="removeImage" class="absolute inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center rounded-lg">
                <div class="text-white text-xs">Uploading...</div>
            </div>
            <!-- Loading End -->

            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['newImages.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="mt-1 text-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <!-- Photo Box -->

        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->inputs(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!--[if BLOCK]><![endif]--><?php if($input->group == $value->key): ?>
                <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $input->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => $input->data,'value' => $input]); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    </div>


<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/form/tab/group/gallery-photo.blade.php ENDPATH**/ ?>