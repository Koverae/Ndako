@props(['value'])

@php
  use Carbon\Carbon;
  use Illuminate\Support\Str;

  $start        = $this->startDate ? Carbon::parse($this->startDate) : null;
  $end          = $this->endDate ? Carbon::parse($this->endDate) : null;
  $nights       = ($start && $end) ? max(0, $start->diffInDays($end)) : 0;

  $hasRoom      = (bool) $this->selectedRoom;
  $rate         = $hasRoom ? $this->rateService->getDefaultRate($this->selectedRoom->unitType->id) : null;
  $unitPrice    = $rate->price ?? 0;
  $leaseName    = $rate->lease->name ?? __('night');

  $total        = (float) ($this->totalAmount ?? 0);
  $minDue       = (float) ($this->downPaymentDue ?? 0);
  $paid         = (float) ($this->downPayment ?? 0);
  $balance      = max(0, $total - $paid);

  $pctPaid      = $total > 0 ? max(0, min(100, round(($paid / $total) * 100))) : 0;
  $pctMin       = $total > 0 ? max(0, min(100, round(($minDue / $total) * 100))) : 0;

  // Selected room image (robust)
  $placeholder  = asset('assets/images/default/placeholder.png');
  $imgSrc       = $placeholder;
  if ($hasRoom) {
      $ref = $this->selectedRoom->unitType->firstImage();
      if (!empty($ref)) {
          if (Str::startsWith($ref, ['http://','https://','//'])) {
              $imgSrc = $ref;
          } else {
              $norm = ltrim($ref, '/');
              if (Str::startsWith($norm, 'storage/')) {
                  $imgSrc = asset($norm);
              } else {
                  try { $imgSrc = Storage::disk('public')->url($norm); }
                  catch (\Throwable $e) { $imgSrc = Storage::url($norm); }
              }
          }
      }
  }
@endphp

<div class="{{ $this->currentStep == $value->step ? '' : 'd-none' }}">
  <div class="row g-4 justify-content-lg-center">
    {{-- Main column --}}
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm luxury-card">
        <div class="card-body p-3 p-md-4">

          {{-- Header summary --}}
          <div class="luxury-header p-3 p-md-4 rounded-3 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
              <div class="d-flex align-items-center gap-2">
                <span class="luxury-dot"></span>
                <h5 class="mb-0 fw-semibold">{{ __('Booking summary') }}</h5>
              </div>
              <div class="d-flex flex-wrap gap-2 text-muted small">
                @if($start && $end)
                  <span class="chip"><i class="bi bi-calendar-event me-1"></i>{{ $start->format('d M Y') }} → {{ $end->format('d M Y') }}</span>
                  <span class="chip"><i class="bi bi-moon-stars me-1"></i>{{ $nights }} {{ Str::plural(__('night'), $nights) }}</span>
                @endif
                <span class="chip"><i class="bi bi-people me-1"></i>{{ (int) $this->people }} {{ Str::plural(__('guest'), (int) $this->people) }}</span>
                @if($hasRoom)
                  <span class="chip"><i class="bi bi-door-open me-1"></i>{{ $this->selectedRoom->name }} · {{ $this->selectedRoom->unitType->name }}</span>
                @endif
              </div>
            </div>
          </div>

          {{-- Room details (if selected) --}}
          @if($hasRoom)
            <div class="row g-3 align-items-stretch mb-4">
              <div class="col-12 col-md-5">
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden luxury-media">
                  <img src="{{ $imgSrc }}" class="w-100 h-100 object-fit-cover" alt="{{ $this->selectedRoom->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $placeholder }}'">
                </div>
              </div>
              <div class="col-12 col-md-7">
                <div class="soft-card h-100 p-3 rounded-3">
                  <h6 class="mb-1 fw-semibold"><i class="fa fa-bed me-2"></i>{{ __('Room details') }}</h6>
                  <div class="text-muted small mb-2">{{ $this->selectedRoom->name }} · {{ $this->selectedRoom->unitType->name }}</div>
                  <div class="d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-people me-1"></i>{{ $this->selectedRoom->unitType->capacity }} {{ __('guests') }}</span>
                    <span class="chip soft"><i class="bi bi-cash-coin me-1"></i>{{ format_currency($unitPrice) }} / {{ $leaseName }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endif

          {{-- Booking period --}}
          <div class="soft-card p-3 p-md-4 rounded-3 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
              <i class="bi bi-calendar2-week text-primary"></i>
              <h6 class="mb-0 fw-semibold">{{ __('Booking period') }}</h6>
            </div>
            <div class="row g-3">
              <div class="col-6 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small">{{ __('Check-in') }}</div>
                  <div class="mini-value fw-semibold">{{ $start ? $start->format('d M Y') : '—' }}</div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small">{{ __('Check-out') }}</div>
                  <div class="mini-value fw-semibold">{{ $end ? $end->format('d M Y') : '—' }}</div>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small">{{ __('Total days') }}</div>
                  <div class="mini-value fw-semibold">{{ $nights }} {{ Str::plural(__('day'), $nights) }}</div>
                </div>
              </div>

              @if($this->startDate == now()->toDateString())
                <div class="col-12">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="checkInNow" wire:model="checkedIn">
                    <label for="checkInNow" class="form-check-label">{{ __('Will the guest check in after booking confirmation?') }}</label>
                  </div>
                </div>
              @endif
            </div>
          </div>

          {{-- Pricing summary (if room) --}}
          @if($hasRoom)
            <div class="soft-card p-3 p-md-4 rounded-3 mb-4">
              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-receipt text-primary"></i>
                <h6 class="mb-0 fw-semibold">{{ __('Pricing summary') }}</h6>
              </div>

              <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                  <div class="d-flex justify-content-between small text-muted">
                    <span>{{ __('Minimum down payment') }}</span>
                    <span>{{ format_currency($minDue) }}</span>
                  </div>
                  <div class="progress mt-1" style="height:6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $pctMin }}%;" aria-valuenow="{{ $pctMin }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="d-flex justify-content-between small text-muted">
                    <span>{{ __('Paid (entered)') }}</span>
                    <span>{{ format_currency($paid) }} ({{ $pctPaid }}%)</span>
                  </div>
                  <div class="progress mt-1" style="height:6px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $pctPaid }}%;" aria-valuenow="{{ $pctPaid }}" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between fw-semibold fs-6">
                    <span>{{ __('Total') }}</span>
                    <span>{{ format_currency($total) }}</span>
                  </div>
                  <div class="d-flex justify-content-between text-muted small">
                    <span>{{ __('Balance after payment') }}</span>
                    <span>{{ format_currency($balance) }}</span>
                  </div>
                </div>
              </div>
            </div>
          @endif

          {{-- Payment section (if room) --}}
          @if($hasRoom)
            <div class="soft-card p-3 p-md-4 rounded-3">
              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-credit-card-2-front text-primary"></i>
                <h6 class="mb-0 fw-semibold">{{ __('Make a payment') }}</h6>
              </div>

              @if (session()->has('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <span>{{ session('error') }}</span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
                </div>
              @endif

              <div class="row g-3">
                {{-- Method --}}
                <div class="col-12 col-md-6">
                  <label for="paymentMethod" class="form-label">{{ __('Payment method') }}</label>
                  <select
                    id="paymentMethod"
                    class="form-select @error('paymentMethod') is-invalid @enderror"
                    wire:model.live="paymentMethod"
                  >
                    <option value="">{{ __('Select…') }}</option>
                    <option value="cash">{{ __('Cash') }}</option>
                    <option value="bank">{{ __('Bank') }}</option>
                    <option value="m-pesa">{{ __('M-Pesa') }}</option>
                    @if (settings()->has_paystack)
                      <option value="paystack">{{ __('Paystack (Bank, Mobile Money, …)') }}</option>
                    @endif
                  </select>
                  @error('paymentMethod')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Transaction ID (only when relevant) --}}
                @if(in_array($this->paymentMethod, ['bank','m-pesa']))
                  <div class="col-12 col-md-6">
                    <label for="transactionId" class="form-label">{{ __('Transaction ID') }}</label>
                    <input
                      type="text"
                      id="transactionId"
                      class="form-control @error('transactionId') is-invalid @enderror"
                      placeholder="{{ __('Enter transaction ID') }}"
                      wire:model="transactionId"
                    >
                    @error('transactionId')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                @endif

                {{-- Amount --}}
                <div class="col-12">
                  <label for="downPayment" class="form-label">
                    {{ __('Payment amount') }}
                    <span class="text-muted">({{ __('Minimum') }}: {{ format_currency($minDue) }})</span>
                  </label>
                  <div class="input-group">
                    <input
                      type="number"
                      step="0.01"
                      min="0"
                      max="{{ $total }}"
                      id="downPayment"
                      class="form-control @error('downPayment') is-invalid @enderror"
                      placeholder="{{ __('Enter payment amount') }}"
                      wire:model.live.debounce.300ms="downPayment"
                    >
                    <span class="input-group-text">{{ __('of') }} {{ format_currency($total) }}</span>
                  </div>
                  @error('downPayment')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                  @if($paid > 0 && $paid < $minDue)
                    <div class="form-text text-warning">{{ __('Heads up: This is below the recommended minimum down payment.') }}</div>
                  @endif
                </div>

                {{-- Submit --}}
                <div class="col-12 d-grid d-sm-flex gap-2 justify-content-sm-end mt-2">
                  <button
                    type="button"
                    wire:click="createBooking"
                    wire:loading.attr="disabled"
                    class="btn btn-primary luxury-cta" style="background-color: #017E84; border-color: #017E84;"
                    @disabled(!$this->paymentMethod || $paid < 0 || $paid > $total)
                  >
                    <span wire:loading.remove wire:target="createBooking">
                      {{ $paid > 0 ? __('Pay & Confirm') : __('Confirm Booking') }}
                    </span>
                    <span wire:loading wire:target="createBooking">
                      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      {{ __('Processing…') }}
                    </span>
                  </button>
                </div>

              </div>
            </div>
          @endif

        </div>
      </div>
    </div>

    {{-- Guest preview --}}
    @if($this->guest)
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm luxury-side position-sticky" style="top:1rem;">
          <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-person-check text-primary"></i> {{ __('Guest') }}
            </h6>
            <span class="badge selected-badge">
              <i class="bi bi-check2-circle me-1"></i>{{ __('Selected') }}
            </span>
          </div>

          <div class="card-body">
            <div class="d-flex align-items-start gap-3">
              <div class="avatar-wrap">
                <img
                    src="{{ $this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}"
                    alt="{{ $this->guest->name }}"
                    class="rounded-2 object-fit-cover"
                    style="width:88px;height:88px;"
                    loading="lazy"
                >
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold text-truncate">{{ $this->guest->name }}</div>
                <div class="small text-muted text-truncate"><i class="bi bi-envelope me-1"></i>{{ $this->guest->email }}</div>
                <div class="small text-muted text-truncate"><i class="bi bi-phone me-1"></i>{{ $this->guest->phone }}</div>
                <div class="small text-muted text-truncate"><i class="bi bi-geo me-1"></i>{{ __('Qwetu Parklands') }}</div>
              </div>
            </div>
          </div>

          @php $activeCount = $this->guest->bookings()->isActive()->count(); @endphp
          <div class="card-footer bg-white border-0 d-flex align-items-center justify-content-between">
            @if($activeCount >= 1)
              <span class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">
                <i class="bi bi-activity me-1"></i>{{ __('Active') }}
              </span>
            @endif
            <a href="#" class="btn btn-outline-secondary btn-sm"
               onclick="event.preventDefault(); Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: {{ $this->guest->id }} })">
              <i class="bi bi-pencil-square me-1"></i>{{ __('Edit') }}
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>

  @script
  <script>
    $wire.on('openPaystackPopup', url => {
      const width = 600, height = 700;
      const left = (screen.width - width) / 2;
      const top  = (screen.height - height) / 2;
      window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);
    });
  </script>
  @endscript
</div>

{{-- Styles to elevate UI (cohesive with earlier steps) --}}
<style>
  :root{
    --luxury-grad: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .10), rgba(99,102,241,.08) 40%, rgba(16,185,129,.08));
    --luxury-border: rgba(0,0,0,.06);
    --luxury-glow: 0 0 0 .25rem rgba(var(--bs-primary-rgb), .15);
  }
  .luxury-card { border-radius: 1rem; }
  .luxury-header { background: var(--luxury-grad); border:1px solid var(--luxury-border); }
  .luxury-dot { width:.6rem;height:.6rem;border-radius:999px;background:var(--bs-primary); box-shadow:0 0 0 .35rem rgba(var(--bs-primary-rgb), .12); }

  .soft-card { background: rgba(255,255,255,.9); border:1px solid var(--luxury-border); }
  .chip { border:1px solid var(--luxury-border); background: rgba(255,255,255,.95); padding:.35rem .6rem; border-radius:999px; display:inline-flex; align-items:center; gap:.35rem; }
  .chip.soft { background: rgba(0,0,0,.03); }

  .luxury-media { background:#f6f7fb; }
  .selected-badge { background: rgba(var(--bs-primary-rgb), .12); color: var(--bs-primary); border:1px solid rgba(var(--bs-primary-rgb), .25); border-radius:999px; padding:.25rem .5rem; font-weight:600; }

  .luxury-cta { border-radius: .7rem; }
  .luxury-side { border-radius: 1rem; }
  .avatar-wrap { position:relative; }
  .avatar-wrap::after { content:''; position:absolute; inset:-4px; border-radius:1rem; border:1px dashed rgba(0,0,0,.05); pointer-events:none; }
</style>
