@props([
    'value',
])
@php
    $contact = App\Models\User::find($value);
@endphp
<div>
    @if($contact)
    {{ $contact->name ?? '' }}
    @endif
</div>
