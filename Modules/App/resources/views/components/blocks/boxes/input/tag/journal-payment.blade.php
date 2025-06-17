@props([
    'value',
    'data'
])

<div class="mt-3 ps-3">
    @if($value->label)
    <span>
        {{ $value->label }} :
    </span>
    @endif

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
                @foreach($data['data'] as $value => $text)
                @php
                    $journal = \Modules\RevenueManager\Models\Accounting\Journal::find($text);
                @endphp
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    {{ $journal->name }}
                    @if($data['delete'])
                    <i wire:click.prevent="{{ $data['delete'] }}('{{ $journal->id }}')" wire:confirm="Are you sure you want to remove {{ $journal->name }} ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove {{ $journal->name }}"></i>
                    @endif
                </a>
                @endforeach
            </span>
        </div>
</div>
