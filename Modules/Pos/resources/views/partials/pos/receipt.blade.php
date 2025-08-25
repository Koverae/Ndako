
  @if($order)
    <div class=" p-2 pos-receipt d-none d-print-block {{$this->toPrint == 'receipt' ? '' : 'd-print-none'}}">
      <!-- Logo -->
      <div class="d-flex flex-column justify-content-center align-items-center">
        <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" class="pos-receipt-logo">
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
        <div class="receipt-number"><span class="fs-3">{{ $order->receipt_number ?? 'N/A' }}</span></div>
      </div>

      <!-- Order list -->
      <div class="mt-2 order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
      <div class="overflow-y-auto order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
    <!-- Bill -->
    <div class=" p-2 pos-receipt d-none d-print-block {{$this->toPrint == 'bill' ? '' : 'd-print-none'}}">
      <!-- Logo -->
      <div class="d-flex flex-column justify-content-center align-items-center">
        <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" class="pos-receipt-logo">
      </div>

      <!-- Company Info -->
      <div class="d-flex flex-column align-items-center company-info">
        <span>{{ current_company()->address ?? 'Moi Avenue' }}</span>
        @if(current_company()->phone)
          <span>Tel: {{ current_company()->phone }}</span>
        @endif
        <div>-------------------------</div>
        <div>{{ __('Served by') }}: {{ $order->cashier->name ?? 'Unknown' }}</div>
        <div class="receipt-number">{{ __('M-Pesa Till') }}: <span class="fs-3">987654</span></div>
      </div>

      <!-- Order list -->
      <div class="mt-2 order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
      <div class="overflow-y-auto order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
        </ul>
      </div>

      <div class="mt-2 text-center pos-receipt-order-data d-flex fs-5 flex-column align-items-center">
        <p>{{ __('Powered by ') }} <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
        <div>{{ \Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i') }}</div>
      </div>
    </div>
  @endif