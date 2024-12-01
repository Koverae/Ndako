@props([
    'value',

])
    <!-- Customer Portal -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

        <!--Left pane -->
        <div class="k_setting_left_pane">
            <div class="k_field_widget k_field_boolean">
                <div class="k-checkbox form-check d-inline-block">
                    <input type="checkbox" wire:model.live="has_pricelist" class="form-check-input" onclick="checkStatus(this)">
                </div>
            </div>
        </div>
        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12">
                <div class="k_field_widget k_field_chat k_read_only modify w-auto ps-3 fw-bold">
                    <span>{{ $value->label }}</span>
                </div>
                <div class="k_field_widget k_field_text k_read_only modify w-auto ps-3 text-muted">
                    <span>
                        {{ $value->description }}
                    </span>
                </div>
            </div>
            <div class="mt-12">
                <div class="k_field_widget k_field_radio k_light_label ps-3">
                    <div class="k_horizontal">
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model="{{ $value->model }}" name="{{ $value->model }}" value="multiple" id="multiple" onclick="checkStatus(this)" />
                            <label class="form-check-label k_form_label" for="multiple" data-bs-toggle="tooltip" data-bs-placement="right" title="Plusieurs prix par produit">
                                {{ __('Plusieurs') }}
                            </label>
                        </div>
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model="{{ $value->model }}" name="{{ $value->model }}" value="advanced" id="advanced" onclick="checkStatus(this)"/>
                            <label class="form-check-label k_form_label" for="advanced" data-bs-toggle="tooltip" data-bs-placement="right" title="Règles tarifaires avancées (remises, formules)">
                                {{ __('Avancée') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
