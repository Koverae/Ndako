@props([
    'value'
])

<div class="k_settings_box col-12 col-lg-6 k_searchable_setting">

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
            <div class="k_field_widget k_field_text k_read_only modify w-auto ps-3 text-muted">
                @php
                    $products = \Modules\Inventory\Entities\Product::isCompany(current_company()->id)->get();
                @endphp
                <select wire:model="{{ $value->model }}" class="k_input" id="{{ $value->key }}">
                    <option value=""></option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>
