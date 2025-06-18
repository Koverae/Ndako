@props([
    'value',
    'data'
])

<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    @if($value->label)
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <label class="k_form_label">
            {{ $value->label }}
            @if($value->help)
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="{{ $value->help }}"></i></sup>
            @endif
        </label>
    </div>
    @endif
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1 {{ $value->type == 'tag' ? 'mb-4' : '' }} {{ $value->type == 'textarea' ? 'mb-4' : '' }}">

        <div class="mb-4 d-block w-100">
            <div class="mb-1 d-flex col-12">
                <select wire:model="{{ $value->model }}" id="{{ $value->model }}" class="k-input" {{ $this->blocked ? 'disabled' : '' }}>
                    <option value=""></option>
                    @foreach($value->data['options'] as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                </select>
                @if($data['action'])
                <i class="cursor-pointer bi bi-plus-circle fw-bold" wire:click="{{ $data['action'] }}"></i>
                @endif
            </div>
            <span class="col-12">
                @foreach($data['data'] ?? [] as $value => $text)
                @php
                    $tax = \Modules\RevenueManager\Models\Tax\Tax::find($text);
                @endphp
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    {{ $tax->name }}
                    @if($data['delete'])
                    <i wire:click.prevent="{{ $data['delete'] }}('{{ $tax->id }}')" wire:confirm="Are you sure you want to remove {{ $tax->name }} ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove {{ $tax->name }}"></i>
                    @endif
                </a>
                @endforeach
            </span>
        </div>
        <br>

    </div>
</div>
