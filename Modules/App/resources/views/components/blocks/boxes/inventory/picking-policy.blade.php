@props([
    'value',

])
    <!-- Picking Policy -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12">
                <div class="k_field_widget k_field_chat k_read_only modify w-auto ps-3 fw-bold">
                    <span>{{ $value->label }}</span>
                    @if($value->help)
                    <a href="{{ $value->help }}" target="__blank" title="documentation" class="k_doc_link">
                        <i class="bi bi-question-circle-fill"></i>
                    </a>
                    @endif
                </div>
                <div class="k_field_widget k_field_text k_read_only modify w-auto ps-3 text-muted">
                    <span>
                        {{ $value->description }}
                    </span>
                </div>
            </div>
            <div class="mt16">
                <div class="k_field_widget k_field_text k_read_only modify w-auto ps-3 text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Possibilité de sélectionner un type de colis dans les commandes clients et de forcer une quantité qui est un multiple du nombre d'unités par colis.">
                    <select wire:model="{{ $value->model }}" class="k_input" id="{{ $value->model }}">
                        <option value="as_soon_as_possible" selected>Expédier les produits dès qu'ils sont disponibles</option>
                        <option value="after_done">Expédiez tous les produits en même temps</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
