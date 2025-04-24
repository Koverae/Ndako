@props([
    'value'
])
@if($value->parent)
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif


    <input type="text" wire:model="{{ $value->model }}" class="w-auto k-input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold" wire:click="addDomain" wire:target="addDomain"></i>
    <br>
    @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror

    <span class="mt-3 d-block">
        @foreach($this->authorizedDomains as $domain)
        <a href="{{ $domain }}" target="__blank" class="cursor-pointer badge rounded-pill k_web_settings_users">
            <i class="fas fa-external-link-alt fs-4"></i>
            {{ $domain }}
            <i wire:click.prevent="removeDomain('{{ $domain }}')" wire:target="removeDomain" wire:confirm="{{ __('Are you sure you want to remove this domain?') }}" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Remove this domain') }}"></i>
        </a>
        @endforeach
    </span>

</div>
@endif
