@extends('app::layouts.pdf')

@section('content')
        <!-- Guest Details -->
        <div class="guest-details">
            <p><strong>Guest:</strong> {{ $guest_name }}</p>
            <p><strong>Booking Reference:</strong> {{ $reference }}</p>
            <p><strong>Check-In Date:</strong> {{ \Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A" }}</p>
            <p><strong>Check-Out Date:</strong> {{ \Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A' }}</p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <h2>Your Reservation</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <i class="bi bi-building"></i>
                    <strong>Property:</strong> {{ $company_name }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-bed"></i>
                    <strong>Room Type:</strong> {{ $room_type ?? 'Deluxe Suite' }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-people"></i>
                    <strong>Guests:</strong> {{ $guest_count ?? 2 }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>Dates:</strong> {{ \Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A" }} to {{ \Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A' }}
                </div>
            </div>
            <div class="total-amount">
                Total Amount: {{ format_currency($total_amount) }}
            </div>
            <p class="confirmation-message">
                We’re delighted to confirm your booking at {{ $company_name }}. Please review the details above and contact us with any questions.
            </p>
        </div>
@endsection