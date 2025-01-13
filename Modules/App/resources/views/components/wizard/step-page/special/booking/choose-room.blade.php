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
                <select class="w-50 form-control" wire:model="filterBy" id="">
                    <option value="price">{{ __('Price') }}</option>
                    <option value="capacity">{{ __('Capacity') }}</option>
                    <option value="number">{{ __('Number') }}</option>
                </select>
                <select class="w-50 form-control" wire:model="sortOrder" id="">
                    <option value="asc">{{ __('Ascending') }}</option>
                    <option value="desc">{{ __('Descending') }}</option>
                </select>
                <span class="gap-2 text-end btn btn-primary">{{ __('Search') }} <i class="fas fa-search-plus"></i></span>
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
                                    <span class="mb-3 text-muted">{{ format_currency($room->unitType->price->price) }} / {{$room->unitType->price->lease->name ?? '' }}</span>
                                    <p class="mt-2">
                                        At sunt unde atque quod. Fuga atque iste ea ut nesciunt ut tenetur sed.
                                        Eligendi dolorem quas adipisci nisi distinctio est suscipit.
                                        Provident blanditiis laudantium voluptas eveniet.
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-bed"></i> {{ $room->beds }} Beds <br>
                                        <i class="fas fa-bath"></i> {{ $room->bathrooms }} Bathrooms <br>
                                        <i class="fas fa-ruler-combined"></i> {{ $room->area }} sq ft
                                    </p>
                                    <button class="mt-3 btn w-100" wire:click="pickRoom('{{ $room->id }}')" {{ $this->startDate == '' && $this->endDate == '' ? 'disabled' : "" }}  @if($this->selectedRoom) {{ $this->selectedRoom->id == $room->id ? 'disabled' : '' }} @endif>{{ __('Choose') }}</button>
                                </div>
                                <div class="col-md-5 d-none d-lg-block">
                                    <img src="{{ asset('assets/images/test/test-'. $room->id.'.jpg') }}" width="300px" height="auto" alt="{{ $room->name }}" class="image">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                <span><i class="bi bi-geo"></i> {{ __('Qwetu Parklands') }}</span> <br>
            </div>
        </div>
    </div>
    @endif
</div>
