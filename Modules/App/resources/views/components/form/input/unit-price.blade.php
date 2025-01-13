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
    <div class="gap-1 k_cell k_wrap_input flex-grow-1 d-flex">
        @if($this->unitPrice)
        <span>
            {{ format_currency($this->unitPrice) ?? '' }} / {{ __('Night') }} 
            {{-- {{ format_currency($this->unitPrice->price) ?? '' }} {{ $this->unitPrice->lease->name ?? '' }}  --}}
        </span>
        @endif

    </div>
</div>

