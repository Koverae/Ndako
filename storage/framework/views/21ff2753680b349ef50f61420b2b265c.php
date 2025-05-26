<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel"><?php echo e(__('Manage Booking')); ?>: #<?php echo e($booking->reference); ?></h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">

        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span><?php echo e(session('message')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php if(session()->has('success')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span><?php echo e(session('success')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentMethod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span><?php echo e(session('message')); ?></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

        <div class="k_form_nosheet">
            <div class="k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0">
                <!-- Action Bar -->
                <div id="action-bar" class="flex-wrap gap-1 k-statusbar-buttons d-lg-flex align-items-center align-content-around">

                    <button class="d-none d-lg-inline-flex rounded-0 <?php echo e($booking->status == 'confirmed' ? 'btn btn-primary active' : ''); ?>" type="button" wire:click="" wire:target=""  id="top-button">
                        <span>
                            <?php echo e(__('Send Invoice')); ?> <span wire:loading wire:target="" ></span>
                        </span>
                    </button>
                    <?php
                        $hideClass = in_array($booking->status, ['canceled', 'completed']) ? 'd-none' : '';
                    ?>
                    <button class=" rounded-0 <?php echo e($hideClass); ?>" type="button" wire:click="cancelBooking" wire:confirm="<?php echo e(__("Are you sure you want to cancel this booking #$booking->reference?")); ?>" wire:target="cancelBooking"  id="top-button">
                        <span>
                            <?php echo e(__('Cancel')); ?> <span wire:loading wire:target="cancelBooking"> ...</span>
                        </span>
                    </button>
                </div>

                <!-- Status Bar -->
                <div id="status-bar" class="gap-1 k-statusbar-buttons-arrow d-md-flex align-items-center align-content-around ">

                    <span class="btn-secondary-outline cursor-pointer k-arrow-button <?php echo e($booking->status == 'pending' ? 'current' : ''); ?>">
                        <?php echo e(__('Pending')); ?>

                    </span>
                    <span class="btn-secondary-outline cursor-pointer k-arrow-button <?php echo e($booking->status == 'confirmed' ? 'current' : ''); ?>">
                        <?php echo e(__('Confirmed')); ?>

                    </span>
                    <span class="btn-secondary-outline cursor-pointer k-arrow-button <?php echo e($booking->status == 'completed' ? 'current' : ''); ?>">
                        <?php echo e(__('Completed')); ?>

                    </span>
                    <span class="btn-secondary-outline <?php echo e($booking->status == 'canceled' ? '' : 'd-none'); ?> cursor-pointer k-arrow-button <?php echo e($booking->status == 'canceled' ? 'current' : ''); ?>">
                        <?php echo e(__('Canceled')); ?>

                    </span>
                </div>
            </div>

            <div class="k_inner_group row">
                <div class="m-0 mt-3 mb-3 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <h2 class="h2"><i class="fas fa-user"></i> <?php echo e(__('Guest Details')); ?></h2>
                        <ul class="list-unstyled row">
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Guest Name')); ?>:</strong> <?php echo e($booking->guest->name); ?></p>
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Guest(s)')); ?>:</strong> <?php echo e($booking->guests); ?> <!--[if BLOCK]><![endif]--><?php if($booking->guests > 1): ?><?php echo e(__('people')); ?><?php else: ?> <?php echo e(__('person')); ?> <?php endif; ?><!--[if ENDBLOCK]><![endif]--></p>
                            <!--[if BLOCK]><![endif]--><?php if($booking->due_amount >= 1 && $booking->status != 'canceled'): ?>
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Amount Paid')); ?>:</strong> <?php echo e(format_currency($booking->paid_amount)); ?></p>
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Due Amount')); ?>:</strong> <?php echo e(format_currency($booking->due_amount)); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($booking->status == 'canceled'): ?>
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Refund Amount')); ?>:</strong> <?php echo e(format_currency($booking->refund_amount)); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Total Amount')); ?>:</strong> <?php echo e(format_currency($booking->total_amount)); ?></p>
                            <p class="mb-2 col-12 col-lg-6"><strong><?php echo e(__('Stay')); ?>:</strong> <?php echo e(\Carbon\Carbon::parse($booking->check_in)->format('d M Y')); ?> ~ <?php echo e(\Carbon\Carbon::parse($booking->check_out)->format('d M Y')); ?></p>
                        </ul>
                    </div>
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        <!--[if BLOCK]><![endif]--><?php if($photo != null): ?>
                        <img src="<?php echo e($photo->temporaryUrl()); ?>" alt="image" class="img img-fluid">
                        <?php else: ?>
                        <img src="<?php echo e($image_path ? Storage::url('avatars/' . $image_path) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="image" class="img img-fluid">
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="error"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($booking->due_amount >= 1): ?>
                <hr>
                <div class="mt-2 <?php echo e($booking->status == 'canceled' ? 'd-none' : ''); ?>">
                    <h2 class="h2"><i class="fas fa-credit-card"></i> <?php echo e(__('Make a Payment')); ?></h2>
                    <div class="mb-2">
                        <label for="paymentMethod" class="form-label"><?php echo e(__('Payment Method')); ?></label>
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
                            <!--[if BLOCK]><![endif]--><?php if(settings()->has_paystack): ?>
                            <option value="paystack"><?php echo e(__('Paystack(Bank, Mobile Money)')); ?></option>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                        <label for="paymentAmount" class="form-label"><?php echo e(__('Payment Amount')); ?></label>
                        <input type="number" class="form-control <?php $__errorArgs = ['paymentAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="paymentAmount" wire:model="paymentAmount" placeholder="Enter payment amount" value="<?php echo e(old('paymentAmount')); ?>">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentAmount'];
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
                    <div class="d-flex justify-content-between">
                        <span>&nbsp;</span>
                        <button type="submit" wire:click='addPayment' wire:loading.class="disabled" class="btn btn-primary text-end">Pay</button>
                    </div>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <div class="mt-3 row <?php echo e($booking->status == 'canceled' ? 'd-none' : ''); ?>">
                    <button wire:click="checkIn" class="gap-2 btn btn-primary rounded-0 col-6" <?php echo e($booking->check_in_status == 'pending' ? '' : 'disabled'); ?>>
                        <i class="fas fa-sign-in-alt"></i> Check In
                    </button>
                    <button wire:click="checkOut" wire:confirm="Do you want to proceed check-out?" class="gap-2 btn btn-warning rounded-0 col-6" <?php echo e($booking->check_in_status == 'checked_in' && $booking->check_out_status == 'pending' ? '' : 'disabled'); ?>>
                        <i class="fas fa-sign-out"></i> Check Out
                    </button>
                </div>


            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Close')); ?></button>
      </div>
    </div>

        <?php
        $__scriptKey = '3957310647-0';
        ob_start();
    ?>
    <script>
        $wire.on('openPaystackPopup', url => {
            let width = 600, height = 700;
            let left = (screen.width - width) / 2;
            let top = (screen.height - height) / 2;

            let paystackWindow = window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);

            // let interval = setInterval(() => {
            //     if (paystackWindow && paystackWindow.closed) {
            //         clearInterval(interval);
            //         $wire.dispatch('paymentCompleted', {reference: localStorage.getItem('paystack_payment_reference')});
            //     }
            // }, 1000);
        });
    </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>

</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/livewire/modal/guest-booking-modal.blade.php ENDPATH**/ ?>