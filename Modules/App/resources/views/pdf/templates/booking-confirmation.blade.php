@extends('app::layouts.pdf')

@section("title", "Booking Confirmation")

@section('content')
        <!-- Guest Details -->
        <div class="guest-details">
            <p><strong>Booking Number:</strong> {{ $reference }}</p>
            <p><strong>Guest Name:</strong> {{ $guest_name }}</p>
            <p><strong>Guest Email:</strong> {{ $guest_email }}</p>
            <p><strong>Guest Phone:</strong> {{ $guest_phone }}</p>
        </div>

        <!-- Booking Details -->
        <div class="booking-details">
            <h2>Your Reservation</h2>
            <div class="detail-grid">
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>{{ __('Arrival') }}:</strong> {{ \Carbon\Carbon::parse($check_in)->format('d M Y') ?? "N/A" }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>{{__('Nights')}}:</strong> {{ $nights }} {{ Str::plural('nights', $nights) }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-calendar-check"></i>
                    <strong>{{ __('Departure') }}:</strong> {{ \Carbon\Carbon::parse($check_out)->format('d M Y') ?? 'N/A' }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-bed"></i>
                    <strong>Room:</strong> {{ $room }}
                </div>
                <div class="detail-item">
                    <i class="bi bi-people"></i>
                    <strong>Guests:</strong> {{ $guest_count }}
                </div>
            </div>
            <div class="total-amount d-block">
                <div class="mb-2">{{ __('Paid Amount') }}: {{ $paid_amount }}</div>
                <div class="font-bold" style="font-weight: 600;">{{ __('Total Amount') }}: {{ $total_amount }}</div>
            </div>
            <p class="confirmation-message">
                We’re delighted to confirm your booking at {{ $company_name }}. Please review the details above and contact us with any questions.
            </p>
        </div>

@endsection
