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
<div>
    <div class="row gap-1 justify-content-md-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">
        <!-- Booking Details -->
        <div class="border shadow-sm col-12 col-md-8 card">
            <div class="p-4 card-body">
                <!-- Room Details -->
                <!--[if BLOCK]><![endif]--><?php if($this->selectedRoom): ?>
                <div class="mb-3">
                    <h2 class="h2"><i class="fas fa-bed"></i> Room Details</h2>
                    <ul class="list-unstyled">
                        <li><strong>Room:</strong> <?php echo e($this->selectedRoom->name); ?></li>
                        <li><strong>Type:</strong> <?php echo e($this->selectedRoom->unitType->name); ?></li>
                        <li><strong>Capacity:</strong> <?php echo e($this->selectedRoom->unitType->capacity); ?> guest(s)</li>
                        <li><strong>Price/Day:</strong> <?php echo e(format_currency($this->selectedRoom->unitType->price->price)); ?></li>
                    </ul>
                </div>
                <hr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <!-- Booking Period -->
                <div class="mt-2 mb-4">
                    <h2 class="h2"><i class="fas fa-calendar-alt"></i> Booking Period</h2>
                    <ul class="list-unstyled">
                        <li><strong>Check-In:</strong> <?php echo e(\Carbon\Carbon::parse($this->startDate)->format('d M Y')); ?></li>
                        <li><strong>Check-Out:</strong> <?php echo e(\Carbon\Carbon::parse($this->endDate)->format('d M Y')); ?></li>
                        <li><strong>Total Days:</strong> <?php echo e(dateDaysDifference($this->startDate, $this->endDate)); ?> Days</li>
                    </ul>
                </div>
                <hr>
                <?php
                    $nights = dateDaysDifference($this->startDate, $this->endDate);
                ?>
                <!-- Pricing Summary -->
                <!--[if BLOCK]><![endif]--><?php if($this->selectedRoom): ?>
                <div class="mt-2 mb-4">
                    <h2 class="h2"><i class="fas fa-money-check-alt"></i> Pricing Summary</h2>
                    <ul class="list-unstyled">
                        <li><strong>Total Price:</strong> <?php echo e(format_currency($this->totalAmount)); ?></li>
                        <li><strong>Minimum Down Payment:</strong> <?php echo e(format_currency($this->downPayment)); ?></li>
                    </ul>
                </div>
                <hr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <!-- Payment Section -->
                <!--[if BLOCK]><![endif]--><?php if($this->selectedRoom): ?>
                <div class="mt-2">
                    <h2 class="h2"><i class="fas fa-credit-card"></i> Make a Payment</h2>
                    <div class="mb-2">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-control <?php $__errorArgs = ['paymentMethod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paymentMethod" wire:model="paymentMethod" placeholder="Enter payment amount" value="<?php echo e(old('paymentMethod')); ?>">
                            <option value=""></option>
                            <option value="cash"><?php echo e(__('Cash')); ?></option>
                            <option value="bank"><?php echo e(__('Bank')); ?></option>
                            <option value="m-pesa"><?php echo e(__('M-Pesa')); ?></option>
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentMethod'];
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
                    <div class="mb-3">
                        <label for="downPayment" class="form-label">Down Payment</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['downPayment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="downPayment" wire:model="downPayment" placeholder="Enter payment amount" value="<?php echo e(old('downPayment')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['downPayment'];
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
                    <button type="submit" wire:click='createBooking' class="btn btn-primary">Pay Down Payment</button>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
        <div class="shadow-sm col-12 col-md-3 card" style="max-height: 450px;">
            <div class="card-body">
                <img src="<?php echo e($this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="<?php echo e($this->guest->name); ?>" class="img img-fluid" height="350px" width="350px">
                <div class="mt-2">
                    <span><i class="fas fa-user-md"></i> <?php echo e($this->guest->name); ?></span> <br>
                    <span><i class="bi bi-envelope"></i> <?php echo e($this->guest->email); ?></span> <br>
                    <span><i class="bi bi-phone"></i> <?php echo e($this->guest->phone); ?></span> <br>
                    <span><i class="bi bi-geo"></i> <?php echo e(__('Qwetu Parklands')); ?></span> <br>
                </div>
            </div>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/wizard/step-page/special/confirmation.blade.php ENDPATH**/ ?>