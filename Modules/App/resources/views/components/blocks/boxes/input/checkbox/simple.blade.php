@props([
    'value'
])

<div class="mt-2 d-flex ps-3">
    <div class="k-checkbox form-check d-inline-block">
        <input type="checkbox" class="form-check-input" wire:model="{{ $value->model }}" class="form-check-input" style="" id="{{ $value->model }}">
    </div>
    <label>{{ $value->label }}</label>
    @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
</div>
