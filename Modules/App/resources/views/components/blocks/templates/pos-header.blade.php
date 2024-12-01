@props([
    'value',

])
<div>

    <div id="{{ $value->key }}" class="p-3 setting_block_pos">
        <h4><b>{{ $value->label }}</b></h4>
        <div class="p-1">
            <select wire:model="pos" class="w-auto k_input d-inline" style="background-color: transparent;" id="pos">
                @foreach($this->shops as $key => $shop)
                <option value="{{ $shop->id }}" {{ $this->pos->id = $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                @endforeach
            </select>
            
            <span class="cursor-pointer " style="color: #017E84; font-weight: 500;" onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-language'})">
                <i class="bi bi-plus k_button_icon"></i> <span>{{ __('New Shop') }}</span>
            </span>
        </div>
    </div>

</div>
