@props([
    'value'
])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif

    <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input">
        <option value=""></option>
        @foreach($value->data['options'] as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    <span class="mt-3 d-block">
        @foreach($value->data['data'] as $value => $text)
        <a class="cursor-pointer badge rounded-pill k_web_settings_users">
            {{ $text }}
            <i wire:click.prevent="" wire:confirm="Êtes-vous sûr de vouloir annuler l'invitation de ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de"></i>
        </a>
        @endforeach
    </span>

    {{-- <div class="flex-wrap gap-1 k_field_tags d-inline-flex k_tags_input k_input">
        @foreach ($value->data['options'] as $text)

        <span class="w-auto k_tag d-inline-flex align-items-center badge rounded-pill k_tag_color_0">
            <div class="k_tag_badge_text text-truncate">
                {{ $text }}
                <a wire:click="" wire:tagert="" class="opacity-75 cursor-pointer k_delete opacity-100-hover ps-1">
                    <i class="bi bi-x {{ $this->blocked ? 'd-none' : '' }}"></i>
                </a>
            </div>
        </span>
        @endforeach

        <input type="text" wire:model="{{ $value->model }}" class="w-auto k_input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    </div> --}}
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>
