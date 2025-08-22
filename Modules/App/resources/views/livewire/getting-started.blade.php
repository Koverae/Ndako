@section('page_title', "Getting Started")

<section class="overflow-x-hidden page page-center" style="height: 100%;">

    <div class="row align-items-center g-4 started">
        <div class="col-lg d-none d-lg-block started-background">
        </div>
        <div class="col-lg">
            <div class="container py-4">
                <div class="mt-0 mb-2 text-center">
                    <a href="#" class="navbar-brand navbar-brand-autodark">
                        <img src="{{ asset('assets/images/logo/logo-circle-white.png') }}" style="height: 120px;" alt="Tabler" class="image">
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
                            <option {{ $language->iso_code == $this->currentLanguage ? 'selected' : '' }} value="{{ $language->id }}">{{ $language->name }}</option>
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
                        <label class="form-label" for="rooms">Number of Rooms/Units
                            <span class="cursor-pointer" data-bs-toggle="tooltip" data-bs-placement="right" title="Enter the total number of rooms or rental units you manage. This helps us recommend the best plan for your needs."><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                              </svg>
                            </span>
                        </label>
                        <input type="text" class="form-control" placeholder="eg. 25" id="rooms" wire:model="rooms" value="{{ old('rooms') }}">
                        <x-input-error :messages="$errors->get('rooms')" class="mt-2" />
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="form-label" for="website">Website</label>
                        <input type="text" class="form-control" placeholder="eg. https://koverae.com" id="website" wire:model="website">
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="form-label" for="country">Country ({{ $currentCountry }})</label>
                        <select class="form-control" wire:model.live="country" id="" required>
                            <option value="">{{ __('Where is your company based?') }}</option>
                            @foreach($countriesOptions as $country)
                            <option {{ $country->country_code == $currentCountry ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->common_name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('country')" class="mt-2" />
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label class="form-label" for="city">City</label>
                            <select wire:model="city" id="city" class="form-select">
                                <option value="">— Select a city —</option>
                                @foreach ($citiesOptions[$currentCountry] as $city)
                                <option value="{{ $city }}" @selected(old('city', $selectedCity ?? '') === $city)>
                                    {{ $city }}
                                </option>
                                @endforeach
                            </select>
                        <x-input-error :messages="$errors->get('city')" class="mt-2" />
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

                    <span class="text-sm text-gray-600 text-muted">
                        Enjoy your 7-days free trial! To continue using Ndako after your trial, you'll need to subscribe to a plan that fits your needs. <a href="https://ndako.koverae.com#pricing" target="__blank">See our pricing</a>
                    </span>

                    <div class="mb-0 form-footer">
                        <span wire:click="getStarted" class="uppercase btn btn-primary w-100" wire:loading.class="d-none">
                            Get Started
                        </span>
                        <span wire:loading.attr="disabled" wire:loading class="uppercase btn btn-primary w-100">
                            Loading...
                        </span>
                    </div>
                </form>


            </div>
        </div>
    </div>
</section>
