@props([
    'value',
])

<div class="d-flex" style="margin-bottom: 8px;">
    @if($value->label)
    <div class="k_cell k_wrap_label flex-grow-1 text-break text-900">
        <label class="k_form_label">
            {{ $value->label }} :
        </label>
    </div>
    @endif
    <div class="k_address_format w-100">
        <div class="row">
            <div class="col-12" style="margin-bottom: 10px;">
                <input type="text" wire:model="street" id="" class="p-0 k-input w-100" {{ $this->blocked ? 'disabled' : '' }} placeholder="{{ __('Street 1 ....') }}">
            </div>
            <div class="col-12" style="margin-bottom: 10px;">
                <input type="text" wire:model="street2" id="street2_0" class="p-0 k-input w-100" {{ $this->blocked ? 'disabled' : '' }} placeholder="{{ __('Street 2 ....') }}">
            </div>
            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="text" wire:model="city" id="city_0" class="p-0 k-input w-100" {{ $this->blocked ? 'disabled' : '' }} placeholder="{{ __('City') }}">
            </div>
            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                <select wire:model="state" class="p-0 k-input w-100" {{ $this->blocked ? 'disabled' : '' }} id="state_id_0">
                    <option value="">{{ __('State') }}</option>
                </select>
            </div>
            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                <input type="text" wire:model="zip" id="zip_0" class="p-0 k-input w-100" {{ $this->blocked ? 'disabled' : '' }} placeholder="{{ __('ZIP') }}">
            </div>
            <div class="col-12" style="margin-bottom: 10px;">
                <select wire:model="country" class="k-input w-100" {{ $this->blocked ? 'disabled' : '' }} id="country_id_0">
                    <option value="">{{ __('Country') }}</option>
                    @foreach(current_company()->countries() as $key => $country)
                    <option value="{{ $country->id }}">{{ $country->common_name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>
</div>
