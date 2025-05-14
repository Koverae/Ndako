

<?php $__env->startSection('content'); ?>
        <!-- Guest Details -->
        <div class="guest-details">
            <p><strong>Guest:</strong> <?php echo e($guest_name); ?></p>
            <p><strong>Booking Reference:</strong> <?php echo e($reference); ?></p>
            <p><strong>Check-In Date:</strong> <?php echo e(\Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A"); ?></p>
            <p><strong>Check-Out Date:</strong> <?php echo e(\Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A'); ?></p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <h2>Your Reservation</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <i class="bi bi-building"></i>
                    <strong>Property:</strong> <?php echo e($company_name); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-bed"></i>
                    <strong>Room Type:</strong> <?php echo e($room_type ?? 'Deluxe Suite'); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-people"></i>
                    <strong>Guests:</strong> <?php echo e($guest_count ?? 2); ?>

                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>Dates:</strong> <?php echo e(\Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A"); ?> to <?php echo e(\Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A'); ?>

                </div>
            </div>
            <div class="total-amount">
                Total Amount: <?php echo e(format_currency($total_amount)); ?>

            </div>
            <p class="confirmation-message">
                We’re delighted to confirm your booking at <?php echo e($company_name); ?>. Please review the details above and contact us with any questions.
            </p>
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app::layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/booking-confirmation.blade.php ENDPATH**/ ?>