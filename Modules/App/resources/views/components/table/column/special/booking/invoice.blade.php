@props([
    'value',
])
@php
    $invoice = \Modules\ChannelManager\Models\Booking\BookingInvoice::find($value);
@endphp
@if($invoice)
<div>
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ Route::subdomainRoute('bookings.invoices.show', ['invoice' => $invoice->id]) }}"  tabindex="-1">
        {{ $invoice->reference }}
    </a>
</div>
@endif
