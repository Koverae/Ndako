@props([
    'value',
])
@php
    $price = \Modules\Properties\Models\Property\PropertyUnitTypePricing::find($value);
    // $price = $type->price;
    // $lease = $type->price->lease;
@endphp
<div>
    @if($price)
    <a style="text-decoration: none" class="primary" tabindex="-1">
        {{ format_currency($price->price) ?? '' }} / {{ $price->lease->name ?? '' }}
    </a>
    @endif
</div>
