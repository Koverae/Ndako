@props([
    'value',
])
@php
    $floor = \Modules\Properties\Models\Property\PropertyFloor::find($value);
@endphp
<div>
    @if($floor)
    <a style="text-decoration: none" class="primary" tabindex="-1">
        {{ $floor->name ?? '' }}
    </a>
    @endif
</div>
