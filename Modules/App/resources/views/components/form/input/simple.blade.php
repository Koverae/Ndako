@props([
    'value',
    'data'
])

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
    <div class="k_cell k_wrap_input flex-grow-1 {{ $value->type == 'tag' ? 'mb-4' : '' }} {{ $value->type == 'textarea' ? 'mb-4' : '' }}">

        @if($value->type == 'select')
        <select wire:model.live="{{ $value->model }}" id="{{ $value->model }}" class="k-input" {{ $value->disabled ? 'disabled' : '' }} {{ $this->blocked ? 'disabled' : '' }}>
            <option value=""></option>
            @foreach($value->data as $val => $text)
                <option value="{{ $val }}">{{ $text }}</option>
            @endforeach
        </select>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        {{-- Input Action --}}
        {{-- @if($value->action)
        <i class="cursor-pointer bi bi-plus-circle fw-bold" wire:click="{{ $value->action }}"></i>
        @endif --}}

        @elseif($value->type == 'tag')
        <div class="mb-4 d-block w-100">
            <div class="mb-1 d-flex col-12">
                <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input" {{ $this->blocked ? 'disabled' : '' }}>
                    <option value=""></option>
                    @foreach($value->data['options'] as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                </select>
                @if($data['action'])
                <i class="cursor-pointer bi bi-plus-circle fw-bold" wire:click="{{ $data['action'] }}"></i>
                @endif
            </div>
            <span class="col-12">
                @foreach($data['data'] as $value => $text)
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    {{ $text }}
                    @if($data['delete'])
                    <i wire:click.prevent="{{ $data['delete'] }}('{{ $value }}')" wire:confirm="Are you sure you want to remove {{ $text }} ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove {{ $text }}"></i>
                    @endif
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

        @elseif($value->type == 'textarea')
        <textarea wire:model="{{ $value->model }}" class="p-0 m-0 textearea k-input" placeholder="{{ $value->placeholder }}" id="description" {{ $this->blocked ? 'disabled' : '' }}>

        </textarea>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        @else

        <input type="{{ $value->type }}" style="margin-left: 5px;" wire:model="{{ $value->model }}" wire:change="calculatePrice" class="p-0 k-input" placeholder="{{ $value->placeholder }}" id="date_0" {{ $value->disabled ? 'disabled' : '' }} {{ $this->blocked ? 'disabled' : '' }}>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        @endif

    </div>
</div>

