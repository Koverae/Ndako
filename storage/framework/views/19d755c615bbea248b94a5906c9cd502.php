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

        <div class="row align-items-start">
            <!--[if BLOCK]><![endif]--><?php if($this->tenant): ?>
            <div class="p-2 k_kanban_view">

                <div class="flex-wrap k_kanban_renderer align-items-start d-flex justify-content-start">

                    <!-- Property Overview -->
                    <div class="mb-1 k_kanban_card">
                        <div class="gap-3 k_kanban_card_content d-flex">
                            <img class="rounded cursor-pointer k_kanban_image k_image_62_cover"
                                style="height: 100px; width: 100px;"
                                src="<?php echo e(asset('assets/images/default/property.jpeg')); ?>">
                            <div class="k_kanban_details">
                                <div class="cursor-pointer k_kanban_record_title">
                                    <div class="gap-3 d-flex">
                                        <h2 class="h2">Urban Nest <i class="bi bi-pencil-square"></i></h2><span class="p-1">UN9RUP161702161707052024</span>
                                    </div>
                                    <span>103 ~ One-Bedroom Apartment 🏠</span>
                                    <span class="mb-1 text-muted d-block">Monthly Rent:  <strong>KSh 12,500</strong> </span>
                                </div>
                                <div class="text-muted">
                                    <span class="mb-1 text-muted">Move-in Date: <strong>Jan 5, 2025</strong></span><br>
                                    <span class="mb-1 text-muted">Lease Duration: <strong>12 Months</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Lease Details -->
                    <div class="mb-1 k_kanban_card">
                        <div class="p-1 k_kanban_card_content">
                            <h6 class="mb-2 h3">Current Lease Information</h6>
                            <ul class="mb-0 list-unstyled">
                                <li><strong>Next Rent Due:</strong> April 5, 2025</li>
                                <li><strong>Rent Amount:</strong> <?php echo e(format_currency(12500)); ?> / Month</li>
                                <li><strong>Unpaid Rent:</strong> <?php echo e(format_currency(0)); ?> (No overdue balance) ✅</li>
                                <li><strong>Security Deposit:</strong> <?php echo e(format_currency(25000)); ?> (Refundable)</li>
                                <li><strong>Status:</strong> Active ✅</li>
                                
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            <?php else: ?>
            <div class="p-2 k_kanban_view">

                <!-- Property -->
                <div class="d-flex" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                            <?php echo e(__('Property')); ?>

                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1 ">

                        <select wire:model.live="property" id="property" class="k-input">
                            <option value=""><?php echo e(__('---- Select -----')); ?></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->propertiesOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($text); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['property'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Unit -->
                <div class="d-flex" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                            <?php echo e(__('Unit')); ?>

                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1 ">

                        <select wire:model.live="unit" id="unit" class="k-input">
                            <option value=""><?php echo e(__('---- Select -----')); ?></option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->unitsOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($text); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['unit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Monthly Rent -->
                <div class="mb-2 d-flex gap-2">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label"><?php echo e(__('Monthly Rent')); ?></label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="number" class="k-input" wire:model="monthlyRent">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['monthlyRent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <br>
                    </div>
                    <label for="is-linked" class="d-block align-items-center cursor-pointer">
                        <input type="checkbox" wire:model="isLinked" id="is-linked" class="form-check-input koverae-checkbox">
                        Link rent to unit price
                    </label>
                </div>

                <!-- Deposit Amount -->
                <div class="mb-2 d-flex gap-2">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label"><?php echo e(__('Deposit Amount')); ?></label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="number" class="k-input" wire:model.live="depositAmount">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['depositAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        <br>
                    </div>
                </div>

                <!-- Lease Term -->
                

                <!-- Start Date -->
                <div class="mb-2 d-flex">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label"><?php echo e(__('Start Date')); ?></label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="date" class="k-input" wire:model.live="startDate">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- End Date -->
                <div class="mb-2 d-flex">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label"><?php echo e(__('End Date')); ?></label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="date" class="k-input" wire:model.live="endDate">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <span class="<?php echo e($this->unit ? '' : 'd-none'); ?>">
                    <?php echo e(__("Your lease will last for {$this->duration} " . Str::plural('month', $this->duration))); ?>

                    (<?php echo e(\Carbon\Carbon::parse($this->startDate)->format("M j, Y") ."~". \Carbon\Carbon::parse($this->endDate)->format("M j, Y")); ?>)
                </span>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->



            
        </div>
    </div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/form/tab/group/special/lease.blade.php ENDPATH**/ ?>