@props([
    'value',
])
@php
    $type = \Modules\Properties\Models\Property\PropertyType::find($value);
@endphp
<div>
    {{ Str::ucfirst($type->property_type_group) ?? '' }}
</div>
