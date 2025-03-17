@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Define Your Units 🏢</h2>
            <p>Now, let's add units to your property. Whether it's rooms, apartments, or offices, this step ensures accurate tracking and management.</p>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="submitProperty">
                @csrf
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <label class="h3" for="name-k">{{ __('What’s the name of this room/unit?') }}</label>
                        <h1 class="flex-row d-flex align-items-center">
                            <select wire:model="unitName" id="" class="form-control" id="name-k">
                                <option value="">-- Choose --</option>
                                @foreach ($this->unitTypes as $value => $text)
                                    <option value="{{ $text }}">{{ $text }}</option>
                                @endforeach
                            </select>
                            {{-- <input type="text" wire:model="unitName" class="k-input" id="name-k" placeholder="e.g. Standard Room" > --}}
                            @error('unitName') <span class="text-danger">{{ $message }}</span> @enderror
                        </h1>
                    </div>
                </div>
                <div class="mb-3 row align-items-start">
                    <div class="d-flex">
                        <textarea wire:model="unitDesc" class="p-0 m-0 textearea k-input" placeholder="{{ __('Tell me a bit about your type of unit. What makes it awesome?') }}" id="unitDesc">
                        </textarea>
                        @error('unitDesc') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mb-3 col-md-12">
                    <label for="numberUnits" class="form-label h3">
                        {{ __('How many rooms of this type do you have?') }}
                    </label>
                    <input type="number" class="form-control @error('numberUnits') is-invalid @enderror"
                        id="numberUnits" wire:model.live="numberUnits" style="width: 140px; height: 36px;" value="{{ old('numberUnits') }}">
                    @error('numberUnits')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                    <!-- Units -->
                    <div class="row {{ $this->numberUnits >= 1 ? '' : 'd-none' }}">
                        @for($i = 0; $i < $this->numberUnits; $i++)
                            <div class="gap-2 mt-2 mb-2 col-md-6 d-flex align-items-center">
                                <div class="col-4">
                                    <label for="unit-name-{{ $i }}">{{ __('Room Number') }}</label>
                                    <input type="text" class="form-control @error('units.' . $i . '.name') is-invalid @enderror"
                                           id="unit-name-{{ $i }}"
                                           wire:model="units.{{ $i }}.name"
                                           placeholder="{{ __('Room Number') }}">
                                    @error('units.' . $i . '.name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <label for="unit-floor-{{ $i }}">{{ __('Floor') }}</label>
                                    <select class="form-control @error('units.' . $i . '.floor') is-invalid @enderror" id="unit-floor-{{ $i }}" wire:model="units.{{ $i }}.floor">
                                        <option value="">{{ __('--- Choose ---') }}</option>
                                        @foreach ($this->propertyFloors as $floor)
                                            <option value="{{ $floor['name'] }}">{{ $floor['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('units.' . $i . '.floor')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">&nbsp;</span>

                                    <span class="cursor-pointer text-end" wire:click.prevent="removeTypeUnit({{ $i }})">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <!-- Units End -->
                </div>

                <div class="row">
                    <!-- Capacity -->
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="capacity" class="form-label h3">
                            {{ __('How many guests can stay in this room/unit?') }}
                        </label>
                        <div class="number-input-wrapper @error('capacity') is-invalid @enderror">
                            <span class="btn btn-link minus" wire:click="decreaseCapacity">−</span>
                            <input type="number" id="number-input" min="1" wire:model="capacity" class="number-input" />
                            <span class="btn btn-link plus" wire:click="increaseCapacity">+</span>
                        </div>
                        @error('capacity')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- Capacity End -->
                    <!-- Size -->
                    <div class="mb-3 col-md-12 col-lg-6">
                        <label for="unitSize" class="form-label h3">
                            {{ __('How big is this room/unit? (optional)') }}
                        </label>
                        <div class="gap-2 d-flex">
                            <input type="number" class="form-control @error('unitSize') is-invalid @enderror" id="unitSize" wire:model="unitSize" style="width: 140px; height: 36px;" value="{{ old('unitSize') }}">
                            <span class="p-2">Square metres</span>
                        </div>
                        @error('unitSize')
                        <div class="mt-1 text-danger">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <!-- Size End -->
                </div>

                <div class="col-12">
                    <label class="form-label h3">
                        {{ __('How much do you want to charge?') }}
                    </label>
                    <span class="cursor-pointer fw-bolder border rounded p-2" wire:click.prevent="addPricing" wire:target="addPricing">
                        <i class="bi bi-plus-circle"></i> {{ __('Add Pricing') }}
                    </span>
                    <div class="row mt-2 {{ $this->prices >= 1 ? '' : 'd-none' }}">
                        @for($i = 0; $i < $this->prices; $i++)
                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="rateType-{{ $i }}" class="form-label">
                                {{ __('Rate Type') }}
                            </label>
                            <select wire:model="unitPrices.{{ $i }}.rate_type" id="rateType-{{ $i }}" class="form-control" style="width: 200px;">
                                <option value="">--- Chose ---</option>
                                @foreach ($this->leaseTerms as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                                @endforeach
                            </select>
                            @error('unitPrices.{{ $i }}.rate_type')
                            <div class="mt-1 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <!-- Price End -->

                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="unitRate" class="form-label">
                                {{ __('Rate') }}
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon font-weight-bolder">
                                    {{ settings()->currency->symbol }}
                                </span>
                                <input type="number" placeholder="18,900" class="form-control @error('unitPrices.{{ $i }}.rate') is-invalid @enderror" id="unitRate" wire:model="unitPrices.{{ $i }}.rate" style="width: 200px;">
                            </div>
                            @error('unitPrices.{{ $i }}.rate')
                            <div class="mt-1 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ __('Including taxes and charges') }}</span>
                            </div>
                        </div>
                        <!-- Price End -->

                        <!-- Price -->
                        <div class="mb-3 col-md-12 col-lg-4">
                            <label for="unitDefault" class="form-label mb-2">
                                {{ __('Is Default') }}
                            </label>
                            <input type="checkbox" class="form-control form-check-input @error('unitPrices.{{ $i }}.rate') is-invalid @enderror" id="unitDefault" wire:model="unitPrices.{{ $i }}.default">
                            @error('unitPrices.{{ $i }}.default')
                            <div class="mt-1 text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">&nbsp;</span>

                                <span class="cursor-pointer text-end" wire:click.prevent="removePricing({{ $i }})">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Price End -->
                        @endfor

                    </div>
                </div>

                <!-- Features -->
                <div class="mb-3 col-md-12">
                    <label for="unitFeatures" class="form-label h3">
                        {{ __('What can guests use in this room/unit?') }}
                    </label>
                    <div class="row">
                        @foreach(current_company()->features as $feature)
                        <div class="gap-2 mb-2 cursor-pointer d-flex col-6 col-lg-4">
                            <input type="checkbox" class="form-check-input k-checkbox @error('unitFeatures') is-invalid @enderror" id="feature_{{ $feature->id }}" wire:model="unitFeatures" value="{{ $feature->id }}">
                            <label class="cursor-pointer" for="feature_{{ $feature->id }}" class="">{{ $feature->name }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('unitFeatures')
                    <div class="mt-1 text-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <!-- Features End -->

                <div class="mb-3 d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <span class="gap-1 btn btn-primary go-next text-end" wire:click="addUnit">{{ __('Add Room') }} <i class="fas fa-plus-circle"></i></span>
                </div>

                <div class="row {{ $this->propertyUnits ? '' : 'd-none' }}">
                    <h3 class="form-label h3">
                        {{ __('Do these sound like your rooms?') }}
                    </h3>
                    <!-- Unit Type -->
                    @foreach($this->propertyUnits as $index => $unit)
                    <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" style="min-height: 122px;">
                        <div class="p-2 border rounded" style="min-height: 122px;">
                            <div class="d-flex justify-content-between">
                                <h3 class="h3">{{ $unit['unitName'] }}</h3>
                                <span class="text-muted d-block">{{ $unit['capacity'] }} <i class="bi bi-people"></i></span>
                                <span class="text-end" wire:click="removeUnit({{ $index }})"><i class="fas fa-trash"></i></span>
                            </div>
                            <div class="mt-3 mb-3">
                                <p>
                                    {{ $unit['unitDesc'] }}
                                </p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>{{ $unit['numberUnits'] }} {{ __('Rooms') }}</span>
                                <div class="d-block">
                                    @if(count($unit['unitPrices']) >= 1)
                                        @foreach ($unit['unitPrices'] as $price)
                                        <span class="bottom-0 text-end">{{ format_currency($price['rate']) }} / {{ lease_term($price['rate_type'])->name }}</span> <br>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- Unit Type End -->

                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <button type="submit" class="btn btn-primary go-next text-uppercase">
                            {{__('Add Property')}}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
