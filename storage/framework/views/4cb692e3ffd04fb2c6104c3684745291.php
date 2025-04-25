<?php $__env->startSection('title', "Import File"); ?>

<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::navbar.control-panel.import-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1008576848-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>
<!-- Page Content -->
<section class="w-100">
    <div class="empty k_nocontent_help h-100">
        <img src="<?php echo e(asset('assets/images/illustrations/file-icon.svg')); ?>"style="height: 200px" alt="">
        <p class="empty-title"><?php echo e(__('Drop or upload a file to import')); ?></p>
        <p class="empty-subtitle"><?php echo e(__('Excel files are recommended as formatting is automatic. But, you can also use .csv files')); ?></p>
        
        <a href="#" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2">
            <i class="fas fa-download"></i> <?php echo e(__('Import Template for Units')); ?>

        </a>
        
        
        <form wire:submit.prevent="import" class="p-2 mb-2">
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                <input id="file" type="file" wire:model="file" class="mt-1 block w-full" />
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
    
            <div class="mb-4">
                <label for="model" class="block text-sm font-medium text-gray-700">Selected Model <?php echo e(Str::headline($modelSlug)); ?></label>
                <select id="model" wire:model="model" class="mt-1 block w-full">
                    <option></option>
                    <option value="\Modules\Properties\Models\Property\PropertyUnit::class"><?php echo e(__('Property Units')); ?></option>
                    <!-- Add more models as needed -->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
    
            <button type="submit" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2 w-100">Import</button>
        </form>
    
        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="mt-4 text-green-600"><?php echo e(session('message')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
        <?php if(session()->has('error')): ?>
            <div class="mt-4 text-red-600"><?php echo e(session('error')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <p>Need Help? <a href="#" style="color: #0E6163;">Import FAQ</a></p>
        
    </div>
</section>
<!-- Page Content --><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/import-file.blade.php ENDPATH**/ ?>