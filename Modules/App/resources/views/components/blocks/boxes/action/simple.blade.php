@props([
    'value'
])
<div>
    @if($value->type == 'link')
    <a wire:navigate href="{{ $value->action }}" class="outline-none btn btn-link k_web_settings_access_rights">
        <i class="bi {{ $value->icon }} k_button_icon"></i> <span>{{ $value->label }}</span>
    </a>
    @elseif($value->type == 'modal')
    <span class="p-4 outline-none cursor-pointer k_web_settings_access_rights"  onclick="Livewire.dispatch('openModal', {!! $value->action !!})">
        <i class="bi {{ $value->icon }} k_button_icon"></i> <span>{{ $value->label }}</span>
    </span>
    @endif
    
    <br>
</div>