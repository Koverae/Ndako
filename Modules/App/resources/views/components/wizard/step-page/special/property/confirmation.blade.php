@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
    <div class="m-2 d-flex justify-content-between">
        <div class="border shadow-sm col-12 col-md-8 card">
            <div class="p-4 card-body">
                {{-- <h1 class="h1">
                    {{ __('Ready to Finalize Everything?') }}
                </h1> --}}
                <!-- Property Summary -->
                <div class="mb-4 ">
                    <h2 class="h2"><i class="fas fa-hotel"></i> Property Summary</h2>
                    <ul class="list-unstyled fs-3">
                        <li><strong>Property Name:</strong> {{ $this->name }}</li>
                        <li><strong>Type:</strong> {{ $this->type->name }}</li>
                        <li class="{{ $this->street ? '' : 'd-none' }}"><strong>Location:</strong> {{ $this->street }}, {{ $this->city }}</li>
                        <li><strong>Number of Floors:</strong> {{ $this->floors }}</li>
                        <li>
                            <strong>Room Types:</strong>
                            <br>
                            @forelse($this->propertyUnits as $index => $unit)
                                <span class="ml-2">{{ $unit['unitName'] }}: {{ $unit['numberUnits'] }} Units ({{ format_currency($unit['price']) }} each)</span>
                            @empty
                                <span>{{ __('No room types...') }}</span>
                            @endforelse
                        </li>
                        <li><strong>Amenities:</strong> </li>
                    </ul>
                </div>
                <div class="bottom-0 d-flex justify-content-between">
                    <span class="fs-3">{{ __('Is everything okay? 👌🏾') }}</span>
                    <button type="submit" wire:click='confirm' class="btn btn-primary text-end">Confirm</button>
                </div>
                <!-- Property Summary End -->
            </div>
        </div>
        <div class="d-none d-lg-block col-lg-4">
            <img src="{{ asset('assets/images/illustrations/kwame-bot/kwame-6.svg') }}" alt="" class="img-fluid text-end">
        </div>
    </div>
</div>
