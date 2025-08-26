<div>
  <div class="modal-content">
    {{-- Header --}}
        <div class="modal-header">
            <h5 class="modal-title">{{ __("Make Payment for") }} #{{ $order->receipt_number }}</h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>

    <div class="modal-body pt-0">
      {{-- Card-style Tabs (responsive) --}}
      <div class="row g-2 g-md-3 my-3">
        <div class="col-6">
          <div class="card h-100 cursor-pointer {{ $tab === 'offline' ? 'border-primary shadow-sm' : 'border-light' }}"
               role="tab" aria-selected="{{ $tab === 'offline' ? 'true' : 'false' }}"
               wire:click="$set('tab','offline')">
            <div class="card-body p-3 text-center">
              <i class="bi bi-cash-coin fs-2 mb-1 text-primary"></i>
              <div class="fw-semibold">{{ __('Offline') }}</div>
              <small class="text-muted">{{ __('Cash, Card, M-Pesa (offline)') }}</small>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card h-100 cursor-pointer {{ $tab === 'online' ? 'border-success shadow-sm' : 'border-light' }}"
               role="tab" aria-selected="{{ $tab === 'online' ? 'true' : 'false' }}"
               wire:click="$set('tab','online')">
            <div class="card-body p-3 text-center">
              <i class="bi bi-credit-card-2-front fs-2 mb-1 text-success"></i>
              <div class="fw-semibold">{{ __('Online') }}</div>
              <small class="text-muted">{{ __('Paystack, M-Pesa (STK)') }}</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Alerts --}}
      <div aria-live="polite" aria-atomic="true">
        @if (session()->has('error'))
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
          </div>
        @endif
        @if (session()->has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
          </div>
        @endif
      </div>

      {{-- ================= OFFLINE ================= --}}
      @if($tab === 'offline')
        <div class="mb-3">
          <label class="form-label">{{ __('Payment Method') }}</label>
          <div class="row g-2">
            <div class="col-4">
              <button type="button"
                      class="btn w-100 {{ $offlineMethod === 'cash' ? 'btn-outline-primary active' : 'btn-outline-secondary' }}"
                      wire:click="$set('offlineMethod','cash')">
                <i class="bi bi-cash-stack me-1"></i>{{ __('Cash') }}
              </button>
            </div>
            <div class="col-4">
              <button type="button"
                      class="btn w-100 {{ $offlineMethod === 'card' ? 'btn-outline-primary active' : 'btn-outline-secondary' }}"
                      wire:click="$set('offlineMethod','card')">
                <i class="bi bi-credit-card me-1"></i>{{ __('Card') }}
              </button>
            </div>
            <div class="col-4">
              <button type="button"
                      class="btn w-100 {{ $offlineMethod === 'mpesa' ? 'btn-outline-primary active' : 'btn-outline-secondary' }}"
                      wire:click="$set('offlineMethod','mpesa')">
                <img src="{{ asset('assets/images/third-icons/mpesa.svg') }}" alt="" style="height:16px" class="me-1">
                {{ __('M-Pesa') }}
              </button>
            </div>
          </div>
        </div>

        {{-- Amount --}}
        <div class="mb-3">
          <label class="form-label">
            {{ __('Amount') }}
            <small class="text-muted">({{ __('To pay') }}: {{ format_currency($order->due_amount) }})</small>
          </label>
          <div class="input-group">
            <span class="input-group-text">{{ settings()->currency->symbol }}</span>
            <input type="number" step="0.01" min="0" inputmode="decimal"
                   class="form-control"
                   wire:model.live.debounce.300ms="amount"
                   placeholder="0.00"
                   aria-describedby="amount-help">
          </div>
          <div id="amount-help" class="form-text">
            {{ __('Enter the amount received / to charge.') }}
          </div>

          {{-- Quick-fill chips (mobile friendly) --}}
          <div class="d-flex flex-wrap gap-2 mt-2">
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', {{ $order->due_amount }})">
              {{ __('Exact') }}
            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', {{ max(0, ceil(($order->due_amount ?? 0)/50)*50) }})">
              {{ __('Round up') }}
            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', {{ ($order->due_amount ?? 0) + 100 }})">
              +100
            </button>
          </div>
        </div>

        {{-- Cash-specific: Change due --}}
        @if($offlineMethod === 'cash')
          @php
            $change = max(0, (float)($amount ?? 0) - (float)($order->due_amount ?? 0));
          @endphp
          <div class="mb-3">
            <div class="alert {{ $change > 0 ? 'alert-success' : 'alert-secondary' }} py-2 mb-0">
              <div class="d-flex justify-content-between">
                <span class="fw-semibold">{{ __('Change due') }}</span>
                <span class="fw-bold">{{ format_currency($change) }}</span>
              </div>
            </div>
          </div>
        @endif

        {{-- Card-specific: Reference --}}
        @if($offlineMethod === 'card')
          <div class="mb-3">
            <label class="form-label">{{ __('POS Ref / Last 4 digits') }}</label>
            <input type="text" class="form-control" wire:model.lazy="reference" placeholder="e.g. AUTH123, **** 1234">
          </div>
        @endif

        {{-- M-Pesa (offline) specific: Code + Phone --}}
        @if($offlineMethod === 'mpesa')
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <label class="form-label">{{ __('M-Pesa Code') }}</label>
              <input type="text" class="form-control" wire:model.lazy="reference" placeholder="e.g. QJK1XY234">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label">{{ __('Phone (optional)') }}</label>
              <input type="tel" class="form-control" wire:model.lazy="msisdn" placeholder="+2547XXXXXXXX">
            </div>
          </div>
          <small class="text-muted d-block mt-1">
            {{ __('Use this when customer paid on a separate till/phone and shows you the confirmation code.') }}
          </small>
        @endif

        {{-- Submit --}}
        <div class="d-grid mt-3">
          <button class="btn btn-primary"
                  wire:click="processOfflinePayment"
                  wire:loading.attr="disabled"
                  wire:target="processOfflinePayment"
                  @keydown.enter.prevent="null">
            <span wire:loading wire:target="processOfflinePayment" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            {{ __('Confirm Payment') }}
          </button>
        </div>
      @endif

      {{-- ================= ONLINE ================= --}}
      @if($tab === 'online')
        {{-- Provider selector (small cards) --}}
        <div class="row g-2 g-md-3 mb-3">
          <div class="col-6">
            <button type="button"
                    class="card btn p-0 text-start {{ ($onlineProvider ?? 'paystack') === 'paystack' ? 'border-success shadow-sm' : 'border-light' }}"
                    wire:click="$set('onlineProvider','paystack')">
              <div class="card-body d-flex align-items-center gap-2">
                <img src="{{ asset('assets/images/third-icons/paystack.png') }}" alt="Paystack" style="height:20px">
                <div>
                  <div class="fw-semibold">{{ __('Paystack') }}</div>
                  <small class="text-muted">{{ __('Card • Bank • Mobile') }}</small>
                </div>
              </div>
            </button>
          </div>
          <div class="col-6">
            <button type="button"
                    class="card btn p-0 text-start {{ ($onlineProvider ?? 'paystack') === 'mpesa' ? 'border-success shadow-sm' : 'border-light' }}"
                    wire:click="$set('onlineProvider','mpesa')">
              <div class="card-body d-flex align-items-center gap-2">
                <img src="{{ asset('assets/images/third-icons/mpesa.svg') }}" alt="M-Pesa" style="height:20px">
                <div>
                  <div class="fw-semibold">{{ __('M-Pesa STK') }}</div>
                  <small class="text-muted">{{ __('Push to phone') }}</small>
                </div>
              </div>
            </button>
          </div>
        </div>

        {{-- Online amount (prefilled with due) --}}
        <div class="mb-3">
          <label class="form-label">
            {{ __('Amount') }}
            <small class="text-muted">({{ __('To pay') }}: {{ format_currency($order->due_amount) }})</small>
          </label>
          <div class="input-group">
            <span class="input-group-text">{{ settings()->currency->symbol }}</span>
            <input type="number" step="0.01" min="0" inputmode="decimal"
                   class="form-control"
                   wire:model.live.debounce.300ms="onlineAmount"
                   placeholder="{{ number_format((float)($order->due_amount ?? 0), 2) }}">
          </div>
          <div class="d-flex flex-wrap gap-2 mt-2">
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('onlineAmount', {{ $order->due_amount ?? 0 }})">
              {{ __('Exact') }}
            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('onlineAmount', {{ max(0, ceil(($order->due_amount ?? 0)/50)*50) }})">
              {{ __('Round up') }}
            </button>
          </div>
        </div>

        {{-- Provider-specific fields & actions --}}
        @if(($onlineProvider ?? 'paystack') === 'paystack')
          <div class="text-center">
            <p class="text-muted mb-3">{{ __('Click below to pay securely with Paystack') }}</p>
            <button class="btn btn-primary w-100"
                    wire:click="initiatePaystack"
                    wire:loading.attr="disabled"
                    wire:target="initiatePaystack">
              <span class="d-flex align-items-center justify-content-center gap-2" wire:loading.remove wire:target="initiatePaystack">
                <img src="{{ asset('assets/images/third-icons/paystack.png') }}" style="height:20px" alt="">
                <span>{{ __('Pay with Paystack') }}</span>
              </span>
              <span wire:loading wire:target="initiatePaystack">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                {{ __('Processing...') }}
              </span>
            </button>
          </div>
        @endif

        @if(($onlineProvider ?? 'paystack') === 'mpesa')
          <div class="mb-3">
            <label class="form-label">{{ __('Customer Phone (M-Pesa)') }}</label>
            <input type="tel" class="form-control" wire:model.lazy="msisdn" placeholder="+2547XXXXXXXX">
            <small class="text-muted">{{ __('The customer will receive an STK prompt to enter their PIN.') }}</small>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-success"
                    wire:click="initiateMpesaStk"
                    wire:loading.attr="disabled"
                    wire:target="initiateMpesaStk">
              <span wire:loading wire:target="initiateMpesaStk" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ __('Send STK Push') }}
            </button>

            {{-- Optional: manual status check if you expose it --}}
            <button class="btn btn-outline-secondary"
                    wire:click="pollMpesaStatus"
                    wire:loading.attr="disabled"
                    wire:target="pollMpesaStatus,initiateMpesaStk">
              <span wire:loading wire:target="pollMpesaStatus" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              {{ __('Check Status') }}
            </button>
          </div>
        @endif
      @endif
    </div>

    <div class="modal-footer flex-wrap">
      <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
    </div>
  </div>

  {{-- JS hooks (Paystack popup) --}}
  @script
  <script>
    // Paystack popup callback (unchanged but made resilient)
    $wire.on('openPaystackPopup', url => {
      const width = 600, height = 700;
      const left = (screen.width - width) / 2;
      const top = (screen.height - height) / 2;

      const win = window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);
      const iv = setInterval(() => {
        if (!win || win.closed) {
          clearInterval(iv);
          // You can also emit a Livewire event here if your backend expects it:
          // $wire.dispatch('poPaymentCompleted', { reference: localStorage.getItem('paystack_payment_reference') });
          $wire.dispatch('poPaymentCompleted');
        }
      }, 1000);
    });
  </script>
  @endscript
</div>
