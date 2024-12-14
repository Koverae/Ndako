@props([
    'value',
])
@php
    $country = \Modules\Contact\Entities\Localization\Country::find($value);
@endphp
<div>
    {{ $country->common_name ?? '' }} 
</div>
