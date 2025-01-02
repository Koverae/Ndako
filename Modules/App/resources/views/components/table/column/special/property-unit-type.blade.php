@props([
    'value',
])
@php
    $type = \Modules\Properties\Models\Property\PropertyUnitType::find($value);
@endphp
<div>
    @if($type)
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ Route::subdomainRoute('properties.show', ['property' => $type->id]) }}"  tabindex="-1">
        {{ $type->name ?? '' }}
    </a>
    @endif
</div>
