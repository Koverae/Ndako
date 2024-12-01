@props([
    'value',

])
    <!-- Picking Policy -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

        <!-- Left pane -->
        @if($value->checkbox)
        <div class="k_setting_left_pane">
            <div class="k_field_widget k_field_boolean">
                <div class="k-checkbox form-check d-inline-block">
                    <input type="checkbox" wire:model.live="{{ $value->model }}" class="form-check-input" onclick="checkStatus(this)">
                </div>
            </div>
        </div>
        @endif
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
            @if($this->has_shipping_sms_confirmation)
            <div class="mt16">
                <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3">
                    <span class=" w-">
                        Modèle de SMS
                    </span>
                    <select class="k_input" id="">
                        <option value="delivery">Livraison: Envoyé par SMS</option>
                    </select>
                </div>
            </div>
            @endif
        </div>

    </div>
