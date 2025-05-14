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
            <th>Description</th>
            <th>Stay</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Booking {{ $reference ?? 'N/A' }}</td>
            <td></td>
            {{-- <td>{{ $total_amount  }}</td> --}}
        </tr>
        <tr class="total">
            <td colspan="3">Total</td>
            <td>{{ $total_amount }}</td>
        </tr>
    </tbody>
</table>

<!-- Terms -->
<div class="content">
    <h2>Terms & Conditions</h2>
    <p>Payment is due upon receipt unless otherwise stated. Late payments may incur a 1.5% monthly fee. Thank you for your stay at {{ $company_name }}!</p>
</div>

@endsection
