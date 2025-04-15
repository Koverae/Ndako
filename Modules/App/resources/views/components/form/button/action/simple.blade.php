@props([
    'value'
])
<div>
    <li class="d-lg-none"><a class="dropdown-item" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}">{{ $value->label }} <span wire:loading wire:target="{{ $value->action }}" >...</span></a></li>
</div>
