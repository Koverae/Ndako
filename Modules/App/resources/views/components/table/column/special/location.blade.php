@props([
    'value',
])
@php
    $property = \Modules\Properties\Models\Property\Property::find($value);
@endphp
<div>
    @if($property)

    {{ $property->address ?? '' }}, {{ $property->city }}
    @endif
</div>
