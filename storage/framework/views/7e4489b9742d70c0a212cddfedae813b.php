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

    <div class="border shadow-sm col-12 col-md-8 mt-2 card">
        <div class="card-header d-block">
            <h2 class="h2">Add Your First Property 🏡</h2>
            <p>Let's get started by adding your first property. This helps you manage bookings and operations efficiently.</p>

        </div>
        <div class="card-body">
            <form wire:submit.prevent="addProperty">
                <?php echo csrf_field(); ?>
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="What’s this property called?" >
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

                <div class="mb-3">
                    <label for="type" class="form-label h3">
                        <?php echo e(__('What type of property is it?')); ?>

                    </label>
                    <select class="form-select" wire:model="type" required>
                        <option value="">-- Choose --</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->propertyTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="row align-items-start mb-2">
                    <div class="d-flex">
                        <textarea wire:model="description" class="p-0 m-0 textearea k-input" placeholder="<?php echo e(__('Tell me a bit about your property. What makes it awesome?')); ?>" id="description">
                        </textarea>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <div class="mb-3 col-md-12">
                    <label for="floors" class="form-label h3">
                        <?php echo e(__('How many floors are we stacking?')); ?>

                    </label>
                    <div class="gap-2 d-flex">
                        <input type="number" class="form-control <?php $__errorArgs = ['floors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="floors" wire:model.live="floors" style="width: 100px;">
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['floors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mt-1 text-danger">
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <!-- Floors -->
                    <div class="row <?php echo e($this->floors >= 1 ? '' : 'd-none'); ?>">
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < $this->floors; $i++): ?>
                            <div class="gap-2 mt-2 mb-2 col-12 d-flex align-items-center">
                                <div class="col-4">
                                    <label for="floor-name-<?php echo e($i); ?>">Name</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['propertyFloors.' . $i . '.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="floor-name-<?php echo e($i); ?>"
                                           wire:model="propertyFloors.<?php echo e($i); ?>.name"
                                           placeholder="e.g. Ground Floor">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['propertyFloors.' . $i . '.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="col-6">
                                    <label for="floor-description-<?php echo e($i); ?>">Description</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['propertyFloors.' . $i . '.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="floor-description-<?php echo e($i); ?>"
                                           wire:model="propertyFloors.<?php echo e($i); ?>.description"
                                           placeholder="e.g. Main entrance and lobby.">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['propertyFloors.' . $i . '.description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <span class="cursor-pointer" wire:click.prevent="removeFloor(<?php echo e($i); ?>)">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <!-- Floors End -->
                </div>
                <div class="mb-3 col-md-12">
                    <label for="selectedAmenity" class="form-label h3">
                        <?php echo e(__('What can guests use at your hotel?')); ?>

                    </label>
                    <div class="row">
                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = current_company()->amenities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="gap-2 mb-2 cursor-pointer col-6 d-flex">
                            <input type="checkbox" class="form-check-input k-checkbox <?php $__errorArgs = ['selectedAmenity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="amenity_<?php echo e($amenity->id); ?>" wire:model="selectedAmenity" value="<?php echo e($amenity->id); ?>">
                            <label for="amenity_<?php echo e($amenity->id); ?>" class=""><?php echo e($amenity->name); ?></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted"><?php echo e(__('No amenities available.')); ?></p>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['selectedAmenity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mt-1 text-danger">
                            <?php echo e($message); ?>

                        </div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
                                <input type="text" wire:model="street" id="" class="p-0 k-input w-100" placeholder="<?php echo e(__('Street ....')); ?>">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="city" id="city_0" class="p-0 k-input w-100" placeholder="<?php echo e(__('City')); ?>">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <select wire:model="state" class="p-0 k-input w-100" id="state_id_0">
                                    <option value=""><?php echo e(__('State')); ?></option>
                                </select>
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="zip" id="zip_0" class="p-0 k-input w-100" placeholder="<?php echo e(__('ZIP')); ?>">
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                                <select wire:model="country" class="k-input w-100" id="country_id_0">
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
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/onboarding/add-property.blade.php ENDPATH**/ ?>