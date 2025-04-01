@props([
    'value',
    'data'
])
@if($value->label)
<span for="" class="k_form_label font-weight-bold">{{ $value->label }}</span>
@endif
<h1 class="flex-row mb-3 d-flex align-items-center">
    <input type="{{ $value->type }}" wire:model.blur="{{ $value->model }}" class="pb-2 k-input w-100 fs-1" id="" style="font-size: " placeholder="{{ $value->placeholder }}" {{ $this->blocked ? 'disabled' : '' }}>
    @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
</h1>
