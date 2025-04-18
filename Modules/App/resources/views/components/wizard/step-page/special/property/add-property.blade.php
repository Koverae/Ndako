@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="mt-2 border shadow-sm col-12 col-md-8 card">
        <div class="card-header d-block">
            <h2 class="h2">Add Your Property 🏡</h2>
            <p>Let’s add your property in a few easy steps. This helps you manage bookings and operations efficiently.</p>

        </div>
        <div class="card-body">
            <form wire:submit.prevent="addProperty">
                @csrf
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <h1 class="flex-row d-flex align-items-center">
                            <input type="text" wire:model="name" class="k-input" id="name-k" placeholder="What’s this property called?" >
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </h1>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label h3">
                        {{ __('What type of property is it?') }}
                    </label>
                    <select class="form-select" wire:model="type" required>
                        <option value="">-- Choose --</option>
                        @foreach ($this->propertyTypes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-2 row align-items-start">
                    <div class="d-flex">
                        <textarea wire:model="description" class="p-0 m-0 textearea k-input" placeholder="{{ __('Tell me a bit about your property. What makes it awesome?') }}" id="description">
                        </textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3 col-md-12">
                    <label for="floors" class="form-label h3">
                        {{ __('How many floors/sections are we stacking?') }}
                    </label>
                    <div class="gap-2 d-flex">
                        <input type="number" class="form-control @error('floors') is-invalid @enderror"
                        id="floors" wire:model.live="floors" style="width: 100px;">
                    </div>
                    @error('floors')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <!-- Floors -->
                    <div class="row {{ $this->floors >= 1 ? '' : 'd-none' }}">
                        @for($i = 0; $i < $this->floors; $i++)
                            <div class="gap-2 mt-2 mb-2 col-12 d-flex align-items-center">
                                <div class="col-4">
                                    <label for="floor-name-{{ $i }}">Name</label>
                                    <input type="text" class="form-control @error('propertyFloors.' . $i . '.name') is-invalid @enderror"
                                           id="floor-name-{{ $i }}"
                                           wire:model="propertyFloors.{{ $i }}.name"
                                           placeholder="e.g. Ground Floor">
                                    @error('propertyFloors.' . $i . '.name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="floor-description-{{ $i }}">Description</label>
                                    <input type="text" class="form-control @error('propertyFloors.' . $i . '.description') is-invalid @enderror"
                                           id="floor-description-{{ $i }}"
                                           wire:model="propertyFloors.{{ $i }}.description"
                                           placeholder="e.g. Main entrance and lobby.">
                                    @error('propertyFloors.' . $i . '.description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="cursor-pointer" wire:click.prevent="removeFloor({{ $i }})">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        @endfor
                    </div>
                    <!-- Floors End -->
                </div>
                <div class="mb-3 col-md-12">
                    <label for="selectedAmenity" class="form-label h3">
                        {{ __('What can guests use at your hotel?') }}
                    </label>
                    <div class="row">
                        @forelse(current_company()->amenities as $amenity)
                        <div class="gap-2 mb-2 cursor-pointer col-6 d-flex">
                            <input type="checkbox" class="form-check-input k-checkbox @error('selectedAmenity') is-invalid @enderror" id="amenity_{{ $amenity->id }}" wire:model="selectedAmenity" value="{{ $amenity->id }}">
                            <label for="amenity_{{ $amenity->id }}" class="">{{ $amenity->name }}</label>
                        </div>
                        @empty
                            <p class="text-muted">{{ __('No amenities available.') }}</p>
                        @endforelse
                    </div>
                    @error('selectedAmenity')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                    @enderror
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
                                <input type="text" wire:model="street" id="" class="p-0 k-input w-100" placeholder="{{ __('Street ....') }}">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="city" id="city_0" class="p-0 k-input w-100" placeholder="{{ __('City') }}">
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <select wire:model="state" class="p-0 k-input w-100" id="state_id_0">
                                    <option value="">{{ __('State') }}</option>
                                </select>
                            </div>
                            <div class="col-4 d-flex align-items-center" style="margin-bottom: 10px;">
                                <input type="text" wire:model="zip" id="zip_0" class="p-0 k-input w-100" placeholder="{{ __('ZIP') }}">
                            </div>
                            <div class="col-12" style="margin-bottom: 10px;">
                                <select wire:model="country" class="k-input w-100" id="country_id_0">
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
                        {{-- <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span> --}}

                        <button type="submit" class="btn btn-primary go-next" {{ $this->currentStep == count($this->steps()) - 1 ? 'disabled' : '' }}>
                            <span class="text-uppercase" wire:loading.remove>Continue</span>
                            <span class="text-uppercase" wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
