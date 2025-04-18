@props([
    'value',

])

                <!-- Purchase Order Approval -->
                <div class="k_settings_box col-12 col-lg-6 k_searchable_setting">

                    <!-- Left pane -->
                    <div class="k_setting_left_pane">
                        <div class="k_field_widget k_field_boolean">
                            <div class="k-checkbox form-check d-inline-block">
                                <input type="checkbox" wire:model.live="{{ $value->model }}" class="form-check-input" onclick="checkStatus(this)">
                            </div>
                        </div>
                    </div>
                    <!-- Right pane -->
                    <div class="k_setting_right_pane">
                        <div class="mt12">
                            <div class="w-auto k_field_widget k_field_chat k_read_only modify ps-3 fw-bold">
                                <span>{{ $value->label }}</span>
                            </div>
                            <div class="w-auto k_field_widget k_field_text k_read_only modify ps-3 text-muted">
                                <span>
                                    {{ $value->description }}
                                </span>
                            </div>
                        </div>
                        @if($this->has_approval_order)
                        <div class="mt16 ps-3 d-flex">
                            <span class="w-auto ">Montant minimum :</span>
                            {{-- <input type="text" class="k_input w-75"> --}}
                            <div class="row">
                            <input type="text" style="width: 50%;" wire:model="min_amount" class="k_input col-6" id="{{ $value->key }}_0">
                                <span class="col-6" style="width: 50%; margin: 0 0 12px 0;">XAF</span>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>
