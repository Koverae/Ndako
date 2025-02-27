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
            <h2 class="h2">Set Up Your Branding & Preferences 🎭</h2>
            <p>Customize Ndako to reflect your brand and business identity.</p>

        </div>
        <div class="card-body">

            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <form wire:submit.prevent="">
                <?php echo csrf_field(); ?>

                <!-- Avatar -->
                <div class="p-0 m-0 k_employee_avatar rounded-cirlce">
                    <!-- Image Uploader -->
                    <!--[if BLOCK]><![endif]--><?php if($this->photo != null): ?>
                    <img src="<?php echo e($this->photo->temporaryUrl()); ?>" alt="image" class="img img-fluid">
                    <?php else: ?>
                    <img src="<?php echo e($this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/default_logo.png')); ?>" alt="image" class="img img-fluid">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!-- <small class="k_button_icon">
                        <i class="align-middle bi bi-circle text-success"></i>
                    </small>-->
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
                <!-- Avatar -->

                <div class="row ">

                    <div class="mb-4 col-lg-6 col-md-12">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" wire:model="companyEmail" class="form-control w-full" placeholder="e.g. contact@yourcompany.co.ke">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['companyEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div class="mb-4 col-lg-6 col-md-6">
                        <label class="block text-sm font-medium">Phone</label>
                        <input type="tel" wire:model="companyPhone" class="form-control w-full" placeholder="e.g. +254 745 908026">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['companyPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <div class="k_inner_group col-md-6 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">

                        <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Where is this magical place located?')); ?>

                        </div>
                    </div>
                    <div class="k_address_format w-100">
                        <div class="row">
                            <div class="col-12" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyStreet" id="" class="p-0 k-input w-100" placeholder="<?php echo e(__('Street ....')); ?>">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyCity" id="city_0" class="p-0 k-input w-100" placeholder="<?php echo e(__('City')); ?>">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <select wire:model="companyState" class="p-0 k-input w-100" id="state_id_0">
                                    <option value=""><?php echo e(__('State')); ?></option>
                                </select>
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyZip" id="zip_0" class="p-0 k-input w-100" placeholder="<?php echo e(__('ZIP')); ?>">
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                                <select wire:model="companyCountry" class="k-input w-100" id="country_id_0">
                                    <option value=""><?php echo e(__('Country')); ?></option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php echo e($value == current_company()->country_id ? 'selected' : ''); ?> value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                            </div>

                        </div>

                    </div>


                </div>


                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" <?php echo e($this->currentStep == 0 ? 'disabled' : ''); ?>><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <span class="btn cancel" wire:click="goToNextStep"><?php echo e(__('Skip')); ?></span>
                        <button type="submit" class="btn btn-primary go-next" <?php echo e($this->currentStep == count($this->steps()) - 1 ? 'disabled' : ''); ?>>
                            <span wire:loading.remove>Continue</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/onboarding/personalization.blade.php ENDPATH**/ ?>