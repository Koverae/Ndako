<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="row gap-1 justify-content-md-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Identity Verification 🔒</h2>
            <p>Please upload a valid government-issued ID and an optional selfie for verification.</p>
        
            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div class="card-body">
            
            <form wire:submit.prevent="submitIdentity">
                <div class="mb-3">
                    <label for="document_type" class="form-label">Select Document Type</label>
                    <select class="form-select" wire:model="document_type" required>
                        <option value="">-- Choose --</option>
                        <option value="id-card">National ID Card</option>
                        <option value="passport">Passport</option>
                        <option value="driver-license">Driver's License</option>
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Document</label>
                    <input type="file" class="form-control" wire:model="document" required>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <!-- Document Preview -->
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Selfie (Optional)</label>
                    <input type="file" class="form-control" wire:model="selfie">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selfie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- Selfie Preview -->
                    <!--[if BLOCK]><![endif]--><?php if($this->selfiePreview): ?>
                        <div class="mt-3">
                            <p class="fw-bold">Selfie Preview:</p>
                            <img src="<?php echo e($this->selfiePreview); ?>" alt="Selfie Preview" class="img-fluid shadow-sm rounded" style="max-height: 150px; width: 150px;">
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" <?php echo e($this->currentStep == 0 ? 'disabled' : ''); ?>><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        
                        <button type="submit" class="btn btn-primary go-next" <?php echo e($this->currentStep == count($this->steps()) - 1 ? 'disabled' : ''); ?>>
                            <span wire:loading.remove class="uppercase">Go Next</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>
                
            </form>
            
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/onboarding/identity.blade.php ENDPATH**/ ?>