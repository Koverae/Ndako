@props([
    'value',
    'data'
])
@if($value->parent)
<div class="mt-3 ps-3" wire:transition.duration.500ms>
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif

    <input type="{{ $value->type }}" disabled wire:model="{{ $value->model }}" class="w-auto k-input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
    <i class="cursor-pointer fas fa-copy fw-bold" title="Copy"></i>
    
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
</div>
@endif