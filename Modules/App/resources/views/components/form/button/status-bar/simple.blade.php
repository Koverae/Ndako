@props([
    'value',
    'status'
])

<div>
    <span class="btn-secondary-outline cursor-pointer k-arrow-button {{ $status == $value->primary ? 'current' : '' }}">
        {{$value->label }}
    </span>
</div>
