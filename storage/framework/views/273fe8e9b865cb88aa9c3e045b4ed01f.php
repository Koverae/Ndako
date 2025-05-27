<?php $__env->startSection("title", "Booking Confirmation"); ?>

<?php $__env->startSection('content'); ?>
        <!-- Guest Details -->
        <div class="guest-details">
            <p><strong>Booking Number:</strong> <?php echo e($reference); ?></p>
            <p><strong>Guest Name:</strong> <?php echo e($guest_name); ?></p>
            <p><strong>Guest Email:</strong> <?php echo e($guest_email); ?></p>
            <p><strong>Guest Phone:</strong> <?php echo e($guest_phone); ?></p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <h2>Your Reservation</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong><?php echo e(__('Arrival')); ?>:</strong> <?php echo e(\Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A"); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong><?php echo e(__('Nights')); ?>:</strong> <?php echo e($nights); ?> <?php echo e(Str::plural('nights', $nights)); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong><?php echo e(__('Departure')); ?>:</strong> <?php echo e(\Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A'); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-bed"></i>
                    <strong>Room:</strong> <?php echo e($room); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-people"></i>
                    <strong>Guests:</strong> <?php echo e($guest_count); ?>

                </div>
            </div>
            <div class="total-amount d-block">
                <span><?php echo e(__('Paid Amount')); ?>: <?php echo e($paid_amount); ?></span>
                <span class="font-bold" style="font-weight: 600;"><?php echo e(__('Total Amount')); ?>: <?php echo e($total_amount); ?></span>
            </div>
            <p class="confirmation-message">
                We’re delighted to confirm your booking at <?php echo e($company_name); ?>. Please review the details above and contact us with any questions.
            </p>
        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('app::layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/booking-confirmation.blade.php ENDPATH**/ ?>