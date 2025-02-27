@props([
    'value',
])
@php
    $type = \Modules\Properties\Models\Property\PropertyType::find($value);
@endphp
<div>
    @if($type)
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ route('properties.show', ['property' => $type->id]) }}"  tabindex="-1">
        {{ $type->name ?? '' }}
    </a>
    @endif
</div>
