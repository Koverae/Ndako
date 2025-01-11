@props([
    'value',
    'data'
])
@if($value->label)
<span for="" class="k_form_label font-weight-bold">{{ $value->label }}</span>
@endif
<h1 class="flex-row d-flex align-items-center">
    <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="k-input" id="name-k" placeholder="{{ $value->placeholder }}" {{ $this->blocked ? 'disabled' : '' }}>
    @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror
</h1>
