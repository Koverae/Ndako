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
            <p>{{ $company_name }}</p>
        </div>
        <div class="details">
            <p><strong>Guest:</strong> {{ $guest_name }}</p>
            <p><strong>Invoice Number:</strong> {{ $invoice_number ?? 'INV-' . time() }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Booking {{ $reference ?? 'N/A' }}</td>
                        <td>{{ $total_amount ? number_format($total_amount, 2) : '0.00' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Contact us at {{ $company_phone ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>
