@props([
    'value'
])
@props([
    'value',
    'data'
])

<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        @if($value->label)
        <label class="k_form_label">
            {{ $value->label }}
            @if($value->help)
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="{{ $value->help }}"></i></sup>
            @endif
        </label>
        @endif
    </div>
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1">

        @if(settings()->default_currency_position == 'prefix')
            <span class="mt-0" style="margin-right: 10px; ">{{ settings()->currency->symbol }}</span>
            <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="p-0 k-input w-100" placeholder="{{ $value->placeholder }}" id="date_0" {{ $this->blocked ? 'disabled' : '' }}>
        @else
            <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="p-0 k-input" placeholder="{{ $value->placeholder }}" id="date_0" {{ $this->blocked ? 'disabled' : '' }}>
            <span class="" style="margin-right: 10px; ">{{ settings()->currency->symbol }}</span>
        @endif
        @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror

    </div>
</div>
