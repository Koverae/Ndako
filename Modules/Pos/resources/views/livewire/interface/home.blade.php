
    @section('title', $pos->name)
    @section('styles')
    <style>
        /* Custom animations */

    </style>
    @endsection

    <main class="relative main" x-data="{ isLocked: @entangle('isLocked'), timer: null }" x-init="
    // Initialize inactivity timer
    let lastActivity = Date.now();
    const TIMEOUT = 20 * 60 * 1000; // 20 minutes in milliseconds

    const resetTimer = () => {
        lastActivity = Date.now();
        isLocked = false;
    };

    const checkInactivity = () => {
        if (Date.now() - lastActivity > TIMEOUT) {
            isLocked = true;
            $wire.set('isLocked', true);
        }
    };

    // Event listeners for activity
    ['mousemove', 'mousedown', 'keypress', 'touchstart'].forEach(event =>
        document.addEventListener(event, resetTimer)
    );

    // Start checking for inactivity
    timer = setInterval(checkInactivity, 1000);

    // Listen for reset event from Livewire
    window.Livewire.on('reset-inactivity-timer', resetTimer);
">
        <!-- Lock Screen -->
        <div x-show="isLocked" style="z-index: 99999;" class="fixed inset-0 flex items-center justify-center bg-opacity-75 bg-body-secondary backdrop-blur animate-fade-in">
            <div class="relative flex flex-col items-center justify-center w-full h-full bg-white">
                <!-- Top Bar: Date/Time (left) and Logo (right) -->
                <div class="top-0 px-4 py-4 position-absolute start-0 end-0 d-flex justify-content-between align-items-center" style="width: 100%;">
                    <!-- Date & Time (Left) -->
                    <div>
                        <div id="lockscreen-datetime"
                            class="justify-between px-4 py-3 bg-opacity-75 d-flex align-items-center rounded-3"
                            style="backdrop-filter: blur(6px); letter-spacing: 0.02em; font-family: 'Segoe UI', sans-serif; min-width: 280px;">

                            <div class="time fs-1 fw-bold text-dark d-flex align-items-center">
                                <i class="bi bi-clock me-2 fs-4 text-secondary"></i>
                                <span id="lockscreen-time" class="fs-1"></span>
                            </div>

                            <div class="date text-end ps-3">
                                <div id="lockscreen-weekday" class="fw-semibold text-dark small"></div>
                                <div id="lockscreen-full-date" class="text-muted small"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Logo (Right) -->
                    <div>
                        <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo"
                            class="" style="height: 60px;" />
                    </div>
                </div>

                <!-- Full screen center card: Continue Selling -->
                <div class="flex-grow d-flex justify-content-center align-items-center w-100">
                    <button wire:click="{{ (session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'continueSelling' : 'openRegister' }}"
                        class="gap-2 p-5 bg-white cursor-pointer text-dark fw-semibold fs-2 border-1 bg-opacity-90 align-items-center"
                        style="transition: box-shadow 0.2s; height: 200px; border-radius: 10px;">
                        <i class="fas fa-shopping-basket" style="font-size: 45px;"></i>
                        <div>
                            @php
                                $label = (session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'Continue Selling' : 'Open Register';
                            @endphp

                            {{ $label }}
                        </div>
                    </button>
                </div>
                <!-- Bottom Bar: Backend Button -->
                <div class="bottom-0 pb-4 position-absolute start-0 end-0 d-flex justify-content-center align-items-center w-100">
                    <button wire:click="goToBackend" class="px-5 py-2 shadow-sm btn btn-outline-dark rounded-pill fw-semibold fs-4">
                        <i class="bi bi-gear me-2"></i> {{ __('Backend') }}
                    </button>
                </div>
            </div>
            <script>
                function updateLockscreenDateTime() {
                    const timeEl = document.getElementById('lockscreen-time');
                    const weekdayEl = document.getElementById('lockscreen-weekday');
                    const fullDateEl = document.getElementById('lockscreen-full-date');

                    const now = new Date();

                    const timeStr = now.toLocaleTimeString(undefined, {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const weekday = now.toLocaleDateString(undefined, {
                        weekday: 'short'
                    });

                    const fullDate = now.toLocaleDateString(undefined, {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    timeEl.textContent = timeStr;
                    weekdayEl.textContent = weekday;
                    fullDateEl.textContent = fullDate;
                }

                document.addEventListener('DOMContentLoaded', () => {
                    updateLockscreenDateTime();
                    setInterval(updateLockscreenDateTime, 1000);
                });
            </script>
        </div>
        <!-- Lock Screen -->

        <!-- Navbar -->
        <nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky dark:bg-gray-800">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="">
                        <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" class="navbar-brand-image normal">
                        <img src="{{ asset('assets/images/logo/ndako-white.png') }}" alt="Ndako Logo" class="navbar-brand-image dark">
                    </a>
                </h1>
                <div class="flex-row navbar-nav order-md-last">
                    <div class="d-md-flex d-flex">
                        <div class="nav-item dropdown d-md-flex me-3">
                            <a href="#" class="px-0 nav-link text-dark" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                <i class="bi bi-translate" style="font-size: 16px;"></i>
                            </a>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
                            </a>
                            <div class="p-0 dropdown-menu dark-menu pos-burger-menu-items dropdown-menu-end dropdown-menu-arrow">
                                <div class="p-2 pb-3 mb-2 border-bottom">
                                    <span class="text-center btn pos-customer-screen btn-lg w-100 dark:bg-gray-700 dark:text-gray-200">
                                        <i class="fas fa-desktop"></i>
                                    </span>
                                </div>
                                <div class="p-2 rounded menu-items">
                                    <span class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 toggle-theme">
                                        <span class="theme-label">{{ __('Switch to Dark Mode') }}</span>
                                    </span>
                                    <span class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        {{ __('Cash In/Out') }}
                                    </span>
                                    <span wire:click="goToBackend" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        {{ __('Backend') }}
                                    </span>
                                    <span wire:click="closeRegister" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        {{ __('Close Register') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                                <li class="cursor-pointer nav-item" data-turbolinks>
                                    <a class="nav-link kover-navlink {{ $interface == 'tables' ? 'selected' : '' }} dark:text-gray-200" wire:click="switchInterface('tables')" style="margin-right: 5px;">
                                        <span class="nav-link-title">{{ __('Tables') }}</span>
                                    </a>
                                </li>
                                <li class="cursor-pointer nav-item" data-turbolinks>
                                    <a class="nav-link kover-navlink {{ $interface == 'register' ? 'selected' : '' }} dark:text-gray-200" wire:click="switchInterface('register')" style="margin-right: 5px;">
                                        <span class="nav-link-title">{{ __('Register') }}</span>
                                    </a>
                                </li>
                                <li class="cursor-pointer nav-item" data-turbolinks>
                                    <a class="nav-link kover-navlink {{ $interface == 'orders' ? 'selected' : '' }} dark:text-gray-200" wire:click="switchInterface('orders')" style="margin-right: 5px;">
                                        <span class="nav-link-title">{{ __('Orders') }}</span>
                                    </a>
                                </li>
                                @if($selectedTable)
                                <li class="nav-item" data-turbolinks>
                                    <span class="text-white cursor-pointer badge rounded-pill bg-info fs-4 fw-bolder text-truncate dark:bg-blue-700">
                                        {{ $selectedTable->table_name ?? __('Direct Sale') }}
                                    </span>
                                </li>
                                @endif
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Regiter -->
        <div class="row {{ $interface == 'register' ? '' : 'd-none' }}">
            <!-- Product Section -->
            <section class="container-fluid {{ $tab == 'cart' ? 'd-none d-lg-block' : '' }} col-lg-7 col-md-12" style="height: 100vh;" id="product-box">
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Search products..." aria-label="Search products" wire:model.live="searchQuery">
                    <i class="bi bi-search search-icon"></i>
                </div>

                <!-- Categories -->
                <div class="category_section_buttons">
                    <div class="d-flex w-100">
                        <span class="category_button cursor-pointer home {{ $selectedCategoryId == null ? 'selected' : '' }}" wire:click="selectCategory('')">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <div class="cursor-pointer d-flex w-100 section_buttons">
                            @foreach ($productCategoryOptions as $category)
                            <span class="gap-2 category_button {{ $selectedCategoryId == $category->id ? 'selected' : '' }}" wire:click="selectCategory('{{ $category->id }}')">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="gap-2 p-3 product-list row row-cols-2 row-cols-md-3 row-cols-lg-4">
                    @foreach ($productOptions as $product)
                    <article class="cursor-pointer product" wire:click="addToCart('{{ $product->id }}')">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info"><i class="fas fa-infinity"></i></div>
                        <img src="{{ $product->image_path ? Storage::url('avatars/' . $product->image_path) . '?v=' . time() : asset('assets/images/default/product.png') }}"
                            alt="{{ $product->product_name }}" class="card-img-top" alt="Product">
                        <div class="product-content">
                            <div class="product-name">{{ $product->product_name }}</div>
                            <div class="price-tag">{{ format_currency($product->product_price) }}</div>
                        </div>
                    </article>
                    @endforeach
                </div>
                {{-- <div class="pagination">
                    {{ $productOptions->links() }}
                </div> --}}

            </section>

            <!-- Checkout Section -->
            <section class="col-lg-5 col-md-12 {{ $tab == 'pay' ? 'd-none d-lg-block' : '' }} " id="checkout-box">
                <div class="border-0 shadow-sm card">
                    <div class="card-body" id="cart-body">
                        <div class="overflow-y-auto order-container-bg-view flex-grow-1 d-flex flex-column text-start">

                            @forelse ($cart as $item)
                            <ul wire:click="selectProduct('{{ $item['id'] }}')">
                                <li class="p-2 cursor-pointer orderline lh-s  {{ $selectedProductId == $item['id'] ? 'selected' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="product-name w-75 fw-bolder pe-1 text-truncate">
                                            {{ $item['name'] }}
                                        </div>
                                        <div class="product-price w-25 text-end fw-bolder">
                                            {{ format_currency(($item['unit_price'] * $item['quantity']) ) }}
                                        </div>
                                    </div>
                                    <ul>
                                        <li class="price-per-unit">
                                            <em class="qty fst-normal fw-bolder me-1">{{ $item['quantity'] }}</em>
                                            unit(s) x {{ format_currency($item['unit_price']) }}
                                        </li>
                                        @if ($item['discount'] > 0)
                                        <li class="price-per-unit text-muted">
                                            {{ $item['discount'] }}% discount
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                            @empty
                            <div class="empty-cart d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted">
                                <i class="rotate-45 bi bi-cart-fill" style="font-size: 60px; color: #898989;"></i>
                                <br>
                                <h3>
                                    {{ __('No items in cart.') }}
                                </h3>
                            </div>
                            @endforelse
                        </div>
                        <div class="px-3 py-2 order-summary w-100 bg-100 text-end fw-bolder fs-2 lh-sm">
                            Total: <span class="total">{{ format_currency($cartTotal) }}</span>
                            <div class="text-muted subentry">
                                Taxes: <span class="tax">(+) {{ format_currency($cartTax) }}</span>
                            </div>
                        </div>
                        <div class="flex-wrap control_buttons d-flex bg-300 border-bottom">

                            <button class="gap-2 k_price_list_button btn btn-light rounded-0 fw-bolder">
                                <i class="fas fa-tags"></i> <span>Pricelists</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="fas fa-sync-alt"></i> <span>Refund</span>
                            </button>
                            <button onclick="Livewire.dispatch('openModal', {component: 'pos::modal.service-type-modal'})" class="gap-2 btn btn-light rounded-0 fw-bolder preset">
                                @if($selectedService)
                                <i class="{{ $selectedService['icon'] }}"></i> <span>{{ $selectedService['label'] }}</span>
                                @else
                                {{ __('Service Type') }}
                                @endif
                            </button>

                            <button class="gap-3 btn btn-light rounded-0 fw-bolder" wire:click="switchInterface('tables')" style="background-color: #B7EDBE;">
                                <i class="fas fa-chair"></i> <span>{{ $selectedTable->table_name ?? __('Table') }}</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Customer Note</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Note</span>
                            </button>

                            <button wire:click="cancelOrder" wire:confirm="{{ __('Are you sure to reset the cart?') }}" class="gap-2 btn btn-light rounded-0 fw-bolder {{ empty($cart) ? 'disabled' : '' }}" id="reset-cart">
                                <i class="fas fa-trash"></i> <span>Cancel Order</span>
                            </button>
                            @php
                                $customer = $this->guest ? Str::limit($this->guest->name, 10) : __('Guest');
                            @endphp
                            <button onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-modal'})" class="gap-2 btn btn-light rounded-0 fw-bolder" id="reset-cart">
                                <i class="fas fa-user"></i> <span>{{ $customer }}</span>
                            </button>

                        </div>

                        <!-- Calculator -->
                        <div class="flex-wrap calculator_buttons d-flex bg-300 border-bottom">
                            <div class="flex-wrap w-25 d-flex" id="vertical_buttons">
                                <button wire:click="processPayment" class="btn btn-light rounded-0 fw-bolder {{ empty($cart) ? 'disabled' : '' }}" id="pay">
                                    {{ __('Payment') }}
                                </button>
                            </div>
                            <div x-data="calculatorComponent(@this)"
                                x-init="
                                    window.addEventListener('keydown', (e) => {
                                        press(e.key);
                                    });"
                                class="flex-wrap w-75 d-flex"
                            >
                                <template x-for="key in keys" :key="key.label + key.value">
                                    <button
                                        type="button"
                                        @click="press(key.value)"
                                        :class="[
                                            'btn',
                                            'rounded-0',
                                            'fw-bolder',
                                            key.class,
                                            key.mode && $wire.calculatorMode === key.value ? 'selected' : ''
                                        ]"
                                        :style="key.style"
                                    >
                                        <template x-if="key.icon">
                                            <i :class="key.icon"></i>
                                        </template>
                                        <template x-if="!key.icon">
                                            <span x-text="key.label"></span>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <!-- Calculator -->
                    </div>
                </div>
            </section>

            <!-- Mobile Checkout -->
            <section class="d-lg-none" id="mobile-checkout-box">
                <div class="fixed-bar">
                    <button wire:click="changeTab('pay')" class="text-white btn-switch_pane rounded-0 fw-bolder review-button" id="pay-order">
                        <span class="fs-1 d-block">Pay</span>
                        <span>{{ format_currency($cartTotal) }}</span>
                    </button>
                    <button wire:click="changeTab('cart')" class="text-black btn-switch_pane rounded-0 fw-bolder review-button">
                        <span class="fs-1 d-block">Cart</span>
                        <span>{{ count($cart) }} items</span>
                    </button>
                </div>
            </section>
        </div>
        <!-- Regiter -->

        <!-- Payment -->
        <div class="payment-container bg-white {{ $interface == 'payment' ? '' : 'd-none' }}" style="height: 100vh;">
            <div class="payment-confirmed">
                <div class="row">
                    <div class="top-content d-print-none">
                        <h1>{{ format_currency($order->total_amount ?? 0) }}</h1>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-6 d-print-none">
                        <div class="actions justify-content-between flex-lg-grow-1">

                            <div class="p-3 m-1 mt-2 mb-3 rounded payment-success-card d-flex flex-column align-items-center g-3 border-success bg-success-subtle text-success fs-3">
                                {{-- <i class="fas fa-check"></i> --}}
                                <i class="mb-2 bi bi-check-circle" style="font-size: 35px;"></i>
                                <span style="font-weight: 900;" class="fs-2 ">{{ __('Payment Successful') }}</span>
                                <div class="gap-2 mt-2 d-flex justify-content-center align-items-center fw-bolder">
                                    <span>{{ format_currency($order->total_amount ?? 0) }}</span>
                                    <span class="pt-1 text-white rounded cursor-pointer edit-order-payment badge bg-success">
                                        {{ __('Edit Payment') }}
                                    </span>
                                </div>
                            </div>

                            <button class="gap-2 py-5 m-1 button btn btn-print btn-lg w-100" onclick="window.print();">
                                <i class="mr-1 bi bi-printer fw-bold"></i>
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
                                                <li class="mt-1 price-per-unit" style="padding-left: 3px;">Cash: {{ format_currency($order->total_amount ?? 0 + $cartTax) }}</li>
                                                <li class="mt-1 price-per-unit" style="padding-left: 3px;">Card: {{ format_currency(0) }}</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Qr Code -->
                                <div class="mt-2 mb-2 text-center pos-receipt-order-data d-flex fs-5">
                                    <img src="{{ asset('assets/images/default/sample-qrcode.png') }}" style="height: 100px; width: 100px;" alt="" class="">

                                    <div class="d-block">
                                        <span class="fw-bolder">
                                            {{ __('Need an invoice?') }}
                                        </span>
                                        <p>Code: yhK2r</p>
                                    </div>
                                </div>

                                <!-- Order Meta -->
                                <div class="mt-2 text-center pos-receipt-order-data d-flex fs-5 flex-column align-items-center">
                                    <p>{{ __('Powered by ') }} <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
                                    <div>{{ \Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Payment -->

        <!-- Tables -->
        <div class="table-container d-print-none bg-white {{ $interface == 'tables' ? '' : 'd-none' }} dark:bg-gray-800" style="height: 100vh;">
            <div class="gap-3 px-3 table-navbar d-flex flex-column gap-lg-1 d-print-none">
                <div class="gap-5 p-2 table-navbar-main d-flex flex-nowrap justify-content-between align-items-lg-start flex-grow-1">
                    <div class="gap-1 table-navbar-left d-flex align-items-center order-0">
                        <button wire:click="newOrder" class="new-order btn btn-primary fs-3 btn-lg lh-lg dark:bg-indigo-600">
                            <i class="bi bi-plus fs-3"></i> <span class="d-none d-lg-flex">New Order</span>
                        </button>
                    </div>
                    <div id="actions" class="order-2 gap-2 d-inline-flex rounded-2 table-navbar-actions d-flex align-items-center justify-content-between order-lg-1">
                        <div class="gap-3 d-flex align-items-center">
                            <div class="table-navbar-buttons align-items-center">
                                @foreach ($floorPlanOptions as $plan)
                                <span wire:click="changeFloorPlan('{{ $plan->id }}')" class="w-auto gap-1 k_switch_view fs-3 d-lg-inline-block btn btn-secondary {{ $plan->id == $selectedPlanId ? 'active' : '' }} k-list dark:bg-gray-800 dark:text-gray-200">
                                    {{ $plan->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="flex-wrap order-3 align-items-end table-navbar-left d-flex flex-md-wrap align-items-center justify-content-end gap-l-1 gap-xl-5 order-lg-2 flex-grow-1">
                        <div class="table-navbar-buttons d-print-none d-xl-inline-flex btn-group">
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5 overflow-y-auto table-section row h-100 ">
                @foreach($floorPlanOptions->where('id', $selectedPlanId)->first()->tables as $table)
                <div class="floor col-md-3">
                    <div class="p-0 rounded cursor-pointer floor-table flex-column justify-content-between position-absolute dark:bg-gray-700">
                        <div wire:click="selectTable('{{ $table->id }}')" class="info {{ $selectedTable?->id == $table->id ? 'active' : '' }} w-100 h-100 overflow-hidden dark:text-gray-200">
                            <div class="label top-50 start-50 fw-bolder position-absolute fs-3 translate-middle">
                                {{ $table->table_name }}
                                <br>
                                <small>{{ inverseSlug($table->status) }}</small>
                            </div>
                        </div>
                        @if($table->status == 'occupied')
                        <button wire:click="releaseTable('{{ $table->id }}')" class="bottom-0 m-1 btn btn-danger btn-sm position-absolute end-0 dark:bg-red-800 dark:border-red-800">
                            Release
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Tables -->

        <!-- Orders -->

        <!-- Orders -->
        <div class="order-container overflow-y-auto bg-white {{ $interface == 'orders' ? '' : 'd-none' }}" style="height: 100vh;">
            <div class="p-6">
                <h2 class="mb-6 text-2xl font-bold text-gray-800">{{ __('Order History') }}</h2>

                <!-- Filters -->
                <div class="flex flex-col gap-4 mb-6 md:flex-row">
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600">{{ __('Status') }}</label>
                        <select wire:model="orderStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('All') }}</option>
                            <option value="ongoing">{{ __('Ongoing') }}</option>
                            <option value="completed">{{ __('Completed') }}</option>
                            <option value="refunded">{{ __('Refunded') }}</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600">{{ __('Payment Status') }}</label>
                        <select wire:model="paymentStatusFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">{{ __('All') }}</option>
                            <option value="unpaid">{{ __('Unpaid') }}</option>
                            <option value="paid">{{ __('Paid') }}</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600">{{ __('Date Range') }}</label>
                        <input type="date" wire:model="dateFilter" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600">{{ __('Search') }}</label>
                        <input type="text" wire:model.debounce.500ms="searchQuery" placeholder="{{ __('Search by ID, customer, or table') }}" class="w-full mt-1 transition duration-150 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Loading State -->
                <div wire:loading class="mb-4 text-center text-gray-500">
                    <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                    </svg>
                    {{ __('Loading orders...') }}
                </div>

                <!-- Orders Table -->
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Order ID') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Table') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Customer') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Total') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Payment') }}</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($orders as $order)
                            <tr class="transition duration-150 hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">{{ $order->receipt_number }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->table->table_name ?? 'Direct Sale' }}</td>
                                <td class="px-4 py-3 text-sm">{{ $order->guest->name ?? 'No Guest' }}</td>
                                <td class="px-4 py-3 text-sm">{{ format_currency($order->total_amount + ($order->tax_amount ?? 0)) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $order->status == 'ongoing' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="flex gap-2 px-4 py-3 text-sm">
                                    @php
                                        $cartData = session("pos_cart_{$pos->id}");
                                    @endphp
                                    @if ($order->status === 'ongoing' && ($cartData['active_order_id'] ?? null) != $order->id)
                                        <button wire:click="selectOrder('{{ $order->id }}')" class="relative transition duration-150 btn btn-primary btn-sm group hover:bg-indigo-600" title="{{ __('Select this order') }}">
                                            {{ __('Select') }}
                                            <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Select this order') }}</span>
                                        </button>
                                    @endif
                                    @if($order->status == 'ongoing')
                                        <button wire:click="confirmDelete('{{ $order->id }}')" class="relative transition duration-150 btn btn-danger btn-sm group hover:bg-red-600" title="{{ __('Delete this order') }}">
                                            {{ __('Delete') }}
                                            <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Delete this order') }}</span>
                                        </button>
                                        <button wire:click="printPreBill('{{ $order-> receipt_number }}')" class="relative transition duration-150 btn btn-info btn-sm group hover:bg-blue-600" title="{{ __('Print pre-bill') }}">
                                            {{ __('Pre-Bill') }}
                                            <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Print pre-bill') }}</span>
                                        </button>
                                    @endif
                                    @if($order->status != 'refunded' && $order->payment_status == 'paid')
                                        <button wire:click="confirmRefund('{{ $order->id }}')" class="relative transition duration-150 btn btn-danger btn-sm group hover:bg-red-600" title="{{ __('Refund this order') }}">
                                            {{ __('Refund') }}
                                            <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('Refund this order') }}</span>
                                        </button>
                                    @endif
                                    <button wire:click="showOrderDetails('{{ $order->id }}')" class="relative transition duration-150 btn btn-secondary btn-sm group hover:bg-gray-600" title="{{ __('View order details') }}">
                                        {{ __('Details') }}
                                        <span class="absolute hidden px-2 py-1 text-xs text-white transform -translate-x-1/2 bg-gray-800 rounded group-hover:block -top-8 left-1/2">{{ __('View order details') }}</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-sm text-center text-gray-500">{{ __('No orders found.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{-- {{ $orders->links() }} --}}
                </div>

                <!-- Confirmation Modal for Delete/Refund -->
                <div x-data="{ open: false, action: '', orderId: null }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
                    <div class="w-full max-w-md p-6 bg-white rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">{{ __('Confirm Action') }}</h3>
                        <p class="text-sm text-gray-600" x-text="action === 'delete' ? '{{ __('Are you sure you want to delete this order?') }}' : '{{ __('Are you sure you want to refund this order?') }}'"></p>
                        <div class="flex justify-end gap-2 mt-6">
                            <button x-on:click="open = false" class="btn btn-secondary btn-sm">{{ __('Cancel') }}</button>
                            <button x-on:click="open = false; $wire.dispatch(action, [orderId])" class="btn btn-danger btn-sm">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Order Details Modal -->
                <div x-data="{ detailsOpen: false }" x-show="detailsOpen" class="fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50">
                    <div class="w-full max-w-lg p-6 bg-white rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">{{ __('Order Details') }}</h3>
                        <div wire:loading wire:target="showOrderDetails" class="text-center text-gray-500">
                            <svg class="inline-block w-5 h-5 mr-2 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                            </svg>
                            {{ __('Loading details...') }}
                        </div>
                        <div x-show="!$wire.loading">
                            <!-- Placeholder for order details, populated by Livewire -->
                            <p class="text-sm text-gray-600">{{ __('Order ID') }}: <span wire:model="selectedOrder.receipt_number"></span></p>
                            <p class="text-sm text-gray-600">{{ __('Items') }}: <span wire:model="selectedOrder.items"></span></p>
                            <p class="text-sm text-gray-600">{{ __('Total') }}: <span wire:model="selectedOrder.total_amount"></span></p>
                            <!-- Add more details as needed -->
                        </div>
                        <div class="flex justify-end mt-6">
                            <button x-on:click="detailsOpen = false" class="btn btn-secondary btn-sm">{{ __('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Orders -->
    </main>

<script>
    function calculatorComponent($wire) {
        return {
            input: '',
            keys: [
                { label: '1', value: '1' },
                { label: '2', value: '2' },
                { label: '3', value: '3' },
                { label: 'Qty', value: 'qty', class: 'btn-light', mode: true },
                { label: '4', value: '4' },
                { label: '5', value: '5' },
                { label: '6', value: '6' },
                { label: 'Disc', value: 'discount', icon: 'bi bi-percent', class: 'btn-light', mode: true },
                { label: '7', value: '7' },
                { label: '8', value: '8' },
                { label: '9', value: '9' },
                { label: 'Price', value: 'price', class: 'btn-light', mode: true },
                { label: '÷', value: '/', style: 'background-color: #F5D976;' },
                { label: '0', value: '0' },
                { label: '.', value: '.', style: 'background-color: #F5D7CB;' },
                { label: '', value: 'Backspace', icon: 'bi bi-backspace', style: 'background-color: #FAA0A0;' },
            ],

            press(value) {

                // Prevent any action if no product is selected
                if (!$wire.selectedProductId) {
                    return;
                }

                if (['qty', 'discount', 'price'].includes(value)) {
                    $wire.selectCalculatorMode(value); // Now $wire is defined
                    return;
                }

                // Handle mapped keys
                switch (value) {
                    case 'q':
                        $wire.selectCalculatorMode('qty');
                        return;
                    case 'p':
                        $wire.selectCalculatorMode('price');
                        return;
                    case 'd':
                        $wire.selectCalculatorMode('discount');
                        return;
                    case '/':
                        this.input += '/';
                        break;
                    case 'Backspace':
                        this.input = this.input.slice(0, -1);
                        break;
                    case 'Enter':
                        // Placeholder for calculation or submission logic
                        console.log('Enter pressed');
                        break;
                    default:
                        if (/^[0-9]$/.test(value) || value === '.') {
                            this.input += value;
                        } else {
                            return; // Ignore unknown keys
                        }
                }

                // Optional: send to Livewire if needed
                $wire.set('calculatorInput', this.input);
                $wire.applyCalculatorInput(); // ← Realtime update on each key press

            },

        };
    }
// Dark Mode Handling
    (function () {
        const html = document.documentElement;
        const toggleButton = document.querySelector('.toggle-theme');
        const themeLabel = toggleButton.querySelector('.theme-label');

        // Initialize theme: check localStorage, then system preference, default to light
        let currentTheme = localStorage.getItem('theme');
        if (!currentTheme) {
            currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            localStorage.setItem('theme', currentTheme);
        }
        html.setAttribute('data-theme', currentTheme);
        themeLabel.textContent = currentTheme === 'dark' ? '{{ __('Switch to Light Mode') }}' : '{{ __('Switch to Dark Mode') }}';

        // Toggle theme on button click
        toggleButton.addEventListener('click', function () {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', currentTheme);
            localStorage.setItem('theme', currentTheme);
            themeLabel.textContent = currentTheme === 'dark' ? '{{ __('Switch to Light Mode') }}' : '{{ __('Switch to Dark Mode') }}';
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) { // Only apply if user hasn't set a preference
                currentTheme = e.matches ? 'dark' : 'light';
                html.setAttribute('data-theme', currentTheme);
                themeLabel.textContent = currentTheme === 'dark' ? '{{ __('Switch to Light Mode') }}' : '{{ __('Switch to Dark Mode') }}';
            }
        });
    })();

</script>

