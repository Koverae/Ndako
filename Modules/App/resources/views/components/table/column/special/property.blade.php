@props([
    'value',
])
@php
    $property = \Modules\Properties\Models\Property\Property::find($value);
@endphp
<div>
    @if($property)
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ route('properties.show', ['property' => $property->id]) }}"  tabindex="-1">
        {{ $property->name ?? '' }}
    </a>
    @endif
</div>
