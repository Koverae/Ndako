@props([
    'value',
])
@php
    $country = \Modules\Contact\Entities\Localization\Country::find($value);
    $property = \Modules\Properties\Models\Property\Property::find($value);
@endphp
<div>
    {{ $country->common_name ?? '' }}, {{  }}
</div>
