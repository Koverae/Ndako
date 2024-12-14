@props([
    'value',
])
@php
    $bank = \Modules\Contact\Entities\Bank\Bank::find($value);
@endphp
<div>
    <a style="text-decoration: none " wire:navigate href="{{ route('contacts.banks.show', ['bank' => $bank->id, 'subdomain' => current_company()->domain_name, 'menu' => current_menu()]) }}"  tabindex="-1">
        {{ $bank->name ?? '' }}
    </a> 
</div>
