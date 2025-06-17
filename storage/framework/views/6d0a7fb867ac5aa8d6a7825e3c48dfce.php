
    <?php $__env->startSection('title', $pos->name); ?>
    <?php $__env->startSection('styles'); ?>
    <style>
        /* Custom animations */

    </style>
    <?php $__env->stopSection(); ?>

    <main class="main relative" x-data="{ isLocked: <?php if ((object) ('isLocked') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isLocked'->value()); ?>')<?php echo e('isLocked'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isLocked'); ?>')<?php endif; ?>, timer: null }" x-init="
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
        <div x-show="isLocked" style="z-index: 99999;" class="fixed inset-0 flex items-center justify-center bg-body-secondary bg-opacity-75 backdrop-blur animate-fade-in">
            <div class="relative flex flex-col items-center justify-center w-full h-full bg-white">
                <!-- Top Bar: Date/Time (left) and Logo (right) -->
                <div class="position-absolute top-0 start-0 end-0 d-flex justify-content-between align-items-center px-4 py-4" style="width: 100%;">
                    <!-- Date & Time (Left) -->
                    <div>
                        <div id="lockscreen-datetime"
                            class="d-flex justify-between align-items-center bg-opacity-75 rounded-3 px-4 py-3"
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
                        <img src="<?php echo e(asset('assets/images/logo/ndako.png')); ?>" alt="Ndako Logo"
                            class="" style="height: 60px;" />
                    </div>
                </div>

                <!-- Full screen center card: Continue Selling -->
                <div class="flex-grow d-flex justify-content-center align-items-center w-100">
                    <button wire:click="<?php echo e((session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'continueSelling' : 'openRegister'); ?>"
                        class="p-5 text-dark fw-semibold fs-2 border-1 bg-white bg-opacity-90 cursor-pointer align-items-center gap-2"
                        style="transition: box-shadow 0.2s; height: 200px; border-radius: 10px;">
                        <i class="fas fa-shopping-basket" style="font-size: 45px;"></i>
                        <div>
                            <?php
                                $label = (session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'Continue Selling' : 'Open Register';
                            ?>

                            <?php echo e($label); ?>

                        </div>
                    </button>
                </div>
                <!-- Bottom Bar: Backend Button -->
                <div class="position-absolute bottom-0 start-0 end-0 d-flex justify-content-center align-items-center w-100 pb-4">
                    <button wire:click="goToBackend" class="btn btn-outline-dark px-5 py-2 rounded-pill fw-semibold fs-4 shadow-sm">
                        <i class="bi bi-gear me-2"></i> <?php echo e(__('Backend')); ?>

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
                        <img src="<?php echo e(asset('assets/images/logo/ndako.png')); ?>" alt="Ndako Logo" class="navbar-brand-image normal">
                        <img src="<?php echo e(asset('assets/images/logo/ndako-white.png')); ?>" alt="Ndako Logo" class="navbar-brand-image dark">
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
                                <span class="avatar avatar-sm" style="background-image: url(<?php echo e(Storage::url('avatars/' . auth()->user()->avatar)); ?>)"></span>
                            </a>
                            <div class="dropdown-menu dark-menu p-0 pos-burger-menu-items dropdown-menu-end dropdown-menu-arrow">
                                <div class="border-bottom p-2 mb-2 pb-3">
                                    <span class="btn pos-customer-screen btn-lg w-100 text-center dark:bg-gray-700 dark:text-gray-200">
                                        <i class="fas fa-desktop"></i>
                                    </span>
                                </div>
                                <div class="menu-items p-2 rounded">
                                    <span class="dropdown-item cursor-pointer fs-4 kover-navlink rounded-1 toggle-theme">
                                        <span class="theme-label"><?php echo e(__('Switch to Dark Mode')); ?></span>
                                    </span>
                                    <span class="dropdown-item cursor-pointer fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        <?php echo e(__('Cash In/Out')); ?>

                                    </span>
                                    <span wire:click="goToBackend" class="dropdown-item cursor-pointer fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        <?php echo e(__('Backend')); ?>

                                    </span>
                                    <span wire:click="closeRegister" class="dropdown-item cursor-pointer fs-4 kover-navlink rounded-1 dark:text-gray-200">
                                        <?php echo e(__('Close Register')); ?>

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
                                <li class="nav-item cursor-pointer" data-turbolinks>
                                    <a class="nav-link kover-navlink <?php echo e($interface == 'tables' ? 'selected' : ''); ?> dark:text-gray-200" wire:click="switchInterface('tables')" style="margin-right: 5px;">
                                        <span class="nav-link-title"><?php echo e(__('Tables')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item cursor-pointer" data-turbolinks>
                                    <a class="nav-link kover-navlink <?php echo e($interface == 'register' ? 'selected' : ''); ?> dark:text-gray-200" wire:click="switchInterface('register')" style="margin-right: 5px;">
                                        <span class="nav-link-title"><?php echo e(__('Register')); ?></span>
                                    </a>
                                </li>
                                <li class="nav-item cursor-pointer" data-turbolinks>
                                    <a class="nav-link kover-navlink <?php echo e($interface == 'orders' ? 'selected' : ''); ?> dark:text-gray-200" wire:click="switchInterface('orders')" style="margin-right: 5px;">
                                        <span class="nav-link-title"><?php echo e(__('Orders')); ?></span>
                                    </a>
                                </li>
                                <!--[if BLOCK]><![endif]--><?php if($selectedTable): ?>
                                <li class="nav-item" data-turbolinks>
                                    <span class="badge rounded-pill bg-info text-white fs-4 cursor-pointer fw-bolder text-truncate dark:bg-blue-700">
                                        <?php echo e($selectedTable->table_name ?? __('Direct Sale')); ?>

                                    </span>
                                </li>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <!-- Regiter -->
        <div class="row <?php echo e($interface == 'register' ? '' : 'd-none'); ?>">
            <!-- Product Section -->
            <section class="container-fluid <?php echo e($tab == 'cart' ? 'd-none d-lg-block' : ''); ?> col-lg-7 col-md-12" style="height: 100vh;" id="product-box">
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Search products..." aria-label="Search products" wire:model.live="searchQuery">
                    <i class="bi bi-search search-icon"></i>
                </div>

                <!-- Categories -->
                <div class="category_section_buttons">
                    <div class="d-flex w-100">
                        <span class="category_button cursor-pointer home <?php echo e($selectedCategoryId == null ? 'selected' : ''); ?>" wire:click="selectCategory('')">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <div class="cursor-pointer d-flex w-100 section_buttons">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productCategoryOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="gap-2 category_button <?php echo e($selectedCategoryId == $category->id ? 'selected' : ''); ?>" wire:click="selectCategory('<?php echo e($category->id); ?>')">
                                <?php echo e($category->name); ?>

                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="gap-2 p-3 product-list row row-cols-2 row-cols-md-3 row-cols-lg-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $productOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="product cursor-pointer" wire:click="addToCart('<?php echo e($product->id); ?>')">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info"><i class="fas fa-infinity"></i></div>
                        <img src="<?php echo e($product->image_path ? Storage::url('avatars/' . $product->image_path) . '?v=' . time() : asset('assets/images/default/product.png')); ?>"
                            alt="<?php echo e($product->product_name); ?>" class="card-img-top" alt="Product">
                        <div class="product-content">
                            <div class="product-name"><?php echo e($product->product_name); ?></div>
                            <div class="price-tag"><?php echo e(format_currency($product->product_price)); ?></div>
                        </div>
                    </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                

            </section>

            <!-- Checkout Section -->
            <section class="col-lg-5 col-md-12 <?php echo e($tab == 'pay' ? 'd-none d-lg-block' : ''); ?> " id="checkout-box">
                <div class="border-0 shadow-sm card">
                    <div class="card-body" id="cart-body">
                        <div class="overflow-y-auto order-container-bg-view flex-grow-1 d-flex flex-column text-start">

                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <ul wire:click="selectProduct('<?php echo e($item['id']); ?>')">
                                <li class="p-2 cursor-pointer orderline lh-s  <?php echo e($selectedProductId == $item['id'] ? 'selected' : ''); ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="product-name w-75 fw-bolder pe-1 text-truncate">
                                            <?php echo e($item['name']); ?>

                                        </div>
                                        <div class="product-price w-25 text-end fw-bolder">
                                            <?php echo e(format_currency(($item['unit_price'] * $item['quantity']) )); ?>

                                        </div>
                                    </div>
                                    <ul>
                                        <li class="price-per-unit">
                                            <em class="qty fst-normal fw-bolder me-1"><?php echo e($item['quantity']); ?></em>
                                            unit(s) x <?php echo e(format_currency($item['unit_price'])); ?>

                                        </li>
                                        <!--[if BLOCK]><![endif]--><?php if($item['discount'] > 0): ?>
                                        <li class="price-per-unit text-muted">
                                            <?php echo e($item['discount']); ?>% discount
                                        </li>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </ul>
                                </li>
                            </ul>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="empty-cart d-flex flex-column align-items-center justify-content-center h-100 w-100 text-muted">
                                <i class="bi bi-cart-fill rotate-45" style="font-size: 60px; color: #898989;"></i>
                                <br>
                                <h3>
                                    <?php echo e(__('No items in cart.')); ?>

                                </h3>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="px-3 py-2 order-summary w-100 bg-100 text-end fw-bolder fs-2 lh-sm">
                            Total: <span class="total"><?php echo e(format_currency($cartTotal)); ?></span>
                            <div class="text-muted subentry">
                                Taxes: <span class="tax">(+) <?php echo e(format_currency($cartTax)); ?></span>
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
                                <!--[if BLOCK]><![endif]--><?php if($selectedService): ?>
                                <i class="<?php echo e($selectedService['icon']); ?>"></i> <span><?php echo e($selectedService['label']); ?></span>
                                <?php else: ?>
                                <?php echo e(__('Service Type')); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </button>

                            <button class="gap-3 btn btn-light rounded-0 fw-bolder" wire:click="switchInterface('tables')" style="background-color: #B7EDBE;">
                                <i class="fas fa-chair"></i> <span><?php echo e($selectedTable->table_name ?? __('Table')); ?></span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Customer Note</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Note</span>
                            </button>

                            <button wire:click="cancelOrder" wire:confirm="<?php echo e(__('Are you sure to reset the cart?')); ?>" class="gap-2 btn btn-light rounded-0 fw-bolder <?php echo e(empty($cart) ? 'disabled' : ''); ?>" id="reset-cart">
                                <i class="fas fa-trash"></i> <span>Cancel Order</span>
                            </button>
                            <?php
                                $customer = $this->guest ? Str::limit($this->guest->name, 10) : __('Guest');
                            ?>
                            <button onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-modal'})" class="gap-2 btn btn-light rounded-0 fw-bolder" id="reset-cart">
                                <i class="fas fa-user"></i> <span><?php echo e($customer); ?></span>
                            </button>

                        </div>

                        <!-- Calculator -->
                        <div class="flex-wrap calculator_buttons d-flex bg-300 border-bottom">
                            <div class="flex-wrap w-25 d-flex" id="vertical_buttons">
                                <button onclick="Livewire.dispatch('openModal', {component: 'pos::modal.payment-modal', arguments: { order: <?php echo e($order->id ?? null); ?> } })" class="btn btn-light rounded-0 fw-bolder <?php echo e(empty($cart) ? 'disabled' : ''); ?>" id="pay">
                                    <?php echo e(__('Payment')); ?>

                                </button>
                            </div>
                            <div x-data="calculatorComponent(window.Livewire.find('<?php echo e($_instance->getId()); ?>'))"
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
                        <span><?php echo e(format_currency($cartTotal)); ?></span>
                    </button>
                    <button wire:click="changeTab('cart')" class="text-black btn-switch_pane rounded-0 fw-bolder review-button">
                        <span class="fs-1 d-block">Cart</span>
                        <span><?php echo e(count($cart)); ?> items</span>
                    </button>
                </div>
            </section>
        </div>
        <!-- Regiter -->

        <!-- Payment -->
        <div class="payment-container bg-white <?php echo e($interface == 'payment' ? '' : 'd-none'); ?>" style="height: 100vh;">
            <div class="payment-confirmed">
                <div class="row">
                    <div class="top-content d-print-none">
                        <h1><?php echo e(format_currency($order->total_amount ?? 0)); ?></h1>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-6 d-print-none">
                        <div class="actions justify-content-between flex-lg-grow-1">

                            <div class="payment-success-card m-1 mt-2 d-flex flex-column align-items-center mb-3 p-3 g-3 border-success rounded bg-success-subtle text-success fs-3">
                                
                                <i class="bi bi-check-circle mb-2" style="font-size: 35px;"></i>
                                <span style="font-weight: 900;" class="fs-2 "><?php echo e(__('Payment Successful')); ?></span>
                                <div class="d-flex mt-2 justify-content-center align-items-center gap-2 fw-bolder">
                                    <span><?php echo e(format_currency($order->total_amount ?? 0)); ?></span>
                                    <span class="edit-order-payment cursor-pointer badge bg-success text-white rounded pt-1">
                                        <?php echo e(__('Edit Payment')); ?>

                                    </span>
                                </div>
                            </div>

                            <button class="button m-1 btn btn-print btn-lg py-5 gap-2 w-100" onclick="window.print();">
                                <i class="mr-1 bi bi-printer fw-bold"></i>
                                <span><?php echo e(__('Print Full Receipt')); ?></span>
                            </button>

                            <div class="gap-1 mt-3 validation_buttons d-print-none d-none d-lg-flex w-100">
                                <a wire:click="newOrder" class="text-center cursor-pointer text-white p-3 rounded m-1 btn-switch_pane btn-primary fw-bolder review-button w-50 text-decoration-none">
                                    <span class="fs-1 d-block"><?php echo e(__('New Order')); ?></span>
                                </a>
                                <button wire:click="switchInterface('orders')" class="text-white p-3 rounded m-1 btn-switch_pane btn-primary fw-bolder review-button w-50">
                                    <span class="mb-1 fs-1 d-block"><?php echo e(__('Orders')); ?></span>
                                </button>
                            </div>

                            <!-- Mobile View -->
                            <div class="gap-1 mt-3 validation_buttons d-print-none d-flex d-lg-none fixed-bottom w-100">
                                <a wire:click="newOrder" class="text-center cursor-pointer text-white p-3 rounded m-1 btn-switch_pane btn-primary fw-bolder review-button w-50 text-decoration-none">
                                    <span class="fs-1 d-block"><?php echo e(__('New Order')); ?></span>
                                </a>
                                <button wire:click="switchInterface('orders')" class="text-white p-3 rounded m-1 btn-switch_pane btn-primary fw-bolder review-button w-50">
                                    <span class="mb-1 fs-1 d-block"><?php echo e(__('Orders')); ?></span>
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
                                    <img src="<?php echo e(asset('assets/images/logo/ndako.png')); ?>" alt="Ndako Logo" class="pos-receipt-logo">
                                </div>

                                <!-- Company Info -->
                                <div class="d-flex flex-column align-items-center company-info">
                                    <span><?php echo e(current_company()->address); ?></span>
                                    <!--[if BLOCK]><![endif]--><?php if(current_company()->phone): ?>
                                    <span>Tel: <?php echo e(current_company()->phone); ?></span>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div>-------------------------</div>
                                    <div><?php echo e(__('Guest')); ?>: <?php echo e($order->guest->name ?? 'Unknown'); ?></div>
                                    <div>Served by: <?php echo e($order->cashier->name ?? 'Unknown'); ?></div>
                                    <div class="receipt-number"><span class="fs-3">GHJKSSHSJJKJS</span></div>
                                </div>

                                <!-- Order list -->
                                <div class="overflow-y-auto mt-2 order-container-bg-view flex-grow-1 d-flex flex-column text-start">
                                    <ul>
                                        <!--[if BLOCK]><![endif]--><?php if($order): ?>
                                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <li class="p-2 cursor-pointer orderline lh-sm">
                                                    <div class="d-flex">
                                                        <div class="w-75 d-flex gap-2 pe-1 text-truncate">
                                                            <span class="qty fw-bolder"><?php echo e($item->quantity); ?></span>
                                                            <span class="name"><?php echo e($item->product->product_name ?? 'Unknown'); ?></span>
                                                        </div>
                                                        <div class="product-price w-50 text-end">
                                                            <?php echo e(format_currency(($item->unit_price * $item->quantity) * (1 - $item->product_discount_amount / 100))); ?>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <li class="p-2 text-muted"><?php echo e(__('No items in order.')); ?></li>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php else: ?>
                                            <li class="p-2 text-muted"><?php echo e(__('No active order.')); ?></li>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </ul>
                                </div>

                                <!-- Separator -->
                                <div class="align-items-center">---------------------------</div>

                                <!-- Totals -->
                                <div class="overflow-y-auto order-container-bg-view flex-grow-1 d-flex flex-column text-start">
                                    <ul>
                                        <li class="p-2 cursor-pointer orderline lh-sm">
                                            <div class="d-flex">
                                                <div class="w-75 pe-1 text-truncate"><?php echo e(__('Subtotal')); ?></div>
                                                <div class="w-50 text-end"><?php echo e(format_currency($order->total_amount ?? 0)); ?></div>
                                            </div>
                                        </li>
                                        <li class="p-2 cursor-pointer orderline lh-sm">
                                            <div class="d-flex">
                                                <div class="w-75 pe-1 text-truncate"><?php echo e(__('VAT')); ?> <?php echo e(config('pos.tax_rate', 0.16) * 100); ?>%</div>
                                                <div class="w-50 text-end"><?php echo e(format_currency($cartTax)); ?></div>
                                            </div>
                                        </li>
                                        <li class="p-2 cursor-pointer orderline lh-sm">
                                            <div class="d-flex">
                                                <div class="w-75 pe-1 text-truncate fw-bold"><?php echo e(__('Total')); ?></div>
                                                <div class="w-50 text-end fw-bold"><?php echo e(format_currency($order->total_amount ?? 0 + $cartTax)); ?></div>
                                            </div>
                                        </li>
                                        <li class="p-2 cursor-pointer orderline lh-sm">
                                            <div class="d-flex">
                                                <div class="w-75 pe-1 text-truncate"><?php echo e(__('Payment')); ?></div>
                                                <div class="w-50 text-end"><?php echo e(format_currency($order->total_amount ?? 0 + $cartTax)); ?></div>
                                            </div>
                                            <ul>
                                                <!-- Placeholder for payment methods; extend as needed -->
                                                <li class="price-per-unit mt-1" style="padding-left: 3px;">Cash: <?php echo e(format_currency($order->total_amount ?? 0 + $cartTax)); ?></li>
                                                <li class="price-per-unit mt-1" style="padding-left: 3px;">Card: <?php echo e(format_currency(0)); ?></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Qr Code -->
                                <div class="pos-receipt-order-data d-flex mt-2 mb-2 text-center fs-5">
                                    <img src="<?php echo e(asset('assets/images/default/sample-qrcode.png')); ?>" style="height: 100px; width: 100px;" alt="" class="">

                                    <div class="d-block">
                                        <span class="fw-bolder">
                                            <?php echo e(__('Need an invoice?')); ?>

                                        </span>
                                        <p>Code: yhK2r</p>
                                    </div>
                                </div>

                                <!-- Order Meta -->
                                <div class="pos-receipt-order-data d-flex mt-2 text-center fs-5 flex-column align-items-center">
                                    <p><?php echo e(__('Powered by ')); ?> <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
                                    <div><?php echo e(\Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Payment -->

        <!-- Tables -->
        <div class="table-container bg-white <?php echo e($interface == 'tables' ? '' : 'd-none'); ?> dark:bg-gray-800" style="height: 100vh;">
            <div class="gap-3 px-3 table-navbar d-flex flex-column gap-lg-1 d-print-none">
                <div class="gap-5 table-navbar-main p-2 d-flex flex-nowrap justify-content-between align-items-lg-start flex-grow-1">
                    <div class="gap-1 table-navbar-left d-flex align-items-center order-0">
                        <button wire:click="newOrder" class="new-order btn btn-primary fs-3 btn-lg lh-lg dark:bg-indigo-600">
                            <i class="bi bi-plus fs-3"></i> <span class="d-none d-lg-flex">New Order</span>
                        </button>
                    </div>
                    <div id="actions" class="order-2 gap-2 d-inline-flex rounded-2 table-navbar-actions d-flex align-items-center justify-content-between order-lg-1">
                        <div class="gap-3 d-flex align-items-center">
                            <div class="table-navbar-buttons align-items-center">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $floorPlanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span wire:click="changeFloorPlan('<?php echo e($plan->id); ?>')" class="w-auto gap-1 k_switch_view fs-3 d-lg-inline-block btn btn-secondary <?php echo e($plan->id == $selectedPlanId ? 'active' : ''); ?> k-list dark:bg-gray-800 dark:text-gray-200">
                                    <?php echo e($plan->name); ?>

                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                    <div class="flex-wrap order-3 align-items-end table-navbar-left d-flex flex-md-wrap align-items-center justify-content-end gap-l-1 gap-xl-5 order-lg-2 flex-grow-1">
                        <div class="table-navbar-buttons d-print-none d-xl-inline-flex btn-group">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-section  row overflow-y-auto p-5 h-100 ">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $floorPlanOptions->where('id', $selectedPlanId)->first()->tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="floor col-md-3">
                    <div class="floor-table p-0 rounded flex-column cursor-pointer justify-content-between position-absolute dark:bg-gray-700">
                        <div wire:click="selectTable('<?php echo e($table->id); ?>')" class="info table-info <?php echo e($selectedTable?->id == $table->id ? 'active' : ''); ?> w-100 h-100 overflow-hidden dark:text-gray-200">
                            <div class="label top-50 start-50 fw-bolder position-absolute fs-3 translate-middle">
                                <?php echo e($table->table_name); ?>

                                <br>
                                <small><?php echo e(inverseSlug($table->status)); ?></small>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($table->status == 'occupied'): ?>
                        <button wire:click="releaseTable('<?php echo e($table->id); ?>')" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 m-1 dark:bg-red-800 dark:border-red-800">
                            Release
                        </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <!-- Tables -->

        <!-- Orders -->

        <!-- Orders -->
        <div class="order-container overflow-y-auto bg-white <?php echo e($interface == 'orders' ? '' : 'd-none'); ?>" style="height: 100vh;">
            <div class="p-4">
                <h2 class="text-2xl font-bold mb-4"><?php echo e(__('Order History')); ?></h2>

                <!-- Filters -->
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600"><?php echo e(__('Status')); ?></label>
                        <select wire:model="orderStatusFilter" class="w-full mt-1 rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <option value="ongoing"><?php echo e(__('Ongoing')); ?></option>
                            <option value="completed"><?php echo e(__('Completed')); ?></option>
                            <option value="refunded"><?php echo e(__('Refunded')); ?></option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3">
                        <label class="text-sm font-medium text-gray-600"><?php echo e(__('Payment Status')); ?></label>
                        <select wire:model="paymentStatusFilter" class="w-full mt-1 rounded-md border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value=""><?php echo e(__('All')); ?></option>
                            <option value="unpaid"><?php echo e(__('Unpaid')); ?></option>
                            <option value="paid"><?php echo e(__('Paid')); ?></option>
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto ">
                    <table class="w-100 bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Order ID')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Table')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Customer')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Total')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Status')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Payment')); ?></th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php echo e(__('Actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm"><?php echo e($order->receipt_number); ?></td>
                                <td class="px-4 py-3 text-sm"><?php echo e($order->table->table_name ?? 'Direct Sale'); ?></td>
                                <td class="px-4 py-3 text-sm"><?php echo e($order->guest->name ?? 'No Guest'); ?></td>
                                <td class="px-4 py-3 text-sm"><?php echo e(format_currency($order->total_amount + ($order->tax_amount ?? 0))); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full">
                                        <?php echo e(ucfirst($order->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 rounded-full <?php echo e($order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e(ucfirst($order->payment_status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm flex gap-2">
                                    <?php
                                        $cartData = session("pos_cart_{$pos->id}");
                                    ?>

                                    <!--[if BLOCK]><![endif]--><?php if($order->status === 'ongoing' && ($cartData['active_order_id'] ?? null) != $order->id): ?>
                                        <button wire:click="selectOrder('<?php echo e($order->id); ?>')" class="btn btn-primary btn-sm"><?php echo e(__('Select')); ?></button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <!--[if BLOCK]><![endif]--><?php if($order->status == 'ongoing'): ?>
                                    <button wire:click="deleteOrder('<?php echo e($order->id); ?>')" class="btn btn-danger btn-sm"><?php echo e(__('Delete')); ?></button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <!--[if BLOCK]><![endif]--><?php if($order->status != 'refunded'): ?>
                                    <button wire:click="refundOrder('<?php echo e($order->id); ?>')" class="btn btn-danger btn-sm"><?php echo e(__('Refund')); ?></button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-4 py-3 text-sm text-gray-500 text-center"><?php echo e(__('No orders found.')); ?></td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
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
        themeLabel.textContent = currentTheme === 'dark' ? '<?php echo e(__('Switch to Light Mode')); ?>' : '<?php echo e(__('Switch to Dark Mode')); ?>';

        // Toggle theme on button click
        toggleButton.addEventListener('click', function () {
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', currentTheme);
            localStorage.setItem('theme', currentTheme);
            themeLabel.textContent = currentTheme === 'dark' ? '<?php echo e(__('Switch to Light Mode')); ?>' : '<?php echo e(__('Switch to Dark Mode')); ?>';
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) { // Only apply if user hasn't set a preference
                currentTheme = e.matches ? 'dark' : 'light';
                html.setAttribute('data-theme', currentTheme);
                themeLabel.textContent = currentTheme === 'dark' ? '<?php echo e(__('Switch to Light Mode')); ?>' : '<?php echo e(__('Switch to Dark Mode')); ?>';
            }
        });
    })();

</script>

<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/interface/home.blade.php ENDPATH**/ ?>