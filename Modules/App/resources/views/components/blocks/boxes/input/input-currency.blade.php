@props([
    'value'
])

<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} : 
    </span>
    @endif
    
    @if($this->setting->default_currency_position == 'prefix')
    <span class="col-6" style="width: 30%; margin: 0 0 12px 0;">{{ $this->setting->currency->symbol }}</span>
    <input type="{{ $value->type }}" style="width: 50%;" wire:model="{{ $value->model }}" min="0" class="k-input" placeholder="{{ $value->placeholder }}" id="amount">
    @else
        <input type="{{ $value->type }}" style="width: 30%;" wire:model="{{ $value->model }}" min="0" class="k-input" placeholder="{{ $value->placeholder }}" id="amount">
        <span class="col-6" style="width: 30%; margin: 0 0 12px 0;">{{ $this->setting->currency->symbol }}</span>
    @endif
    {{-- <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="k-input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}"> --}}
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>