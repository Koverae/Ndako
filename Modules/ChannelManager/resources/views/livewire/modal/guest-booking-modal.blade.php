<div>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalToggleLabel">{{ __('Manage Booking') }}: #{{ $booking->reference }}</h5>
        <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
      </div>
      <div class="modal-body">
        
        @if (session()->has('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span>{{ session('message') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @error('paymentMethod')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="alert-body">
                <span>{{ session('message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @enderror

        <div class="k_form_nosheet">
            <div class="k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0">
                <!-- Action Bar -->
                <div id="action-bar" class="flex-wrap gap-1 k-statusbar-buttons d-lg-flex align-items-center align-content-around">

                    <button class="d-none d-lg-inline-flex rounded-0 {{ $booking->status == 'confirmed' ? 'btn btn-primary active' : '' }}" type="button" wire:click="" wire:target=""  id="top-button">
                        <span>
                            {{__('Send Invoice')}} <span wire:loading wire:target="" ></span>
                        </span>
                    </button>
                    @php
                        $hideClass = in_array($booking->status, ['canceled', 'completed']) ? 'd-none' : '';
                    @endphp
                    <button class=" rounded-0 {{ $hideClass }}" type="button" wire:click="cancelBooking" wire:target="cancelBooking"  id="top-button">
                        <span>
                            {{__('Cancel')}} <span wire:loading wire:target="cancelBooking"> ...</span>
                        </span>
                    </button>
                </div>

                <!-- Status Bar -->
                <div id="status-bar" class="gap-1 k-statusbar-buttons-arrow d-md-flex align-items-center align-content-around ">

                    <span class="btn-secondary-outline cursor-pointer k-arrow-button {{ $booking->status == 'pending' ? 'current' : '' }}">
                        {{ __('Pending') }}
                    </span>
                    <span class="btn-secondary-outline cursor-pointer k-arrow-button {{ $booking->status == 'confirmed' ? 'current' : '' }}">
                        {{ __('Confirmed') }}
                    </span>
                    <span class="btn-secondary-outline cursor-pointer k-arrow-button {{ $booking->status == 'completed' ? 'current' : '' }}">
                        {{ __('Completed') }}
                    </span>
                    <span class="btn-secondary-outline {{ $booking->status == 'canceled' ? '' : 'd-none' }} cursor-pointer k-arrow-button {{ $booking->status == 'canceled' ? 'current' : '' }}">
                        {{ __('Canceled') }}
                    </span>
                </div>
            </div>

            <div class="k_inner_group row">
                <div class="m-0 mt-3 mb-3 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <h2 class="h2"><i class="fas fa-user"></i> {{ __('Guest Details') }}</h2>
                        <ul class="list-unstyled row">
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Guest Name') }}:</strong> {{ $booking->guest->name }}</p>
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Guest(s)') }}:</strong> {{ $booking->guests }} @if($booking->guests > 1){{ __('people') }}@else {{ __('person') }} @endif</p>
                            @if($booking->due_amount >= 1 && $booking->status != 'canceled')
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Amount Paid') }}:</strong> {{ format_currency($booking->paid_amount) }}</p>
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Due Amount') }}:</strong> {{ format_currency($booking->due_amount) }}</p>
                            @endif
                            @if($booking->status == 'canceled')
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Refund Amount') }}:</strong> {{ format_currency($booking->refund_amount) }}</p>
                            @endif
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Total Amount') }}:</strong> {{ format_currency($booking->total_amount) }}</p>
                            <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Stay') }}:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }} ~ {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</p>
                        </ul>
                    </div>
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        @if($photo != null)
                        <img src="{{ $photo->temporaryUrl() }}" alt="image" class="img img-fluid">
                        @else
                        <img src="{{ $image_path ? Storage::url('avatars/' . $image_path) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="image" class="img img-fluid">
                        @endif
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                @if($booking->due_amount >= 1)
                <hr>
                <div class="mt-2 {{ $booking->status == 'canceled' ? 'd-none' : '' }}">
                    <h2 class="h2"><i class="fas fa-credit-card"></i> {{ __('Make a Payment') }}</h2>
                    <div class="mb-2">
                        <label for="paymentMethod" class="form-label">{{ __('Payment Method') }}</label>
                        <select class="form-control @error('paymentMethod') is-invalid @enderror" id="paymentMethod" wire:model="paymentMethod" placeholder="Enter payment amount" value="{{ old('paymentMethod') }}">
                            <option value=""></option>
                            <option value="cash">{{ __('Cash') }}</option>
                            <option value="bank">{{ __('Bank') }}</option>
                            <option value="m-pesa">{{ __('M-Pesa') }}</option>
                            @if (settings()->has_paystack)
                            <option value="paystack">{{ __('Paystack(Bank, Mobile Money)') }}</option>
                            @endif
                        </select>
                        @error('paymentMethod')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">{{ __('Payment Amount') }}</label>
                        <input type="number" class="form-control @error('paymentAmount') is-invalid @enderror" id="paymentAmount" wire:model="paymentAmount" placeholder="Enter payment amount" value="{{ old('paymentAmount') }}">
                        @error('paymentAmount')
                        <div class="mt-1 text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>&nbsp;</span>
                        <button type="submit" wire:click='addPayment' wire:loading.class="disabled" class="btn btn-primary text-end">Pay</button>
                    </div>
                </div>
                @endif

                <div class="mt-3 row {{ $booking->status == 'canceled' ? 'd-none' : '' }}">
                    <button wire:click="checkIn" class="gap-2 btn btn-primary rounded-0 col-6" {{ $booking->check_in_status == 'pending' ? '' : 'disabled' }}>
                        <i class="fas fa-sign-in-alt"></i> Check In
                    </button>
                    <button wire:click="checkOut" wire:confirm="Do you want to proceed check-out?" class="gap-2 btn btn-warning rounded-0 col-6" {{ $booking->check_in_status == 'checked_in' && $booking->check_out_status == 'pending' ? '' : 'disabled' }}>
                        <i class="fas fa-sign-out"></i> Check Out
                    </button>
                </div>


            </div>
        </div>
      </div>
      <div class="p-0 modal-footer">
        <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
      </div>
    </div>

    @script
    <script>
        $wire.on('openPaystackPopup', url => {
            let width = 600, height = 700;
            let left = (screen.width - width) / 2;
            let top = (screen.height - height) / 2;

            let paystackWindow = window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);

            // let interval = setInterval(() => {
            //     if (paystackWindow && paystackWindow.closed) {
            //         clearInterval(interval);
            //         $wire.dispatch('paymentCompleted', {reference: localStorage.getItem('paystack_payment_reference')});
            //     }
            // }, 1000);
        });
    </script>    
    @endscript

</div>
