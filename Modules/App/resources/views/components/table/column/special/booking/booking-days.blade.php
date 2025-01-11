@props([
    'value'
])
@php
    $booking = \Modules\ChannelManager\Models\Booking\Booking::find($value);
    $date1 = \Carbon\Carbon::parse($booking->check_in);
    $date2 = \Carbon\Carbon::parse($booking->check_out);
    $daysDifference = $date1->diffInDays($date2);
@endphp
<div>
    {{ $daysDifference }} {{ __('Days') }}
</div>
