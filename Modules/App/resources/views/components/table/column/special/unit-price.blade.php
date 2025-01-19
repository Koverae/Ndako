@props([
    'value',
])
@php
    $type = \Modules\Properties\Models\Property\PropertyUnitType::find($value);
@endphp
<div>
    @if($type->price)
    <a style="text-decoration: none" class="primary" tabindex="-1">
        {{ format_currency($type->price) ?? '' }} / {{ __('Night') }}
        {{-- {{ format_currency($price->price) ?? '' }} / {{ $price->lease->name ?? '' }} --}}
    </a>
    @endif
</div>
