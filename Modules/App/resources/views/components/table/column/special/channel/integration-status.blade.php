@props([
    'value',
])

@if ($value == 'disconnected')
    <span class="text-white badge bg-danger">
       {{ __('Disconnected') }}
    </span>
@elseif($value == 'connected')
    <span class="text-white badge" style="background-color: #0E6163;">
        {{ __('Connected') }}
    </span>
@endif
