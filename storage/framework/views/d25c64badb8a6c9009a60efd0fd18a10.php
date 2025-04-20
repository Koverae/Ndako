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
            <h2 class="h2">Define Your Units 🏢</h2>
            <p>Now, let's add units to your property. Whether it's rooms, apartments, or offices, this step ensures accurate tracking and management.</p>

        </div>
        <div class="card-body">
            <form wire:submit.prevent="submitProperty">
                <?php echo csrf_field(); ?>
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <label class="h3" for="name-k"><?php echo e(__('What’s the name of this room/unit?')); ?></label>
                        <h1 class="flex-row d-flex align-items-center">
                            <select wire:model="unitName" id="" class="form-control" id="name-k">
                                <option value="">-- Choose --</option>
                                <?php $__currentLoopData = $this->unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($text); ?>"><?php echo e($text); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            
                            <?php $__errorArgs = ['unitName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </h1>
                    </div>
                </div>
                <div class="mb-3 row align-items-start">
                    <div class="d-flex">
                        <textarea wire:model="unitDesc" class="p-0 m-0 textearea k-input" placeholder="<?php echo e(__('Tell me a bit about your type of unit. What makes it awesome?')); ?>" id="unitDesc">
                        </textarea>
                        <?php $__errorArgs = ['unitDesc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="numberUnits" class="form-label h3">
                        <?php echo e(__('How many rooms of this type do you have?')); ?>

                    </label>
                    <input type="number" class="form-control <?php $__errorArgs = ['numberUnits'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="numberUnits" wire:model.live="numberUnits" style="width: 140px; height: 36px;" value="<?php echo e(old('numberUnits')); ?>">
                    <?php $__errorArgs = ['numberUnits'];
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
unset($__errorArgs, $__bag); ?>
                    <!-- Units -->
                    <div class="row <?php echo e($this->numberUnits >= 1 ? '' : 'd-none'); ?>">
                        <?php for($i = 0; $i < $this->numberUnits; $i++): ?>
                            <div class="gap-2 mt-2 mb-2 col-md-6 d-flex align-items-center">
                                <div class="col-4">
                                    <label for="unit-name-<?php echo e($i); ?>"><?php echo e(__('Room Number')); ?></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['units.' . $i . '.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="unit-name-<?php echo e($i); ?>"
                                           wire:model="units.<?php echo e($i); ?>.name"
                                           placeholder="<?php echo e(__('Room Number')); ?>">
                                    <?php $__errorArgs = ['units.' . $i . '.name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-4">
                                    <label for="unit-floor-<?php echo e($i); ?>"><?php echo e(__('Floor')); ?></label>
                                    <select class="form-control <?php $__errorArgs = ['units.' . $i . '.floor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unit-floor-<?php echo e($i); ?>" wire:model="units.<?php echo e($i); ?>.floor">
                                        <option value=""><?php echo e(__('--- Choose ---')); ?></option>
                                        <?php $__currentLoopData = $this->propertyFloors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($floor['name']); ?>"><?php echo e($floor['name']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['units.' . $i . '.floor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">&nbsp;</span>

                                    <span class="cursor-pointer text-end" wire:click.prevent="removeTypeUnit(<?php echo e($i); ?>)">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    <!-- Units End -->
                </div>

                <div class="row">
                    <!-- Capacity -->
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="capacity" class="form-label h3">
                            <?php echo e(__('How many guests can stay in this room/unit?')); ?>

                        </label>
                        <div class="number-input-wrapper <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <span class="btn btn-link minus" wire:click="decreaseCapacity">−</span>
                            <input type="number" id="number-input" min="1" wire:model="capacity" class="number-input" />
                            <span class="btn btn-link plus" wire:click="increaseCapacity">+</span>
                        </div>
                        <?php $__errorArgs = ['capacity'];
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
unset($__errorArgs, $__bag); ?>
                    </div>
                    <!-- Capacity End -->
                    <!-- Size -->
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="unitSize" class="form-label h3">
                            <?php echo e(__('How big is this room/unit? (optional)')); ?>

                        </label>
                        <div class="gap-2 d-flex">
                            <input type="number" class="form-control <?php $__errorArgs = ['unitSize'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unitSize" wire:model="unitSize" style="width: 140px; height: 36px;" value="<?php echo e(old('unitSize')); ?>">
                            <span class="p-2">Square metres</span>
                        </div>
                        <?php $__errorArgs = ['unitSize'];
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
unset($__errorArgs, $__bag); ?>
                    </div>
                    <!-- Size End -->
                </div>

                <div class="col-12">
                    <label class="form-label h3">
                        <?php echo e(__('How much do you want to charge?')); ?>

                    </label>
                    <span class="cursor-pointer fw-bolder border rounded p-2" wire:click.prevent="addPricing" wire:target="addPricing">
                        <i class="bi bi-plus-circle"></i> <?php echo e(__('Add Pricing')); ?>

                    </span>
                    <div class="row mt-2 <?php echo e($this->prices >= 1 ? '' : 'd-none'); ?>">
                        <?php for($i = 0; $i < $this->prices; $i++): ?>
                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="rateType-<?php echo e($i); ?>" class="form-label">
                                <?php echo e(__('Rate Type')); ?>

                            </label>
                            <select wire:model="unitPrices.<?php echo e($i); ?>.rate_type" id="rateType-<?php echo e($i); ?>" class="form-control" style="width: 200px;">
                                <option value="">--- Chose ---</option>
                                <?php $__currentLoopData = $this->leaseTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.rate_type'];
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
unset($__errorArgs, $__bag); ?>
                        </div>
                        <!-- Price End -->

                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="unitRate" class="form-label">
                                <?php echo e(__('Rate')); ?>

                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon font-weight-bolder">
                                    <?php echo e(settings()->currency->symbol); ?>

                                </span>
                                <input type="number" placeholder="18,900" class="form-control <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unitRate" wire:model="unitPrices.<?php echo e($i); ?>.rate" style="width: 200px;">
                            </div>
                            <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.rate'];
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
unset($__errorArgs, $__bag); ?>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted"><?php echo e(__('Including taxes and charges')); ?></span>
                            </div>
                        </div>
                        <!-- Price End -->

                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="unitDefault" class="form-label mb-2">
                                <?php echo e(__('Is Default')); ?>

                            </label>
                            <input type="checkbox" class="form-control form-check-input <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unitDefault" wire:model="unitPrices.<?php echo e($i); ?>.default">
                            <?php $__errorArgs = ['unitPrices.<?php echo e($i); ?>.default'];
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
unset($__errorArgs, $__bag); ?>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">&nbsp;</span>

                                <span class="cursor-pointer text-end" wire:click.prevent="removePricing(<?php echo e($i); ?>)">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Price End -->
                        <?php endfor; ?>

                    </div>
                </div>

                <!-- Features -->
                <div class="mb-3 col-md-12">
                    <label for="unitFeatures" class="form-label h3">
                        <?php echo e(__('What can guests use in this room/unit?')); ?>

                    </label>
                    <div class="row">
                        <?php $__currentLoopData = current_company()->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="gap-2 mb-2 cursor-pointer d-flex col-6 col-lg-4">
                            <input type="checkbox" class="form-check-input k-checkbox <?php $__errorArgs = ['unitFeatures'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="feature_<?php echo e($feature->id); ?>" wire:model="unitFeatures" value="<?php echo e($feature->id); ?>">
                            <label class="cursor-pointer" for="feature_<?php echo e($feature->id); ?>" class=""><?php echo e($feature->name); ?></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['unitFeatures'];
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
unset($__errorArgs, $__bag); ?>
                </div>
                <!-- Features End -->

                <div class="mb-3 d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <span class="gap-1 btn btn-primary go-next text-end" wire:click="addUnit"><?php echo e(__('Add Room')); ?> <i class="fas fa-plus-circle"></i></span>
                </div>

                <div class="row <?php echo e($this->propertyUnits ? '' : 'd-none'); ?>">
                    <h3 class="form-label h3">
                        <?php echo e(__('Do these sound like your rooms?')); ?>

                    </h3>
                    <!-- Unit Type -->
                    <?php $__currentLoopData = $this->propertyUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" style="min-height: 122px;">
                        <div class="p-2 border rounded" style="min-height: 122px;">
                            <div class="d-flex justify-content-between">
                                <h3 class="h3"><?php echo e($unit['unitName']); ?></h3>
                                <span class="text-muted d-block"><?php echo e($unit['capacity']); ?> <i class="bi bi-people"></i></span>
                                <span class="text-end" wire:click="removeUnit(<?php echo e($index); ?>)"><i class="fas fa-trash"></i></span>
                            </div>
                            <div class="mt-3 mb-3">
                                <p>
                                    <?php echo e($unit['unitDesc']); ?>

                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><?php echo e($unit['numberUnits']); ?> <?php echo e(__('Rooms')); ?></span>
                                <div class="d-block">
                                    <?php if(count($unit['unitPrices']) >= 1): ?>
                                        <?php $__currentLoopData = $unit['unitPrices']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="bottom-0 text-end"><?php echo e(format_currency($price['rate'])); ?> / <?php echo e(lease_term($price['rate_type'])->name); ?></span> <br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <!-- Unit Type End -->

                </div>

                <?php if(session()->has('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

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
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\wizard\step-page\special\onboarding\add-units.blade.php ENDPATH**/ ?>