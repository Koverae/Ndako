@props([
    'value',
])
@if($value)
<div>
    {{ $value }} {{ __('Guest(s)') }}
</div>
@endif
