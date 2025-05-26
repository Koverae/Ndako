<?php $__env->startSection('content'); ?>
    <!-- Guest Details -->
    <div class="guest-details">
        <p><strong>Guest:</strong> <?php echo e($guest_name); ?></p>
        <p><strong>Receipt Number:</strong> <?php echo e($reference); ?></p>
        <p><strong>Payment Date:</strong> <?php echo e($date); ?></p>
        <p><strong>Payment Method:</strong> <?php echo e(inverseSlug($payment_method) ?? 'N/A'); ?></p>
    </div>

    <!-- Payment Details -->
    <table class="table invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Payment Method</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Payment for Booking #<?php echo e($booking_reference); ?></td>
                <td><?php echo e(inverseSlug($payment_method) ?? 'N/A'); ?></td>
                <td><?php echo e($total_amount); ?></td>
            </tr>
            <tr class="total">
                <td>Total Paid</td>
                <td><?php echo e($total_amount); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Thank You -->
    <div class="content">
        <h2>Thank You!</h2>
        <p>We’ve received your payment of <?php echo e($total_amount); ?> for booking #<?php echo e($reference); ?>. We look forward to hosting you at <?php echo e($company_name); ?>!</p>
    </div>
    
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app::layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/payment.blade.php ENDPATH**/ ?>