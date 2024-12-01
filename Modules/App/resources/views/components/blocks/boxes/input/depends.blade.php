@props([
    'value'
])
@if(!$value->data['parent'])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} : 
    </span>
    @endif

    @if($value->type == 'select')
    <select wire:model="{{ $value->model }}" id="">
        @foreach($value->data['data'] as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    @elseif($value->type == 'textarea')
    <textarea wire:model="{{ $value->model }}" class="border textearea k-input" placeholder="{{ $value->placeholder }}" id="description" {{ $this->blocked ? 'disabled' : '' }}>
        {!! $value->model !!}
    </textarea>
    @else
    <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="k-input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    @endif
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>
@endif