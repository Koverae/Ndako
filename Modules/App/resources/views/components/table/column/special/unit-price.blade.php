@props([
    'value',
])
@php
    $type = \Modules\Properties\Models\Property\PropertyUnitType::find($value);
    $defaultPrice = \Modules\Properties\Models\Property\PropertyUnitTypePricing::isPropertyUnit($type->id)
    ->isDefault(true)
    ->first();
@endphp
<div>
    @if($defaultPrice)
    <a style="text-decoration: none" class="primary" tabindex="-1">
        {{ format_currency($defaultPrice->price) ?? '' }} / {{ $defaultPrice->lease->description }}
    </a>
    @endif
</div>
