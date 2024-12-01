@props([
    'value'
])
@if($value->data['parent'])
<div class="mt-3 ps-3">
    @if($value->label)
    <span class="" style="margin-right: 5px;">
        {{ $value->label }}
    </span>
    @endif
    <div class="d-inline-block">
        <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="form-check-input" style="" id="{{ $value->model }}" onclick="checkStatus(this)" {{ $this->blocked ? 'disabled' : '' }}>
    </div>
    {{-- <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="w-auto k_input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}"> --}}
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
    {{-- <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i> --}}
</div>
@endif