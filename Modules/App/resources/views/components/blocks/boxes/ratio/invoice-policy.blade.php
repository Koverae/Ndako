@props([
    'value',

])
    <!-- Customer Portal -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

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
            <div class="mt16">
                <div class="k_field_widget k_field_text k_read_only modify w-auto ps-3 text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Cette valeur est appliquée par défaut sur tous les nouveaux produits. Ceci peut être modifié dans la fiche du produit.">
                    <!-- What is ordered -->
                    <div>
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model.live="invoice_policy" name="invoice_policy" id="ordered" value="ordered" onclick="checkStatus(this)"/>
                            <label class="form-check-label k_form_label" for="ordered">
                                {{ __('Facturer ce qui est commandé') }}
                            </label>
                        </div>
                    </div>
                    <!-- What is delivered -->
                    <div>
                        <div class="form-check k_radio_item">
                            <input type="radio" class="form-check-input k_radio_input" wire:model.live="invoice_policy" name="invoice_policy" id="delivered" value="delivered" onclick="checkStatus(this)"/>
                            <label class="form-check-label k_form_label" for="delivered">
                                {{ __('Facturer ce qui est livré') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
