@props([
    'value',
])
@php
    $pos = \Modules\Pos\Models\Pos\Pos::find($value);
@endphp
<div>
    <a href="{{ route('pos.show', $pos->id) }}">
        {{ $pos->name }}
    </a>
</div>
