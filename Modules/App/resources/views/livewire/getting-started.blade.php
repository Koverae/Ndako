
<div>

    <section class="overflow-x-hidden page page-center" style="height: 100%;">

        <div class="row align-items-center g-4 started">
            <div class="col-lg d-none d-lg-block started-background">
            </div>
            <div class="col-lg">
                <div class="container py-4">
                    <div class="mt-0 mb-2 text-center">
                        <a href="#" class="navbar-brand navbar-brand-autodark">
                            <img src="{{ asset('assets/images/logo/logo-circle-white.png') }}" style="height: 150px;" alt="Tabler" class="image">
                        </a>
                    </div>
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <!-- Session Status -->

                    <form class="row" id="getStarted">
                        @csrf
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="name">Business Name</label>
                            <input type="text" class="form-control" placeholder="eg. Mamba Residences" id="name" wire:model="name" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="company">Business Type</label>
                            <select class="form-control" wire:model="type" id="" required>
                                <option value="">{{ __('Select your business type') }}</option>
                                @foreach($typesOptions as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="language">Language</label>
                            <select class="form-control" wire:model="language" id="" required>
                                <option value="">{{ __('Select your language') }}</option>
                                @foreach($languagesOptions as $language)
                                <option {{ $language->iso_code == $currentLanguage ? 'selected' : '' }} value="{{ $language->id }}">{{ $language->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('language')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="currency">Business Currency</label>
                            <select class="form-control" wire:model="currency" id="" required>
                                <option value="">{{ __('Select your currency') }}</option>
                                @foreach($currenciesOptions as $currency)
                                <option {{ $currency->code == $currentCurrency ? 'selected' : '' }} value="{{ $currency->id }}">{{ $currency->currency_name }} ({{ $currency->code }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="rooms">Number of Rooms/Units</label>
                            <input type="text" class="form-control" placeholder="eg. 25" id="rooms" wire:model="rooms" value="{{ old('rooms') }}">
                            <x-input-error :messages="$errors->get('rooms')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="website">Website</label>
                            <input type="text" class="form-control" placeholder="eg. https://koverae.com" id="website" wire:model="website">
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="city">City</label>
                            <input type="text" class="form-control" placeholder="eg. Nairobi" id="city" wire:model="city" required>
                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="country">Country</label>
                            <select class="form-control" wire:model="country" id="" required>
                                <option value="">{{ __('Where is your company based?') }}</option>
                                @foreach($countriesOptions as $country)
                                <option {{ $country->country_code == $currentCountry ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->common_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('country')" class="mt-2" />
                        </div>
                        <div class="mb-2 col-lg-12">
                            <label class="form-label" for="role">What your role in the business? *</label>
                            <select class="form-control" wire:model="role" id="role" required>
                                <option value="">{{ __('Select your role') }}</option>
                                @foreach($rolesOptions as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="mb-0 form-footer">
                            <span wire:click="getStarted" class="uppercase btn btn-primary w-100" wire:loading.class="d-none">
                                Get Started
                            </span>
                            <span wire:loading class="uppercase btn btn-primary w-100">
                                ....
                            </span>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </section>
</div>
