<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
</head>
<body>
    

                    <!-- Receipt -->
                    <div class="overflow-hidden text-center pos-receipt-container col-md-6 d-none d-md-flex flex-grow-1 flex-lg-grow-0 user-select-none justify-content-center bg-200">
                        <div class="p-3 m-3 overflow-y-auto bg-white border rounded receipt-block d-inline-block w-50 bg-view text-start">
                            <div class="p-2 pos-receipt">
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
                                    <div>Served by: {{ $order->cashier->name ?? 'Unknown' }}</div>
                                    <div class="receipt-number"><span class="fs-3">GHJKSSHSJJKJS</span></div>
                                </div>

                                <!-- Order list -->
                                <div class="overflow-y-auto mt-2 order-container-bg-view flex-grow-1 d-flex flex-column text-start">
                                    <ul>
                                        @if ($order)
                                            @forelse ($order->details as $item)
                                                <li class="p-2 cursor-pointer orderline lh-sm">
                                                    <div class="d-flex">
                                                        <div class="w-75 d-flex gap-2 pe-1 text-truncate">
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
                                                <div class="w-50 text-end fw-bold">{{ format_currency($order->total_amount ?? 0 + $cartTax) }}</div>
                                            </div>
                                        </li>
                                        <li class="p-2 cursor-pointer orderline lh-sm">
                                            <div class="d-flex">
                                                <div class="w-75 pe-1 text-truncate">{{ __('Payment') }}</div>
                                                <div class="w-50 text-end">{{ format_currency($order->total_amount ?? 0 + $cartTax) }}</div>
                                            </div>
                                            <ul>
                                                <!-- Placeholder for payment methods; extend as needed -->
                                                <li class="price-per-unit mt-1" style="padding-left: 3px;">Cash: {{ format_currency($order->total_amount ?? 0 + $cartTax) }}</li>
                                                <li class="price-per-unit mt-1" style="padding-left: 3px;">Card: {{ format_currency(0) }}</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Qr Code -->
                                <div class="pos-receipt-order-data d-flex mt-2 mb-2 text-center fs-5">
                                    <img src="{{ asset('assets/images/default/sample-qrcode.png') }}" style="height: 100px; width: 100px;" alt="" class="">

                                    <div class="d-block">
                                        <span class="fw-bolder">
                                            {{ __('Need an invoice?') }}
                                        </span>
                                        <p>Code: yhK2r</p>
                                    </div>
                                </div>

                                <!-- Order Meta -->
                                <div class="pos-receipt-order-data d-flex mt-2 text-center fs-5 flex-column align-items-center">
                                    <p>{{ __('Powered by ') }} <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
                                    <div>{{ \Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
</body>
</html>