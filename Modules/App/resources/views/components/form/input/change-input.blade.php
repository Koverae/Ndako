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
    <div class="k_cell k_wrap_input flex-grow-1 ">

        @if($value->type == 'select')
        <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input" wire:change="calculatePrice" {{ $this->blocked ? 'disabled' : '' }}>
            <option value=""></option>
            @foreach($value->data as $val => $text)
                <option value="{{ $val }}">{{ $text }}</option>
            @endforeach
        </select>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        @else

        <input type="{{ $value->type }}" style="margin-left: 5px;" wire:model="{{ $value->model }}" wire:change="calculatePrice" class="p-0 k-input" placeholder="{{ $value->placeholder }}" id="${{ $value->key }}_0" {{ $this->blocked ? 'disabled' : '' }}>
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
        @endif
    </div>
</div>

