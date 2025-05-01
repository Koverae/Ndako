@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">
            <h3 class="h2">
                {{ $this->availableRooms->count() }} Room(s) Available for:
            </h3>
            <span class="mb-3"><b>{{ $this->people }}</b> People on <b>{{ \Carbon\Carbon::parse($this->startDate) ->format('d M Y') }}</b> to <b>{{ \Carbon\Carbon::parse($this->endDate) ->format('d M Y') }}</b></span>
            <hr class="mt-3 mb-3">

            <div class="gap-1 mb-3 d-flex justify-content-between align-items-center">
                <select class="w-50 form-control" wire:model.live="filterBy" id="">
                    <option value="price">{{ __('Price') }}</option>
                    <option value="capacity">{{ __('Capacity') }}</option>
                    <option value="name">{{ __('Number') }}</option>
                </select>
                <select class="w-50 form-control" wire:model.live="sortOrder" id="">
                    <option value="asc">{{ __('Ascending') }}</option>
                    <option value="desc">{{ __('Descending') }}</option>
                </select>
                {{-- <span class="gap-2 text-end btn btn-primary">{{ __('Search') }} <i class="fas fa-search-plus"></i></span> --}}
            </div>

            <!-- Available Rooms -->
            <div class="row">
                <!-- Available Rooms Loop -->
                @foreach ($this->availableRooms as $room)
                    <div class="mb-3 col-12 col-md-12">
                        <div class="card @if($this->selectedRoom) {{ $this->selectedRoom->id == $room->id ? 'active-pick' : '' }} @endif">
                            <div class="card-body row">
                                <div class="col-12 col-lg-7">
                                    <span class="text-muted fw-bolder">{{ $room->capacity }} {{ __('People') }} <i class="fas fa-users"></i></span>
                                    <h5 class="mb-0 card-title">{{ $room->name }} ~ {{ $room->unitType->name }}</h5>
                                    <span class="mb-3 text-muted">{{ format_currency($this->rateService->getDefaultRate($room->unitType->id)->price) }} / {{ $this->rateService->getDefaultRate($room->unitType->id)->lease->name }}</span>
                                    <p class="mt-2">
                                        {{ $room->unitType->description }}
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-bed"></i> {{ $room->beds }} Beds <br>
                                        <i class="fas fa-bath"></i> {{ $room->bathrooms }} Bathrooms <br>
                                        <i class="fas fa-ruler-combined"></i> {{ $room->unitType->size }} sq ft
                                    </p>
                                    <button class="mt-3 btn w-100" wire:click="pickRoom('{{ $room->id }}')" {{ $this->startDate == '' && $this->endDate == '' ? 'disabled' : "" }}  @if($this->selectedRoom) {{ $this->selectedRoom->id == $room->id ? 'disabled' : '' }} @endif>{{ __('Choose') }}</button>
                                </div>
                                <div class="col-md-5 d-none d-lg-block">
                                    <img src="{{ $room->unitType->images
                                        ? asset('storage/' . $room->unitType->firstImage())
                                        : asset('storage/assets/images/default/placeholder.png') }}" width="300px" height="auto" alt="{{ $room->name }}" class="image">
                                        
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if ($this->availableRooms->count() >= $this->perPage)
                <button wire:click="loadMore"
                        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Load More
                </button>
                @endif
            </div>
            <!-- Available Rooms ENd -->
        </div>
    </div>
    @if($this->guest)
    <div class="shadow-sm col-12 col-md-3 card" style="max-height: 450px;">
        <div class="card-body">
            <img src="{{ $this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="{{ $this->guest->name }}" class="img img-fluid" height="350px" width="350px">
            <div class="mt-2">
                <span><i class="fas fa-user-md"></i> {{ $this->guest->name }}</span> <br>
                <span><i class="bi bi-envelope"></i> {{ $this->guest->email }}</span> <br>
                <span><i class="bi bi-phone"></i> {{ $this->guest->phone }}</span> <br>
                <span><i class="bi bi-geo"></i> {{ $this->guest->street }}</span> <br>
            </div>
        </div>
    </div>
    @endif
</div>
