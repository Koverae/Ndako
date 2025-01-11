@props([
    'value',
])
@php
    // $contact = \Modules\Contact\Entities\Contact::find($value);
    $contact = \App\Models\User::find($value);
@endphp
<div>
    @if($contact)
    <a style="text-decoration: none" class="primary" wire:navigate href="{{ Route::subdomainRoute('settings.users.show', ['user' => $contact->id]) }}"  tabindex="-1">
        {{ $contact->name ?? '' }}
    </a>
    @endif
</div>
