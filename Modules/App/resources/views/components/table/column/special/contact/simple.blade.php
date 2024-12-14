@props([
    'value',
])
@php
    $contact = \Modules\Contact\Entities\Contact::find($value);
@endphp
<div>
    {{ $contact->name ?? '' }} 
</div>
