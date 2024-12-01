@props([
    'value'
])
<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} : 
    </span>
    @endif

    @if($value->type == 'select')
    <div class="flex-wrap gap-1 k_field_tags d-inline-flex k_tags_input k_input">
        @foreach ($value->data['data'] as $id => $data)
        <span class="w-auto k_tag d-inline-flex align-items-center badge rounded-pill k_tag_color_0">
            <div class="k_tag_badge_text text-truncate">
                {{ $data }}
                <a wire:click="" wire:tagert="" class="opacity-75 cursor-pointer k_delete opacity-100-hover ps-1">
                    <i class="bi bi-x {{ $this->blocked ? 'd-none' : '' }}"></i>
                </a>
            </div>
        </span>
        @endforeach
    </div>
    <input type="{{ $value->type }}" wire:model="{{ $value->model }}" class="w-auto k_input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    @endif
    
    {{-- <div class="flex-wrap gap-1 k_field_tags d-inline-flex k_tags_input k_input">
        @foreach ($value->data['data'] as $value => $text)

        <span class="w-auto k_tag d-inline-flex align-items-center badge rounded-pill k_tag_color_0">
            <div class="k_tag_badge_text text-truncate">
                {{ $text }}
                <a wire:click="" wire:tagert="" class="opacity-75 cursor-pointer k_delete opacity-100-hover ps-1">
                    <i class="bi bi-x {{ $this->blocked ? 'd-none' : '' }}"></i>
                </a>
            </div>
        </span>
        @endforeach

        <input type="text" wire:model="{{ $value->model }}" class="w-auto k_input" placeholder="{{ $value->placeholder }}" id="{{ $value->model }}">
    </div> --}}
    {{-- @error($value->model) <span class="text-danger">{{ $message }}</span> @enderror --}}
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
</div>