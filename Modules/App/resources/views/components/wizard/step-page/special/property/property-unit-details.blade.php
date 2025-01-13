@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="m-3 text-center">
        <h1 class="h1">{{ __('What kind of units do we have?') }}</h1>
    </div>
    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">
            <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                <div class="ke-title mw-75 pe-2 ps-0">
                    <label class="h3" for="name-k">{{ __('What’s the name of this room?') }}</label>
                    <h1 class="flex-row d-flex align-items-center">
                        <input type="text" wire:model="unitName" class="k-input" id="name-k" placeholder="e.g. Standard Room" >
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
            <div class="mb-3 col-md-6">
                <label for="numberUnits" class="form-label h3">
                    {{ __('How many rooms of this type do you have?') }}
                </label>
                <input type="number" class="form-control @error('numberUnits') is-invalid @enderror"
                    id="numberUnits" wire:model="numberUnits" style="width: 140px; height: 36px;" value="{{ old('numberUnits') }}">
                @error('numberUnits')
                    <div class="mt-1 text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <!-- Capacity -->
            <div class="mb-3 col-md-12">
                <label for="capacity" class="form-label h3">
                    {{ __('How many guests can stay in this room?') }}
                </label>
                <div class="number-input-wrapper @error('capacity') is-invalid @enderror">
                    <span class="btn btn-link minus" onclick="changeValue(-1)">−</span>
                    <input type="number" id="number-input" min="1" wire:model="capacity" class="number-input" />
                    <span class="btn btn-link plus" onclick="changeValue(1)">+</span>
                </div>
                @error('capacity')
                <div class="mt-1 text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Capacity End -->
            <!-- Size -->
            <div class="mb-3 col-md-12">
                <label for="unitSize" class="form-label h3">
                    {{ __('How big is this room? (optional)') }}
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
            
            <!-- Features -->
            <div class="mb-3 col-md-12">
                <label for="unitFeatures" class="form-label h3">
                    {{ __('What can guests use in this room?') }}
                </label>
                <div class="row">
                    @foreach(current_company()->features as $feature)
                    <div class="gap-2 mb-2 cursor-pointer d-flex col-6">
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

            <!-- Price -->
            <div class="mb-3 col-md-12">
                <label for="unitPrice" class="form-label h3">
                    {{ __('How much do you want to charge per night?') }}
                </label>
                <div class="input-icon">
                    <span class="input-icon-addon font-weight-bolder">
                        {{ settings()->currency->symbol }}
                    </span>
                    <input type="number" class="form-control @error('unitPrice') is-invalid @enderror" id="unitPrice" wire:model="unitPrice" style="width: 200px;" value="{{ old('unitPrice') }}">
                </div>
                <span class="text-muted">{{ __('Including taxes and charges') }}</span>
                @error('unitPrice')
                <div class="mt-1 text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Price End -->

            <!-- Image Upload -->
            <div class="mb-3 col-md-12">
                <label for="unitSize" class="form-label h3">
                    {{ __('Show me some pictures room!') }}
                </label>
                <div class="mb-3 d-flex">
                    <div class="gap-2 k-gallery-box">
                        <span class="inline-flex bg-gray-200 border rounded k-image-box" onclick="document.getElementById('photo').click();">
                            <img src="{{ asset('assets/images/default/placeholder.png') }}" class="inline-flex rounded image">
                            <input type="file" wire:model.blur="photo" id="photo" style="display: none;" />
                        </span>
                    </div>
                </div>
                @error('unitSize')
                <div class="mt-1 text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Image Upload End -->
            
            <div class="mb-3 d-flex justify-content-between">
                <span>&nbsp;</span>
                <button class="gap-1 btn btn-primary go-next text-end" wire:click="addUnit">{{ __('Add Room') }} <i class="fas fa-plus-circle"></i></button>
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
                            <span class="text-end" wire:click="removeUnit({{ $index }})"><i class="fas fa-trash"></i></span>
                        </div>
                        <div class="mt-3 mb-3">
                            <p>
                                {{ $unit['unitDesc'] }}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>{{ $unit['numberUnits'] }} {{ __('Rooms') }}</span>
                            <span class="bottom-0 text-end">{{ format_currency($unit['price']) }} / Night</span>
                        </div>
                    </div>
                </div>
                @endforeach
                <!-- Unit Type End -->
            </div>
            
        </div>
    </div>
</div>
