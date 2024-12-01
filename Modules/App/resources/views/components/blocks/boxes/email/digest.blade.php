@props([
    'value',

])
<!-- Digest Email -->
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
                @if($value->icon)
                    <i class="inline-block bi {{ $value->icon }}"></i>
                @endif
                <span class="ml-2">{{ $value->label }}</span>
                @if($value->help)
                <a href="{{ $value->help }}" target="__blank" title="documentation" class="k_doc_link">
                    <i class="bi bi-question-circle-fill"></i>
                </a>
                @endif
            </div>
            @if($value->description)
            <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                <span>
                    {{ $value->description }}
                </span>
            </div>
            @endif
        </div>
        <div class="mt16 ps-3">
            <span>Modèles :</span>
            <select name="digest" id="">
                <option value="koverae_digest">Koverae Digest</option>
            </select>
            <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
        </div>
        @if($value->actions)
        <div class="mt-2 d-block"   >
            @foreach($value->actions as $action)
            <a
                @if($action['is_link'] == true)
                wire:navigate href="{{ $action['action'] }}"
                @endif
                @if($action['is_link'] == false)
                    wire:click="{{ $action['action'] }}" wire:target="{{ $action['action'] }}"
                @endif
                class="outline-none btn btn-link k_web_settings_access_rights">
                <i class="bi {{ $action['icon'] }} k_button_icon"></i> <span>{{ $action['label'] }}</span>
            </a>
            <br>
            @endforeach
        </div>
        @endif
    </div>

</div>