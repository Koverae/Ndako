

<?php $__env->startSection('content'); ?>

<!-- Guest Details -->
<div class="guest-details">
    <p><strong>Guest:</strong> <?php echo e($guest_name); ?></p>
    <p><strong>Invoice Number:</strong> <?php echo e($invoice_reference); ?></p>
    <p><strong>Issue Date:</strong> <?php echo e($date); ?></p>
    
</div>

<!-- Invoice Table -->
<table class="table invoice-table">
    <thead>
        <tr>
            <th>Description</th>
            <th>Stay</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Booking <?php echo e($reference ?? 'N/A'); ?></td>
            <td></td>
            
        </tr>
        <tr class="total">
            <td colspan="3">Total</td>
            <td><?php echo e($total_amount); ?></td>
        </tr>
    </tbody>
</table>

<!-- Terms -->
<div class="content">
    <h2>Terms & Conditions</h2>
    <p>Payment is due upon receipt unless otherwise stated. Late payments may incur a 1.5% monthly fee. Thank you for your stay at <?php echo e($company_name); ?>!</p>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('app::layouts.pdf', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/invoice.blade.php ENDPATH**/ ?>