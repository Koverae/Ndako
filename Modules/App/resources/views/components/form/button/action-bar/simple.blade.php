@props([
    'value',
    'status'
])
<div class="{{ $value->parent ? 'd-none' : '' }}">
    <button class="d-none d-lg-inline-flex {{ $this->status == $value->primary ? 'btn btn-primary active' : '' }}" type="button" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}"  id="top-button">
        <span>
            {{ $value->label }} <span wire:loading wire:target="{{ $value->action }}" >...</span>
        </span>
    </button>
    <li class="d-lg-none"><a class="dropdown-item" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}">{{ $value->label }} <span wire:loading wire:target="{{ $value->action }}" >...</span></a></li>
</div>
