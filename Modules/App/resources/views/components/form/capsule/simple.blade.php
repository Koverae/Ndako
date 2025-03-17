@props([
    'value',
    'status'
])
<!-- Invoice -->
<div class="form-check k_radio_item" id="capsule">
    <i class="k_button_icon {{ $value->icon }}" style="margin-right: 5px;"></i>
    @if($value->type == 'link')
    <a style="text-decoration: none;" title="{{ $value->help }}" wire:navigate href="{{ $value->action }}">
        <span class="k_horizontal_span">{{ $value->label }}</span>
        @if($value->data)
        <span class="stat_value text-muted d-none d-lg-flex">
            {{ $value->data['amount'] }} Items
        </span>
        @endif
    </a>
    @elseif($value->type == 'modal')
    <a style="text-decoration: none;" title="{{ $value->help }}" onclick="Livewire.dispatch('openModal', {!! $value->action !!})">
        <span class="k_horizontal_span">{{ $value->label }}</span>
        @if($value->data)
        <span class="stat_value text-muted d-none d-lg-flex">
            {{ $value->data['amount'] }} Items
        </span>
        @endif
    </a>
    @elseif($value->type == 'action')
    <a style="text-decoration: none;" title="{{ $value->help }}" wire:click="{{ $value->action }})">
        <span class="k_horizontal_span">{{ $value->label }}</span>
        @if($value->data)
        <span class="stat_value text-muted d-none d-lg-flex">
            {{ $value->data['amount'] }} Items
        </span>
        @endif
    </a>
    @endif
</div>
