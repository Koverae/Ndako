<div
  class="modal-content"
  x-data="{ copying:false }"
  role="dialog"
  aria-labelledby="manageBookingTitle"
  aria-modal="true"
>
  {{-- ───────────────────────────────────────── Styles (lightweight, scoped) ───────────────────────────────────────── --}}
  <style>
    .k-chip{ display:inline-flex; align-items:center; gap:.35rem; padding:.25rem .5rem; border-radius:999px; font-size:.8rem; line-height:1; }
    .k-chip--ok{ background:#E8F7EE; color:#166534; }
    .k-chip--warn{ background:#FEF3C7; color:#92400E; }
    .k-chip--muted{ background:#EEF2F7; color:#334155; }
    .k-stepper{ display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
    .k-step{ padding:.25rem .6rem; border-radius:999px; font-weight:600; font-size:.75rem; background:#F3F4F6; color:#6B7280; }
    .k-step.is-current{ background:#E0E7FF; color:#3730A3; }
    .k-step.is-done{ background:#DCFCE7; color:#166534; }
    .k-divider{ width:22px; height:2px; background:#E5E7EB }
    /* Skeletons */
    .sk{ position:relative; overflow:hidden; background:#eef2f7; border-radius:.5rem; }
    .sk:after{ content:""; position:absolute; inset:0; transform:translateX(-100%);
      background:linear-gradient(90deg, transparent, rgba(255,255,255,.65), transparent); animation:sk 1.2s infinite; }
    @keyframes sk{ to{ transform:translateX(100%)} }
    .sk-line{ height:12px; margin:.35rem 0 }
    /* Small focus ring */
    .k-focus:focus-visible{ box-shadow:0 0 0 .2rem rgba(99,102,241,.2); outline:0 }
    /* Tighten modal body spacing on small screens */
    @media (max-width: 576px){ .modal-body{ padding-top:.5rem } }
  </style>

  {{-- ───────────────────────────────────────── Header ───────────────────────────────────────── --}}
  <div class="modal-header align-items-start">
    <div class="d-flex flex-column">
      <h5 class="modal-title d-flex align-items-center gap-2" id="manageBookingTitle">
        {{ __('Manage Booking') }}:
        <span class="fw-bold">#{{ $booking->reference }}</span>
        <button
          type="button"
          class="btn btn-sm btn-outline-secondary k-focus"
          title="{{ __('Copy reference') }}"
          @click="
            copying=true;
            navigator.clipboard.writeText('{{ $booking->reference }}')
              .finally(()=>setTimeout(()=>copying=false,700))
          "
        >
          <i class="bi bi-clipboard"></i>
          <span x-show="!copying">{{ __('Copy') }}</span>
          <span x-show="copying">{{ __('Copied') }}</span>
        </button>
      </h5>

      {{-- Status chips --}}
      <div class="mt-2">
        @php
          $status = $booking->status;
          $isCanceled = $status === 'canceled';
          $isConfirmed = $status === 'confirmed';
          $isCompleted = $status === 'completed';
          $isPending = $status === 'pending';
        @endphp
        <div class="k-stepper" aria-label="{{ __('Booking status') }}">
          <span class="k-step {{ $isPending ? 'is-current' : (!$isCanceled && !$isPending ? 'is-done' : '') }}">{{ __('Pending') }}</span>
          <span class="k-divider"></span>
          <span class="k-step {{ $isConfirmed ? 'is-current' : ($isCompleted ? 'is-done' : '') }}">{{ __('Confirmed') }}</span>
          <span class="k-divider"></span>
          <span class="k-step {{ $isCompleted ? 'is-current' : '' }}">{{ __('Completed') }}</span>
          @if($isCanceled)
            <span class="k-divider"></span>
            <span class="k-step is-current">{{ __('Canceled') }}</span>
          @endif
        </div>
      </div>
    </div>

    <button type="button" class="btn-close" wire:click="$dispatch('closeModal')" aria-label="{{ __('Close') }}"></button>
  </div>

  {{-- ───────────────────────────────────────── Body ───────────────────────────────────────── --}}
  <div class="modal-body">

    {{-- Toast-like messages --}}
    @if (session()->has('message'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="alert-body d-flex align-items-start gap-2">
          <i class="bi bi-info-circle mt-1"></i>
          <span>{{ session('message') }}</span>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
      </div>
    @endif
    @if (session()->has('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="alert-body d-flex align-items-start gap-2">
          <i class="bi bi-check2-circle mt-1"></i>
          <span>{{ session('success') }}</span>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
      </div>
    @endif

    {{-- Field error (generic) --}}
    @error('paymentMethod')
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="alert-body d-flex align-items-start gap-2">
          <i class="bi bi-exclamation-triangle mt-1"></i>
          <span>{{ $message }}</span>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
        </div>
      </div>
    @enderror

    {{-- Top bars: actions + status --}}
    <div class="k_form_nosheet">
      <div class="k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0 flex-wrap gap-2">
        <!-- Action Bar -->
        <div id="action-bar" class="flex-wrap gap-2 k-statusbar-buttons d-lg-flex align-items-center align-content-around">
          {{-- Send invoice (only when confirmed or completed) --}}
          <button
            type="button"
            id="top-button"
            class="btn rounded-0 d-none d-lg-inline-flex {{ in_array($status,['confirmed','completed']) ? 'btn-primary' : 'btn-outline-secondary disabled' }}"
            title="{{ in_array($status,['confirmed','completed']) ? __('Send invoice to guest') : __('Available after confirmation') }}"
            wire:click="sendInvoice"
            wire:target="sendInvoice"
            wire:loading.attr="disabled"
          >
            <span class="d-inline-flex align-items-center gap-2">
              <i class="bi bi-send"></i> {{__('Send Invoice')}}
              <span wire:loading wire:target="sendInvoice" class="spinner-border spinner-border-sm"></span>
            </span>
          </button>

          {{-- Cancel (hide on canceled/completed) --}}
          @php $hideClass = in_array($status, ['canceled', 'completed']) ? 'd-none' : ''; @endphp
          <button
            type="button"
            id="top-button"
            class="btn btn-outline-danger rounded-0 {{ $hideClass }}"
            wire:click="cancelBooking"
            wire:target="cancelBooking"
            wire:confirm="{{ __("Are you sure you want to cancel this booking #$booking->reference?") }}"
            wire:loading.attr="disabled"
          >
            <span class="d-inline-flex align-items-center gap-2">
              <i class="bi bi-x-circle"></i> {{__('Cancel')}}
              <span wire:loading wire:target="cancelBooking" class="spinner-border spinner-border-sm"></span>
            </span>
          </button>
        </div>

        <!-- Quick financial chips -->
        <div class="d-flex align-items-center flex-wrap gap-2">
          <span class="k-chip k-chip--muted" title="{{ __('Total amount') }}">
            <i class="bi bi-receipt"></i> {{ format_currency($booking->total_amount) }}
          </span>
          @if($booking->due_amount >= 1 && $status != 'canceled')
            <span class="k-chip k-chip--ok" title="{{ __('Paid amount') }}">
              <i class="bi bi-check2"></i> {{ format_currency($booking->paid_amount) }}
            </span>
            <span class="k-chip k-chip--warn" title="{{ __('Due amount') }}">
              <i class="bi bi-exclamation-circle"></i> {{ format_currency($booking->due_amount) }}
            </span>
          @endif
          @if($status == 'canceled')
            <span class="k-chip k-chip--muted" title="{{ __('Refund amount') }}">
              <i class="bi bi-arrow-counterclockwise"></i> {{ format_currency($booking->refund_amount) }}
            </span>
          @endif
        </div>
      </div>

      {{-- Guest block --}}
      <div class="k_inner_group row">
        <div class="m-0 mt-3 mb-3 row justify-content-between position-relative w-100 g-3">
          <div class="ke-title mw-75 pe-2 ps-0 col-12 col-lg-9">
            <h2 class="h5 mb-3"><i class="fas fa-user"></i> {{ __('Guest Details') }}</h2>

            {{-- Skeleton for guest info while any action running --}}
            <div class="row" wire:loading.class="d-none" wire:target="sendInvoice,cancelBooking,addPayment,checkIn,checkOut">
              <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Guest Name') }}:</strong> {{ $booking->guest->name }}</p>
              <p class="mb-2 col-12 col-lg-6">
                <strong>{{ __('Guest(s)') }}:</strong>
                {{ $booking->guests }}
                @if($booking->guests > 1){{ __('people') }}@else {{ __('person') }} @endif
              </p>

              @if($booking->due_amount >= 1 && $status != 'canceled')
                <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Amount Paid') }}:</strong> {{ format_currency($booking->paid_amount) }}</p>
                <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Due Amount') }}:</strong> {{ format_currency($booking->due_amount) }}</p>
              @endif

              @if($status == 'canceled')
                <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Refund Amount') }}:</strong> {{ format_currency($booking->refund_amount) }}</p>
              @endif

              <p class="mb-2 col-12 col-lg-6"><strong>{{ __('Total Amount') }}:</strong> {{ format_currency($booking->total_amount) }}</p>
              <p class="mb-2 col-12 col-lg-6">
                <strong>{{ __('Stay') }}:</strong>
                {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                ~
                {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
              </p>
            </div>

            {{-- Guest info skeleton --}}
            <div class="row" wire:loading wire:target="sendInvoice,cancelBooking,addPayment,checkIn,checkOut">
              <div class="col-12 col-lg-6">
                <div class="sk sk-line" style="width:70%"></div>
                <div class="sk sk-line" style="width:50%"></div>
              </div>
              <div class="col-12 col-lg-6">
                <div class="sk sk-line" style="width:60%"></div>
                <div class="sk sk-line" style="width:40%"></div>
              </div>
            </div>
          </div>

          {{-- Avatar --}}
          <div class="p-0 m-0 k_employee_avatar col-12 col-lg-3 d-flex justify-content-lg-end">
            <div class="position-relative" style="width:96px;height:96px;">
              <div class="rounded overflow-hidden" style="width:96px;height:96px;">
                @if($photo != null)
                  <img src="{{ $photo->temporaryUrl() }}" alt="{{ __('Guest image') }}" class="img img-fluid w-100 h-100 object-fit-cover">
                @else
                  <img src="{{ $image_path ? Storage::url('avatars/' . $image_path) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="{{ __('Guest image') }}" class="img img-fluid w-100 h-100 object-fit-cover">
                @endif
              </div>
            </div>
            @error('photo') <span class="error small text-danger ms-2">{{ $message }}</span> @enderror
          </div>
        </div>

        {{-- Payment --}}
        @if($booking->due_amount >= 1)
          <hr>
          <div class="mt-2 {{ $status == 'canceled' ? 'd-none' : '' }}">
            <h2 class="h5 mb-3"><i class="fas fa-credit-card"></i> {{ __('Make a Payment') }}</h2>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="paymentMethod" class="form-label">{{ __('Payment Method') }}</label>
                <select
                  id="paymentMethod"
                  class="form-control @error('paymentMethod') is-invalid @enderror"
                  wire:model="paymentMethod"
                >
                  <option value="">{{ __('Select method') }}</option>
                  <option value="cash">{{ __('Cash') }}</option>
                  <option value="bank">{{ __('Bank') }}</option>
                  <option value="m-pesa">{{ __('M-Pesa') }}</option>
                  @if (settings()->has_paystack)
                    <option value="paystack">{{ __('Paystack (Bank / Mobile Money)') }}</option>
                  @endif
                </select>
                @error('paymentMethod')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6">
                <label for="paymentAmount" class="form-label d-flex justify-content-between">
                  <span>{{ __('Payment Amount') }}</span>
                  <span class="text-muted small">{{ __('Due') }}: {{ format_currency($booking->due_amount) }}</span>
                </label>
                <div class="input-group">
                  <input
                    type="number"
                    step="0.01"
                    min="0"
                    class="form-control @error('paymentAmount') is-invalid @enderror"
                    id="paymentAmount"
                    wire:model.lazy="paymentAmount"
                    wire:keydown.enter="addPayment"
                    placeholder="0.00"
                    aria-describedby="quickAmounts"
                  >
                  <button
                    class="btn btn-outline-secondary"
                    type="button"
                    id="quickAmounts"
                    title="{{ __('Fill exact due') }}"
                    wire:click="$set('paymentAmount', {{ $booking->due_amount }})"
                  >
                    {{ __('Exact') }}
                  </button>
                </div>
                @error('paymentAmount')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                {{-- Quick chips --}}
                <div class="mt-2 d-flex gap-2 flex-wrap">
                  @php
                    $due = max(0, $booking->due_amount);
                    $q25 = round($due * .25, 2);
                    $q50 = round($due * .50, 2);
                    $q75 = round($due * .75, 2);
                  @endphp
                  <button type="button" class="btn btn-sm btn-light" wire:click="$set('paymentAmount', {{ $q25 }})">25%</button>
                  <button type="button" class="btn btn-sm btn-light" wire:click="$set('paymentAmount', {{ $q50 }})">50%</button>
                  <button type="button" class="btn btn-sm btn-light" wire:click="$set('paymentAmount', {{ $q75 }})">75%</button>
                  <button type="button" class="btn btn-sm btn-light" wire:click="$set('paymentAmount', {{ $due }})">{{ __('100%') }}</button>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
              <button
                type="button"
                class="btn btn-primary d-inline-flex align-items-center gap-2"
                wire:click='addPayment'
                wire:loading.attr="disabled"
                wire:target="addPayment"
              >
                <span class="spinner-border spinner-border-sm" wire:loading wire:target="addPayment"></span>
                <span>{{ __('Pay') }}</span>
              </button>
            </div>
          </div>
        @endif

        {{-- Check-in / Check-out --}}
        <div class="mt-4 row {{ $status == 'canceled' ? 'd-none' : '' }}">
          <div class="col-6 d-grid">
            <button
              wire:click="checkIn"
              class="btn btn-primary rounded-0 d-inline-flex align-items-center justify-content-center gap-2"
              {{ $booking->check_in_status == 'pending' ? '' : 'disabled' }}
              title="{{ $booking->check_in_status == 'pending' ? __('Mark guest as checked-in') : __('Already checked-in') }}"
              wire:loading.attr="disabled"
              wire:target="checkIn"
            >
              <span class="spinner-border spinner-border-sm" wire:loading wire:target="checkIn"></span>
              <i class="fas fa-sign-in-alt"></i> <span>{{ __('Check In') }}</span>
            </button>
          </div>

          <div class="col-6 d-grid">
            <button
              wire:click="checkOut"
              wire:confirm="{{ __('Do you want to proceed check-out?') }}"
              class="btn btn-warning rounded-0 d-inline-flex align-items-center justify-content-center gap-2"
              {{ ($booking->check_in_status == 'checked_in' && $booking->check_out_status == 'pending') ? '' : 'disabled' }}
              title="{{ ($booking->check_in_status == 'checked_in' && $booking->check_out_status == 'pending') ? __('Mark guest as checked-out') : __('Check-in required or already checked-out') }}"
              wire:loading.attr="disabled"
              wire:target="checkOut"
            >
              <span class="spinner-border spinner-border-sm" wire:loading wire:target="checkOut"></span>
              <i class="fas fa-sign-out-alt"></i> <span>{{ __('Check Out') }}</span>
            </button>
          </div>
        </div>

      </div> {{-- /k_inner_group --}}
    </div> {{-- /k_form_nosheet --}}
  </div>

  {{-- ───────────────────────────────────────── Footer ───────────────────────────────────────── --}}
  <div class="modal-footer justify-content-between">
    <small class="text-muted">
      <i class="bi bi-clock-history"></i>
      {{ __('Created') }}: {{ optional($booking->created_at)->format('d M Y, H:i') }}
    </small>
    <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Close') }}</button>
  </div>

  {{-- ───────────────────────────────────────── Scripts ───────────────────────────────────────── --}}
  @script
  <script>
    // Paystack popup helper
    $wire.on('openPaystackPopup', url => {
      const width = 600, height = 700;
      const left = (screen.width - width) / 2;
      const top = (screen.height - height) / 2;
      window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);
    });
  </script>
  @endscript
</div>
