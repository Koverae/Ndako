@props([
    'value'
])
@php
    $booking = \Modules\ChannelManager\Models\Booking\Booking::find($value);
@endphp
<div>
    {{-- {{ \Carbon\Carbon::parse($value)->locale('fr')->isoFormat('LL LTS') }} --}}
    {{ \Carbon\Carbon::parse($booking->check_in)->format('d F Y') }} ~ {{ \Carbon\Carbon::parse($booking->check_out)->format('d F Y') }}
</div>
