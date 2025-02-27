@props([
    'value',
])
@php
    $unit = \Modules\Properties\Models\Property\PropertyUnit::find($value);
@endphp
<div>
    @if($unit)
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ route('properties.units.show', ['unit' => $unit->id]) }}"  tabindex="-1">
        {{ $unit->name ?? '' }}
    </a>
    @endif
</div>
