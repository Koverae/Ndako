@props([
    'value',
])

<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    @if($value->label)
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <label class="k_form_label">
            {{ $value->label }}
            {{-- @if($value->help)
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="{{ $value->help }}"></i></sup>
            @endif --}}
        </label>
    </div>
    @endif
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1">
        {{-- <div class="d-block">
            <div class="mb-3 d-flex col-12">
                <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input w-100">
                    <option value=""></option>
                    @foreach($value->data['options'] as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                </select>
                <i class="cursor-pointer bi bi-plus-circle fw-bold" wire:click="addFeature"></i>
                <button 
                    type="button" 
                     
                    class="btn btn-primary ms-2"
                    
                >
                    Add
                </button>
            </div>
            <span class="col-12">
                @foreach($data['data'] as $value => $text)
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    {{ $text }}
                    <i wire:click.prevent="removeFeature('{{ $value }}')" wire:confirm="Are you sure you want to remove {{ $text }} ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove {{ $text }}"></i>
                </a>
                @endforeach
            </span>
        </div> --}}
    </div>

</div>