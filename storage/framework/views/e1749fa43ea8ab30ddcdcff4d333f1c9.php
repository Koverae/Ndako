<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details th, .details td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .details th {
            background: #f4f4f4;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
            <p><?php echo e($company_name); ?></p>
        </div>
        <div class="details">
            <p><strong>Guest:</strong> <?php echo e($guest_name); ?></p>
            <p><strong>Invoice Reference:</strong> <?php echo e($invoice_reference ?? 'ND/INV-' . time()); ?></p>
            <p><strong>Date:</strong> <?php echo e($date); ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Booking <?php echo e($reference ?? 'N/A'); ?></td>
                        <td><?php echo e($total_amount); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Contact us at <?php echo e($company_phone ?? 'N/A'); ?></p>
        </div>
    </div>
</body>
</html>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/pdf/templates/invoice.blade.php ENDPATH**/ ?>