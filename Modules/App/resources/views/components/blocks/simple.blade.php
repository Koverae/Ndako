@props([
    'value',

])
<div>

    <div id="{{ $value->key }}" class="setting_block">
        <h2>{{ $value->label }}</h2>
        <div class="row mt16 k_settings_container">
            <!-- Setting Box -->
            @foreach($this->boxes() as $box)
                @if($box->block == $value->key)
                    <x-dynamic-component
                        :component="$box->component"
                        :value="$box"
                    >
                    </x-dynamic-component>
                @endif
            @endforeach
        </div>
    </div>

</div>
