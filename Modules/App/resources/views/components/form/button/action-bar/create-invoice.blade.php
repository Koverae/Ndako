@props([
    'value',
    'status'
])

<div class="{{ $this->invoice == null && $status != 'canceled' ? 'd-none' : '' }}">
    <button class="d-none d-lg-inline-flex" type="button" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}"  id="top-button" class="btn btn-primary {{ $status == $value->primary ? 'primary' : '' }}">
        <span>
            {{ $value->label }} <span wire:loading wire:target="{{ $value->action }}" >...</span>
        </span>
    </button>
    <li class="d-lg-none"><a class="dropdown-item" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}">{{ $value->label }} <span wire:loading wire:target="{{ $value->action }}" >...</span></a></li>
</div>
