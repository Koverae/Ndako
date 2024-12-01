@props([
    'value'
])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} : 
    </span>
    @endif
    
    <input id="{{ $value->data['day'] }}_0 " type="text" class="w-5 k-input" wire:model="{{ $value->data['day'] }}">
    <select class="k-input w-50" id="" wire:model="{{ $value->data['month'] }}">
        <option value=""></option>
        <option value="january">{{ __('January') }}</option>
        <option value="february">{{ __('February') }}</option>
        <option value="march">{{ __('March') }}</option>
        <option value="april">{{ __('April') }}</option>
        <option value="may">{{ __('May') }}</option>
        <option value="june">{{ __('June') }}</option>
        <option value="july">{{ __('July') }}</option>
        <option value="august">{{ __('August') }}</option>
        <option value="september">{{ __('September') }}</option>
        <option value="october">{{ __('October') }}</option>
        <option value="november">{{ __('November') }}</option>
        <option value="december">{{ __('December') }}</option>
    </select>
    @error($value->data['day']) <span class="text-danger">{{ $message }}</span> @enderror
    @error($value->data['month']) <span class="text-danger">{{ $message }}</span> @enderror
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>