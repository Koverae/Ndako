<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
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
        .details p {
            margin: 5px 0;
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
            <h1>Payment Receipt</h1>
            <p>{{ $company_name }}</p>
        </div>
        <div class="details">
            <p><strong>Guest:</strong> {{ $guest_name }}</p>
            <p><strong>Payment Date:</strong> {{ $date }}</p>
            <p><strong>Booking Reference:</strong> {{ $reference ?? 'N/A' }}</p>
            <p><strong>Amount Paid:</strong> {{ $total_amount ? number_format($total_amount, 2) : '0.00' }}</p>
            <p><strong>Payment Method:</strong> {{ $payment_method ?? 'N/A' }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>Contact us at {{ $company_phone ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>
