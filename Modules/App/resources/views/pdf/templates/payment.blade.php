@extends('app::layouts.pdf')

@section('content')
    <!-- Guest Details -->
    <div class="guest-details">
        <p><strong>Guest:</strong> {{ $guest_name }}</p>
        <p><strong>Receipt Number:</strong> {{ $reference }}</p>
        <p><strong>Payment Date:</strong> {{ $date }}</p>
        <p><strong>Payment Method:</strong> {{ inverseSlug($payment_method) ?? 'N/A' }}</p>
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
                <td>Payment for Booking #{{ $booking_reference }}</td>
                <td>{{ inverseSlug($payment_method) ?? 'N/A' }}</td>
                <td>{{ $total_amount }}</td>
            </tr>
            <tr class="total">
                <td>Total Paid</td>
                <td>{{ $total_amount }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Thank You -->
    <div class="content">
        <h2>Thank You!</h2>
        <p>We’ve received your payment of {{ $total_amount }} for booking #{{ $reference }}. We look forward to hosting you at {{ $company_name }}!</p>
    </div>
    
    {{-- <div class="container">
        <div class="header">
            <h1>Payment Receipt</h1>
            <p>{{ $company_name }}</p>
        </div>
        <div class="details">
            <p><strong>Guest:</strong> {{ $guest_name }}</p>
            <p><strong>Payment Date:</strong> {{ $date }}</p>
            <p><strong>Booking Reference:</strong> {{ $reference ?? 'N/A' }}</p>
            <p><strong>Amount Paid:</strong> {{ $total_amount}}</p>
            <p><strong>Payment Method:</strong> {{ $payment_method ?? 'N/A' }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>Contact us at {{ $company_phone ?? 'N/A' }}</p>
        </div>
    </div> --}}
@endsection
