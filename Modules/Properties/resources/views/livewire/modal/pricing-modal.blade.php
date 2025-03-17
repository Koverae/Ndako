<div>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">
                {{ $unitType->name }} {{ __('Pricing') }}
            </h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>
        <div class="modal-body">
            <div class="k_form_nosheet">
                <div class="k_inner_group row">
                    <div class="col-12">
                        <label class="form-label h3">
                            {{ __('How much do you want to charge?') }}
                        </label>

                        @if(count($unitPrices) > 0)
                            <div class="row mt-2">
                                @foreach($unitPrices as $i => $price)
                                    <!-- Rate Type -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="rateType-{{ $i }}" class="form-label">
                                            {{ __('Rate Type') }}
                                        </label>
                                        <select wire:model="unitPrices.{{ $i }}.rate_type" id="rateType-{{ $i }}" class="form-control" style="width: 200px;">
                                            <option value="">{{ __('--- Choose ---') }}</option>
                                            @foreach ($this->leaseTerms as $value => $text)
                                                <option value="{{ $value }}">{{ $text }}</option>
                                            @endforeach
                                        </select>
                                        @error("unitPrices.$i.rate_type") <div class="mt-1 text-danger">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Rate -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="unitRate-{{ $i }}" class="form-label">{{ __('Rate') }}</label>
                                        <div class="input-icon">
                                            <span class="input-icon-addon font-weight-bolder">
                                                {{ settings()->currency->symbol }}
                                            </span>
                                            <input type="number" placeholder="18,900" class="form-control @error('unitPrices.{{ $i }}.rate') is-invalid @enderror"
                                            id="unitRate-{{ $i }}" wire:model="unitPrices.{{ $i }}.rate" style="width: 200px;">
                                        </div>
                                        @error("unitPrices.$i.rate") <div class="mt-1 text-danger">{{ $message }}</div> @enderror
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">{{ __('Including taxes and charges') }}</span>
                                        </div>
                                    </div>

                                    <!-- Is Default -->
                                    <div class="mb-3 col-md-12 col-lg-4">
                                        <label for="unitDefault-{{ $i }}" class="form-label mb-2">
                                            {{ __('Is Default') }}
                                        </label>
                                        <input type="checkbox" class="form-control form-check-input @error('unitPrices.{{ $i }}.default') is-invalid @enderror"
                                            id="unitDefault-{{ $i }}" wire:model="unitPrices.{{ $i }}.default" wire:change="setDefault({{ $i }})">
                                        @error("unitPrices.$i.default") <div class="mt-1 text-danger">{{ $message }}</div> @enderror
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">&nbsp;</span>
                                            <span class="cursor-pointer text-end" wire:click.prevent="removePricing({{ $i }})">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Add Pricing Button -->
                        <span class="cursor-pointer fw-bolder border rounded p-2" wire:click.prevent="addPricing">
                            <i class="bi bi-plus-circle"></i> {{ __('Add Pricing') }}
                        </span>

                        <!-- Success Message -->
                        @if (session()->has('message'))
                            <div class="alert alert-success mt-3">
                                {{ session('message') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <div class="p-0 modal-footer">
            <button class="btn btn-secondary text-uppercase" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
            <button class="btn btn-primary text-uppercase" wire:click.prevent="save">{{ __('Save') }}</button>
        </div>
    </div>
</div>
