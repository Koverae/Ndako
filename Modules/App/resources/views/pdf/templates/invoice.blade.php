@extends('app::layouts.pdf')

@section('content')

<!-- Guest Details -->
<div class="guest-details">
    <p><strong>Guest:</strong> {{ $guest_name }}</p>
    <p><strong>Invoice Number:</strong> {{ $invoice_reference }}</p>
    <p><strong>Issue Date:</strong> {{ $date }}</p>
    {{-- <p><strong>Due Date:</strong> {{ $due_date ?? 'Upon Receipt' }}</p> --}}
</div>

<!-- Invoice Table -->
<table class="table invoice-table">
    <thead>
        <tr>
            <td>Booking {{ $reference ?? 'N/A' }}</td>
            <td>{{ $total_amount  }}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Booking {{ $reference ?? 'N/A' }}</td>
            <td>{{ $total_amount  }}</td>
        </tr>
        {{-- @foreach ($items ?? [['description' => 'Room Charge', 'quantity' => 1, 'unit_price' => 100.00, 'total' => 100.00]] as $item)
            <tr>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ format_currency($item['unit_price']) }}</td>
                <td>{{ format_currency($item['total']) }}</td>
            </tr>
        @endforeach
        <tr class="total">
            <td colspan="3">Total</td>
            <td>{{ format_currency($total_amount) }}</td>
        </tr> --}}
    </tbody>
</table>

<!-- Terms -->
<div class="content">
    <h2>Terms & Conditions</h2>
    <p>Payment is due upon receipt unless otherwise stated. Late payments may incur a 1.5% monthly fee. Thank you for your stay at {{ $company_name }}!</p>
</div>

{{-- <div class="details">
    <p><strong>Guest:</strong> {{ $guest_name }}</p>
    <p><strong>Invoice Reference:</strong> {{ $invoice_reference ?? 'ND/INV-' . time() }}</p>
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
                <td>{{ $total_amount  }}</td>
            </tr>
        </tbody>
    </table>
</div> --}}
@endsection
