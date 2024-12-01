@props([
    'value',

])
    <!-- Picking Policy -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12">
                <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                    <span>{{ $value->label }}</span>
                    @if($value->help)
                    <a href="{{ $value->help }}" target="__blank" title="documentation" class="k_doc_link">
                        <i class="bi bi-question-circle-fill"></i>
                    </a>
                    @endif
                </div>
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <span>
                        {{ $value->description }}
                    </span>
                </div>
            </div>
            <div class="mt16">
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                    <div class=" content-group">
                        <input id="annual_inventory_day_0 " type="text" class="w-5 k_input" wire:model="annual_inventory_day">
                        <select class="k_input w-50" id="" wire:model="annual_inventory_month">
                            <option value=""></option>
                            <option value="january">{{ __('January') }}</option>
                            <option value="february">{{ __('February') }}</option>
                            <option value="march">{{ __('March') }}</option>
                            <option value="april">{{ __('April') }}</option>
                            <option value="may">{{ __('May') }}</option>
                            <option value="june">{{ __('June') }}</option>
                            <option value="july">{{ __('July') }}</option>
                            <option value="august">{{ __('August') }}</option>
                            <option value="september">{{ __('September') }}</option>
                            <option value="october">{{ __('October') }}</option>
                            <option value="november">{{ __('November') }}</option>
                            <option value="december">{{ __('December') }}</option>
                        </select>

                    </div>
                </div>
            </div>
        </div>

    </div>
