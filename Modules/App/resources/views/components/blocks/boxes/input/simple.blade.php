@props([
    'value',
    'data'
])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif

    @if($value->type == 'select')
    <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input w-75">
        <option value=""></option>
        @foreach($value->data as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>

    @elseif($value->type == 'tag')
    <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input w-75">
        <option value=""></option>
        @foreach($value->data['options'] as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>

    <span class="mt-3 d-block">
        @foreach($data['data'] as $value => $text)
        <a class="cursor-pointer badge rounded-pill k_web_settings_users">
            {{ $text }}
            <i wire:click.prevent="" wire:confirm="Êtes-vous sûr de vouloir annuler l'invitation de ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de"></i>
        </a>
        @endforeach
    </span>

    @elseif($value->type == 'textarea')
    <textarea wire:model="{{ $value->model }}" class="border textearea k-input" placeholder="{{ $value->placeholder }}" id="description" {{ $this->blocked ? 'disabled' : '' }}>
        {!! $value->model !!}
    </textarea>
    @elseif($value->type == 'price')
    <div class="mt-3 ps-3">
        @if($this->setting->default_currency_position == 'prefix')
        <span>{{ $this->setting->currency->symbol }}</span>
        <input type="text" class="k-input">
        @else
        <input type="text" class="k-input">
        <span>{{ $this->setting->currency->symbol }}</span>
        @endif
    </div>

    @else
    <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="w-auto k-input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
    @endif

    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
</div>
