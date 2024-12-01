@props([
    'value'
])

<div class="w-auto pt-2 k_field_widget k_field_text k_read_only modify ps-3 text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Cette valeur est appliquée par défaut sur tous les nouveaux produits. Ceci peut être modifié dans la fiche du produit.">
    <!-- What is ordered -->
    @foreach($value->data as $option)
    <div class="form-check k_radio_item">
        <input type="{{ $value->type }}" class="form-check-input" name="{{ $value->model }}" wire:model.blur="{{ $value->model }}" id="{{ $option['value'] }}" value="{{ $option['value'] }}"/>
        <label class="form-check-label k_form_label" for="{{ $option['value'] }}">
                {{ $option['label'] }}
        </label>
    </div>
    @endforeach
</div>

{{-- <div class="k_field_widget k_field_radio k_light_label ps-3">
    <div class="k_horizontal">
        @foreach($value->data as $option)
        <div class="form-check k_radio_item">
            <input type="{{ $value->type }}" class="form-check-input" name="{{ $value->model }}" wire:model.blur="{{ $value->model }}" id="{{ $option['value'] }}" value="{{ $option['value'] }}"/>
            <label class="form-check-label k_form_label" for="{{ $option['value'] }}">
                {{ $option['label'] }}
            </label>
        </div>
        @endforeach
    </div>
</div> --}}