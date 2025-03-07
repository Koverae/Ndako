@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Set Up Your Branding & Preferences 🎭</h2>
            <p>Customize Ndako to reflect your brand and business identity.</p>

        </div>
        <div class="card-body">

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="submitCompany">
                @csrf

                <!-- Avatar -->
                <div class="p-0 m-0 k_employee_avatar rounded-cirlce">
                    <!-- Image Uploader -->
                    @if($this->photo != null)
                    <img src="{{ $this->photo->temporaryUrl() }}" alt="image" class="img img-fluid">
                    @else
                    <img src="{{ $this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/default_logo.png') }}" alt="image" class="img img-fluid">
                    @endif
                    <!-- <small class="k_button_icon">
                        <i class="align-middle bi bi-circle text-success"></i>
                    </small>-->
                    <!-- Image selector -->
                    <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" onclick="document.getElementById('photo').click();">
                            <i class="bi bi-pencil"></i>
                            <input type="file" wire:model.blur="photo" id="photo" style="display: none;" />
                        </span>
                        @if($this->photo || $this->image_path)
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="$cancelUpload('photo')" wire:target="$cancelUpload('photo')">
                            <i class="bi bi-trash"></i>
                        </span>
                        @endif
                    </div>
                    @error('photo') <span class="error">{{ $message }}</span> @enderror
                </div>
                <!-- Avatar -->

                <div class="row ">

                    <div class="mb-4 col-lg-6 col-md-12">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" wire:model="companyEmail" class="form-control w-full" placeholder="e.g. contact@yourcompany.co.ke">
                        @error('companyEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4 col-lg-6 col-md-6">
                        <label class="block text-sm font-medium">Phone</label>
                        <input type="tel" wire:model="companyPhone" class="form-control w-full" placeholder="e.g. +254 745 908026">
                        @error('companyPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="k_inner_group col-md-6 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">

                        <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Where is this magical place located?') }}
                        </div>
                    </div>
                    <div class="k_address_format w-100">
                        <div class="row">
                            <div class="col-12" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyStreet" id="" class="p-0 k-input w-100" placeholder="{{ __('Street ....') }}">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyCity" id="city_0" class="p-0 k-input w-100" placeholder="{{ __('City') }}">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <select wire:model="companyState" class="p-0 k-input w-100" id="state_id_0">
                                    <option value="">{{ __('State') }}</option>
                                </select>
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="companyZip" id="zip_0" class="p-0 k-input w-100" placeholder="{{ __('ZIP') }}">
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                                <select wire:model="companyCountry" class="k-input w-100" id="country_id_0">
                                    <option value="">{{ __('Country') }}</option>
                                    @foreach($this->countries as $value => $text)
                                    <option {{ $value == current_company()->country_id ? 'selected' : '' }} value="{{ $value }}">{{ $text }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                    </div>


                </div>


                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <span class="btn cancel" wire:click="goToNextStep">{{ __('Skip') }}</span>
                        <button type="submit" class="btn btn-primary go-next" {{ $this->currentStep == count($this->steps()) - 1 ? 'disabled' : '' }}>
                            <span wire:loading.remove>Continue</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
