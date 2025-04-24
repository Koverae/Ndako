@props([
    'value',
    'status'
])
<div>
    <li class="gap-2 cursor-pointer dropdown-item dropdown-hover kover-navlink" wire:click="{{ $value->action }}" wire:target="{{ $value->action }}">
        <i class="{{ $value->icon }}"></i> <span>{{ $value->label }}</span>
    </li>
    @if($value->separator)
    <li><hr class="separator"></li>
    @endif
</div>
