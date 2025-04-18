@props([
    'value'
])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif


    <input type="text" wire:model="{{ $value->model }}" class="w-auto k_input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>

    <span class="mt-3 d-block">
        @foreach($this->authorizedDomains as $value => $text)
        <a class="cursor-pointer badge rounded-pill k_web_settings_users">
            {{ $text }}
            <i wire:click.prevent="removeDomain" wire:confirm="{{ __('Are you sure you want to remove this domain?') }}" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de"></i>
        </a>
        @endforeach
    </span>

</div>
