@props([
    'value',
])
<div>
    <div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
        <!-- Booking Details -->
        <div class="border shadow-sm col-12 col-md-8 card">
            <div class="p-4 card-body">
                <!-- Room Details -->
                @if($this->selectedRoom)
                <div class="mb-3">
                    <h2 class="h2"><i class="fas fa-bed"></i> Room Details</h2>
                    <ul class="list-unstyled">
                        <li><strong>Room:</strong> {{ $this->selectedRoom->name }}</li>
                        <li><strong>Type:</strong> {{ $this->selectedRoom->unitType->name }}</li>
                        <li><strong>Capacity:</strong> {{ $this->selectedRoom->unitType->capacity }} guest(s)</li>
                        <li><strong>Price/Day:</strong> {{ format_currency($this->selectedRoom->unitType->price) }}</li>
                    </ul>
                </div>
                <hr>
                @endif
                <!-- Booking Period -->
                <div class="mt-2 mb-4">
                    <h2 class="h2"><i class="fas fa-calendar-alt"></i> Booking Period</h2>
                    <ul class="list-unstyled">
                        <li><strong>Check-In:</strong> {{ \Carbon\Carbon::parse($this->startDate)->format('d M Y') }}</li>
                        <li><strong>Check-Out:</strong> {{ \Carbon\Carbon::parse($this->endDate)->format('d M Y') }}</li>
                        <li><strong>Total Days:</strong> {{ dateDaysDifference($this->startDate, $this->endDate) }} Days</li>
                    </ul>
                </div>
                <hr>
                @php
                    $nights = dateDaysDifference($this->startDate, $this->endDate);
                @endphp
                <!-- Pricing Summary -->
                @if($this->selectedRoom)
                <div class="mt-2 mb-4">
                    <h2 class="h2"><i class="fas fa-money-check-alt"></i> Pricing Summary</h2>
                    <ul class="list-unstyled">
                        <li><strong>Total Price:</strong> {{ format_currency($this->totalAmount) }}</li>
                        <li><strong>Minimum Down Payment:</strong> {{ format_currency($this->downPayment) }}</li>
                    </ul>
                </div>
                <hr>
                @endif
                <!-- Payment Section -->
                @if($this->selectedRoom)
                <div class="mt-2">
                    <h2 class="h2"><i class="fas fa-credit-card"></i> Make a Payment</h2>
                    <div class="mb-2">
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-control @error('paymentMethod') is-invalid @enderror" id="paymentMethod" wire:model="paymentMethod" placeholder="Enter payment amount" value="{{ old('paymentMethod') }}">
                            <option value=""></option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="bank">{{ __('Bank') }}</option>
                            <option value="m-pesa">{{ __('M-Pesa') }}</option>
                        </select>
                        @error('paymentMethod')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="downPayment" class="form-label">Down Payment</label>
                        <input type="number" class="form-control @error('downPayment') is-invalid @enderror" id="downPayment" wire:model="downPayment" placeholder="Enter payment amount" value="{{ old('downPayment') }}">
                        @error('downPayment')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" wire:click='createBooking' class="btn btn-primary">Pay Down Payment</button>
                </div>
                @endif
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
</div>
