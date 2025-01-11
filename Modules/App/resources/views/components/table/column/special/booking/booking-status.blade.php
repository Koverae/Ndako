@props([
    'value',
])

@if ($value == 'pending')
    <span class="text-white badge bg-warning">
       {{ __('Pending') }}
    </span>
@elseif($value == 'confirmed')
    <span class="text-white badge" style="background-color: #0E6163;">
        {{ __('Confirmed') }}
    </span>
@elseif($value == 'canceled')
    <span class="text-white badge bg-danger">
        {{ __('Canceled') }}
    </span>
@endif
