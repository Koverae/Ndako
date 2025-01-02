@props([
    'value',
    'data'
])
@if($this->invoicing == 'rental')
<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    @if($value->label)
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <label class="k_form_label">
            {{ $value->label }}
            @if($value->help)
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="{{ $value->help }}"></i></sup>
            @endif
        </label>
    </div>
    @endif
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1">

        @if($value->type == 'select')
        <select wire:model.blur="{{ $value->model }}" id="{{ $value->model }}" class="k-input">
            <option value=""></option>
            @foreach($value->data['data'] as $value => $text)
                <option value="{{ $value }}">{{ $text }}</option>
            @endforeach
        </select>

        @elseif($value->type == 'tag')
        <div class="d-block">
            <div class="d-flex mb-3">
                <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input w-100">
                    <option value=""></option>
                    @foreach($value->data['options'] as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                </select>
                <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
            </div>
            <span class="">
                @foreach($data['data'] as $value => $text)
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    {{ $text }}
                    <i wire:click.prevent="" wire:confirm="Are you sure you want to remove {{ $text }} ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove {{ $text }}"></i>
                </a>
                @endforeach
            </span>
        </div>
        <br>
        @elseif($value->type == 'price')

            @if(settings()->default_currency_position == 'prefix')
            <span style="margin-right: 10px; ">{{ settings()->currency->symbol }}</span>
            <input type="text" class="k-input" wire:model="{{ $value->model }}" id="{{ $value->key }}" {{ $this->blocked ? 'disabled' : '' }}>
            @else
            <input type="text" class="k-input" wire:model="{{ $value->model }}" id="{{ $value->key }}" {{ $this->blocked ? 'disabled' : '' }}>
            <span>{{ settings()->currency->symbol }}</span>
            @endif
        @else
        <input type="{{ $value->type }}" style="margin-left: 5px;" wire:model.blur="{{ $value->model }}" class="p-0 k-input" placeholder="{{ $value->placeholder }}" id="date_0" {{ $this->blocked ? 'disabled' : '' }}>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        @endif

    </div>
</div>
@endif