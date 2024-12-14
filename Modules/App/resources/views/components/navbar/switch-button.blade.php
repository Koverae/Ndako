@props([
    'value',
    'data'
])
<button  title="{{ ucfirst($value->key) }} view" class="k_switch_view btn btn-secondary {{ $value->key == $this->view_type ? 'active' : '' }} k_list" wire:click.prevent="switchView('{{ $value->key }}')">
    <i class="bi {{ $value->icon }}"></i>
</button>
