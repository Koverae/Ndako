

<?php $__env->startSection('styles'); ?>
<style>
.booking-confirmation-wrapper {
    padding: 2rem 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.booking-header {
    text-align: center;
    margin-bottom: 2rem;
}

.booking-header h2 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #2f2f2f;
}

.booking-header p {
    color: #666;
    font-size: 1rem;
}

.booking-content-grid {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

@media (min-width: 992px) {
    .booking-content-grid {
        flex-direction: row;
    }
}

.booking-column {
    flex: 1;
    padding: 1.5rem;
    background: #fff;
    border-radius: 12px;
    border: 1px solid #eee;
}

.booking-column h4 {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #333;
}

.booking-column p {
    font-size: 0.95rem;
    margin-bottom: 0.6rem;
    color: #555;
}

.payment-column .form-group {
    margin-bottom: 1.25rem;
}

.payment-column label {
    display: block;
    font-weight: 500;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #333;
}

.payment-column input {
    width: 100%;
    padding: 0.6rem 0.8rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
}

.confirm-payment-btn {
    margin-top: 1rem;
    background-color: #0b4f34;
    color: #fff;
    border: none;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    width: 100%;
}

.confirm-payment-btn:hover {
    background-color: #083d28;
}

.mt-4 {
    margin-top: 1.5rem;
}

#confirmBookingBtn:hover {
    background-color: #005e48;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="booking-confirmation-wrapper">
    <div class="booking-header">
        <h2>You're almost done!</h2>
        <p>Please review your booking details and complete payment.</p>
    </div>

    <div class="booking-content-grid">
        <!-- LEFT: Booking Summary -->
        <div class="booking-column">
            <h4>Room Summary</h4>
            <p><strong>Room:</strong> <?php echo e($room->name); ?> <span class="text-muted">(<?php echo e($room->unitType->name); ?>)</span></p>
            <p><strong>Guests:</strong> <?php echo e($room->capacity); ?> <?php echo e(Str::plural('guest', $room->capacity)); ?></p>
            <p><strong>Details:</strong> <?php echo e($room->details); ?></p>

            <h4 class="mt-4">Stay Duration</h4>
            <p><strong>Check-in:</strong> <?php echo e($checkIn->format("F d, Y")); ?></p>
            <p><strong>Check-out:</strong> <?php echo e($checkOut->format("F d, Y")); ?></p>

            <h4 class="mt-4">Pricing</h4>
            <p><strong>Nightly Rate:</strong> KSh <?php echo e(number_format($room->unitType->getDefaultRate($room->unitType->id)->price, 2)); ?></p>
            <p><strong>Total (est.):</strong> KSh <?php echo e(number_format($totalPrice ?? $room->price, 2)); ?></p>
        </div>

        <!-- RIGHT: Payment Section -->
        <div class="booking-column payment-column">
            <h4><?php echo e(__('Payment Information')); ?></h4>
            
            <div class="form-group">
                <label><?php echo e(__('Full Name')); ?></label>
                <input type="text" name="name" required placeholder="Brian Mwangi">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="brianmwangi@gmail.com">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required placeholder="">
            </div>

            <input type="hidden" name="room_id" value="<?php echo e($room->id); ?>">
            <input type="hidden" name="check_in" value="<?php echo e($checkIn); ?>">
            <input type="hidden" name="check_out" value="<?php echo e($checkOut); ?>">
            <input type="hidden" name="total_price" value="<?php echo e($totalPrice ?? $room->price); ?>">

            <button class="confirm-payment-btn" id="confirmBookingBtn">
                Pay with Paystack
            </button>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('channelmanager::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/embed/checkout-section.blade.php ENDPATH**/ ?>