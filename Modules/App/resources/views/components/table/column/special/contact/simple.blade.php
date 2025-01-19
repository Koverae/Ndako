@props([
    'value',
])
@php
    // $contact = \Modules\Contact\Entities\Contact::find($value);
    $contact = Modules\ChannelManager\Models\Guest\Guest::find($value);
@endphp
<div>
    @if($contact)
    {{ $contact->name ?? '' }}
    @endif
</div>
