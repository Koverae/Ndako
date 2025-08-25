
  <div class="payment-container d-print-none bg-white {{ $interface == 'payment' ? '' : 'd-none' }} h-screen-d">
    <div class="payment-confirmed">
      <div class="row">
        <div class="top-content d-print-none">
          <h1>{{ format_currency($order->total_amount ?? 0) }}</h1>
        </div>

        <!-- Actions -->
        <div class="col-md-6 d-print-none">
          <div class="actions justify-content-between flex-lg-grow-1">
            <div class="p-3 m-1 mt-2 mb-3 rounded payment-success-card d-flex flex-column align-items-center g-3 border-success bg-success-subtle text-success fs-3">
              <i class="mb-2 bi bi-check-circle" style="font-size: 35px;" aria-hidden="true"></i>
              <span style="font-weight: 900;" class="fs-2 ">{{ __('Payment Successful') }}</span>
              <div class="gap-2 mt-2 d-flex justify-content-center align-items-center fw-bolder">
                <span>{{ format_currency($order->total_amount ?? 0) }}</span>
                <span class="pt-1 text-white rounded cursor-pointer edit-order-payment badge bg-success">
                  {{ __('Edit Payment') }}
                </span>
              </div>
            </div>

            <button class="gap-2 py-5 m-1 button btn btn-print btn-lg w-100" onclick="window.print();">
              <i class="mr-1 bi bi-printer fw-bold" aria-hidden="true"></i>
              <span>{{ __('Print Full Receipt') }}</span>
            </button>

            <div class="gap-1 mt-3 validation_buttons d-print-none d-none d-lg-flex w-100">
              <a wire:click="newOrder" class="p-3 m-1 text-center text-white rounded cursor-pointer btn-switch_pane btn-primary fw-bolder review-button w-50 text-decoration-none">
                <span class="fs-1 d-block">{{ __('New Order') }}</span>
              </a>
              <button wire:click="switchInterface('orders')" class="p-3 m-1 text-white rounded btn-switch_pane btn-primary fw-bolder review-button w-50">
                <span class="mb-1 fs-1 d-block">{{ __('Orders') }}</span>
              </button>
            </div>

            <!-- Mobile View -->
            <div class="gap-1 mt-3 validation_buttons d-print-none d-flex d-lg-none fixed-bottom w-100">
              <a wire:click="newOrder" class="p-3 m-1 text-center text-white rounded cursor-pointer btn-switch_pane btn-primary fw-bolder review-button w-50 text-decoration-none">
                <span class="fs-1 d-block">{{ __('New Order') }}</span>
              </a>
              <button wire:click="switchInterface('orders')" class="p-3 m-1 text-white rounded btn-switch_pane btn-primary fw-bolder review-button w-50">
                <span class="mb-1 fs-1 d-block">{{ __('Orders') }}</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Receipt (preview) -->
        <div class="overflow-hidden text-center pos-receipt-container col-md-6 d-none d-md-flex flex-grow-1 flex-lg-grow-0 user-select-none justify-content-center bg-200">
          <div class="p-3 m-3 overflow-y-auto bg-white border rounded receipt-block d-inline-block w-50 bg-view text-start">
            <div class="p-2 pos-receipt">
              <!-- Logo -->
              <div class="d-flex flex-column justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" class="pos-receipt-logo" loading="lazy" decoding="async">
              </div>

              <!-- Company Info -->
              <div class="d-flex flex-column align-items-center company-info">
                <span>{{ current_company()->address }}</span>
                @if(current_company()->phone)
                  <span>Tel: {{ current_company()->phone }}</span>
                @endif
                <div>-------------------------</div>
                <div>{{ __('Guest') }}: {{ $order->guest->name ?? 'Unknown' }}</div>
                <div>{{ __('Served by') }}: {{ $order->cashier->name ?? 'Unknown' }}</div>
                <div class="receipt-number"><span class="fs-3">GHJKSSHSJJKJS</span></div>
              </div>

              <!-- Order list -->
              <div class="mt-2 overflow-y-auto order-container-bg-view flex-grow-1 d-flex flex-column text-start">
                <ul>
                  @if ($order)
                    @forelse ($order->details as $item)
                      <li class="p-2 cursor-pointer orderline lh-sm">
                        <div class="d-flex">
                          <div class="gap-2 w-75 d-flex pe-1 text-truncate">
                            <span class="qty fw-bolder">{{ $item->quantity }}</span>
                            <span class="name">{{ $item->product->product_name ?? 'Unknown' }}</span>
                          </div>
                          <div class="product-price w-50 text-end">
                            {{ format_currency(($item->unit_price * $item->quantity) * (1 - $item->product_discount_amount / 100)) }}
                          </div>
                        </div>
                      </li>
                    @empty
                      <li class="p-2 text-muted">{{ __('No items in order.') }}</li>
                    @endforelse
                  @else
                    <li class="p-2 text-muted">{{ __('No active order.') }}</li>
                  @endif
                </ul>
              </div>

              <!-- Separator -->
              <div class="align-items-center">---------------------------</div>

              <!-- Totals -->
              <div class="overflow-y-auto order-container-bg-view flex-grow-1 d-flex flex-column text-start">
                <ul>
                  <li class="p-2 cursor-pointer orderline lh-sm">
                    <div class="d-flex">
                      <div class="w-75 pe-1 text-truncate">{{ __('Subtotal') }}</div>
                      <div class="w-50 text-end">{{ format_currency($order->total_amount ?? 0) }}</div>
                    </div>
                  </li>
                  <li class="p-2 cursor-pointer orderline lh-sm">
                    <div class="d-flex">
                      <div class="w-75 pe-1 text-truncate">{{ __('VAT') }} {{ config('pos.tax_rate', 0.16) * 100 }}%</div>
                      <div class="w-50 text-end">{{ format_currency($cartTax) }}</div>
                    </div>
                  </li>
                  <li class="p-2 cursor-pointer orderline lh-sm">
                    <div class="d-flex">
                      <div class="w-75 pe-1 text-truncate fw-bold">{{ __('Total') }}</div>
                      <div class="w-50 text-end fw-bold">{{ format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0)) }}</div>
                    </div>
                  </li>
                  <li class="p-2 cursor-pointer orderline lh-sm">
                    <div class="d-flex">
                      <div class="w-75 pe-1 text-truncate">{{ __('Payment') }}</div>
                      <div class="w-50 text-end">{{ format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0)) }}</div>
                    </div>
                    <ul>
                      <li class="mt-1 price-per-unit" style="padding-left: 3px;">{{ __('Cash') }}: {{ format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0)) }}</li>
                      <li class="mt-1 price-per-unit" style="padding-left: 3px;">{{ __('Card') }}: {{ format_currency(0) }}</li>
                    </ul>
                  </li>
                </ul>
              </div>

              <!-- QR + meta -->
              <div class="mt-2 mb-2 text-center pos-receipt-order-data d-flex fs-5">
                {!! QrCode::size(100)->generate('https://ndako.koverae.com') !!}
                <div class="d-block ms-2 text-start">
                  <span class="fw-bolder">{{ __('Need an invoice?') }}</span>
                  <p>Code: {{ $order->receipt_number ?? 'N/A' }}</p>
                </div>
              </div>

              <div class="mt-2 text-center pos-receipt-order-data d-flex fs-5 flex-column align-items-center">
                <p>{{ __('Powered by ') }} <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
                <div>{{ \Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i') }}</div>
              </div>
            </div>
          </div>
        </div>

      </div> <!-- row -->
    </div>
  </div>