<div>
    <!-- App Settings block -->
    <div class="app_settings_block">

        <!-- Setting Block -->
        @foreach ($this->blocks() as $block)
        <x-dynamic-component
            :component="$block->component"
            :value="$block"
        >
        </x-dynamic-component>
        @endforeach

    </div>
</div>
