@props([
    'value',

])
    <!-- Customer Portal -->
    <div class="k_settings_box col-12 col-lg-6 k_searchable_setting" id="{{ $value->key }}">

        <!-- Left pane -->
        @if($value->checkbox)
        <div class="k_setting_left_pane">
            <div class="k_field_widget k_field_boolean">
                <div class="k-checkbox form-check d-inline-block">
                    <input type="checkbox" wire:model.live="{{ $value->model }}"  class="form-check-input" >
                </div>
            </div>
        </div>
        @endif
        <!-- Right pane -->
        <div class="k_setting_right_pane">
            <div class="mt12" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $value->comment }}">
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

                <!-- Box Inputs -->
                @if($this->inputs())
                @foreach($this->inputs() as $input)
                @if($input->box == $value->key)
                    <x-dynamic-component
                        :component="$input->component"
                        :value="$input"
                        :data="$input->data"
                    >
                    </x-dynamic-component>
                @endif
                @endforeach
                @endif

                <!-- Box Actions -->
                @if($this->actions())
                <div class="mt-2 d-block">
                    @foreach($this->actions() as $action)
                        @if($action->box == $value->key)
                            <x-dynamic-component
                                :component="$action->component"
                                :value="$action"
                            >
                            </x-dynamic-component>
                        @endif
                    @endforeach
                </div>
                @endif

            </div>
        </div>

    </div>
