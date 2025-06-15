<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            /* margin: 40px; */
            /* background-color: #f4f4f4; */
        }
        .report-container {
            width: 100%;
            /* margin: 0 auto; */
            background-color: #fff;
            /* padding: 20px; */
            /* border: 1px solid #ddd; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #333;
        }
        .date {
            font-style: italic;
            text-align: center;
            margin: 10px 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .section table th, .section table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        .section table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section table .category {
            font-weight: bold;
            background-color: #e8e8e8;
        }
        .section table .total {
            font-weight: bold;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="header">
            <h1>Daily Sales Report</h1>
            <p>{{ $company_name ?? 'Koverae Technologies' }}</p>
            <p>Session ID: {{ $session->pos->name }}/{{ $session->reference }}</p>
            <p>{{ current_company()->country->common_name ?? 'Kenyat' }}</p>
        </div>
        <div class="date" style="border: 1px solid #E6F2F3; background-color: #eaf4ff; color: #017E84; border-radius: 6px; padding: 8px 0; margin: 20px auto 20px auto; max-width: 220px; font-weight: bold; letter-spacing: 1px; box-shadow: 0 2px 6px rgba(0,123,255,0.07);">
            As of {{ \Carbon\Carbon::now()->format('m/d/Y') }}
        </div>

        <div class="section">
            <h2>Sales</h2>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>

                @php
                    $grouped = $session->orders
                        ->flatMap->details
                        ->groupBy(fn($detail) => $detail->product->category->name ?? 'Uncategorized');
                @endphp

                @foreach($grouped as $category => $details)
                    @php
                        $groupedProducts = $details->groupBy(fn($detail) => $detail->product->id ?? 'unknown');
                    @endphp

                    <tr class="category">
                        <td>{{ $category }}</td>
                        <td>{{ $details->sum('quantity') }}</td>
                        <td>{{ format_currency($details->sum(fn($d) => $d->quantity * $d->unit_price)) }}</td>
                    </tr>

                    @foreach($groupedProducts as $productDetails)
                        @php
                            $product = $productDetails->first()->product;
                            $totalQuantity = $productDetails->sum('quantity');
                            $totalAmount = $productDetails->sum(fn($d) => $d->quantity * $d->unit_price);
                        @endphp
                        <tr>
                            <td>{{ $product->product_name ?? 'Unknown Product' }}</td>
                            <td>{{ $totalQuantity }}</td>
                            <td>{{ format_currency($totalAmount) }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <tr class="total">
                    <td>Total</td>
                    <td>{{ $session->orders->sum(fn($order) => $order->details->sum('quantity')) ?? 0 }}</td>
                    <td>{{ format_currency($session->orders->sum('total_amount') ?? 0) }}</td>
                </tr>
            </table>
        </div>


        <div class="section">
            <h2>Taxes on Sales</h2>
            <table>
                <tr>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>VAT 16%</td>
                    <td>{{ format_currency(($session->orders->sum('total_amount') * 16) / 100 ?? 0) }}</td>
                </tr>
                <tr class="total">
                    <td>Total</td>
                    <td>{{ format_currency($session->orders->sum('total_amount') ?? 0) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Discounts</h2>
            <p>Number of discounts: 0</p>
            <p>Amount of discounts: 0.00 KSh</p>
        </div>
        <div class="section">
            <h2>Session Control</h2>
            <p>Total: {{ format_currency($session->orders->sum('total_amount') ?? 0) }}</p>
            <p>Number of transactions: 1</p>
        </div>
        <div class="section">
            <h2>Expected</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Expected</th>
                </tr>
                <tr>
                    <td>Cash {{ $session->pos->name }}/{{ $session->reference }}</td>
                    <td>{{ format_currency($cashPayments ?? 0) }}</td>
                </tr>
                <tr>
                    <td>Card & Mobile Money {{ $session->pos->name }}/{{ $session->reference }}</td>
                    <td>{{ format_currency($cardPayments ?? 0) }}</td>
                </tr>
                <tr>
                    <td>Paystack {{ $session->pos->name }}/{{ $session->reference }}</td>
                    <td>{{ format_currency($paystackPayments ?? 0) }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
