<div>
    @section('styles')
        
    <style>
        /* Reset and Base Styles */
        /* * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        } */
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            font-size: 14px;
            line-height: 1.5;
            min-height: 100vh;
            overflow-y: hidden;
            overflow-x: hidden;
        }
        a {
            color: #045054;
            text-decoration: none;
            transition: color 0.2s;
        }
        a:hover {
            color: #033a3f;
        }
        button, label {
            cursor: pointer;
        }

        /* Main Content */
        .main {
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Search Bar */
        .search-bar {
            position: sticky;
            top: 64px;
            background-color: #ffffff;
            padding: 8px;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 12px;
            z-index: 800;
        }
        .search-bar input {
            width: 100%;
            padding: 10px 32px 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #045054;
        }
        .search-bar .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 16px;
        }

        /* Categories */
        .category_section_buttons {
            height: 48px;
            margin-bottom: 12px;
        }
        .section_buttons {
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }
        .section_buttons::-webkit-scrollbar {
            display: none;
        }
        .category_button {
            font-weight: 500;
            height: 100%;
            padding: 8px 16px;
            margin: 0 4px;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 13px;
            color: #4b5563;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s, color 0.2s;
        }
        .category_button:hover {
            background-color: #045054;
            color: #ffffff;
            border-color: #045054;
        }
        .category_button i {
            font-size: 18px;
        }

        /* Product List */
        .product-list {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
            margin-bottom: 16px;
            overflow-y: auto;
            height: auto;
            max-height: 500px;
        }
        .product {
            background-color: #ffffff;
            border: 1px solid #f9fafb;
            border-radius: 6px;
            width: auto;
            max-width: 165px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.2s, border 0.2s;
            cursor: pointer;
        }
        .product:hover {
            transform: scale(1.03);
            border-color: #045054;
        }
        .product img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }
        .product-content {
            padding: 8px;
        }
        .product-name {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .price-tag {
            font-size: 13px;
            font-weight: 700;
            color: #045054;
            margin-top: 4px;
        }
        .badge-info {
            position: absolute;
            top: 8px;
            left: 8px;
            background-color: #e5e7eb;
            color: #1f2937;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .product-information-tag {
            position: absolute;
            top: 8px;
            right: 8px;
            color: #6b7280;
            font-size: 14px;
        }

        /* Checkout (Preserved) */
        .order-container-bg-view {
            height: 170px;
            min-height: auto;
            min-width: auto;
            background-color: #ffffff;
        }
        .order-container-bg-view ul {
            list-style: none;
            padding-left: 0;
        }
        .order-container-bg-view .orderline {
            text-align: left;
            height: auto;
            max-height: 100px;
            padding: 8px 8px 8px 8px;
            text-decoration: none;
            min-height: auto;
            min-width: auto;
            display: list-item;
        }
        .order-container-bg-view::-webkit-scrollbar {
            width: 3px;
        }
        .order-container-bg-view::-webkit-scrollbar-thumb:hover {
            background: #03565b;
        }
        .orderline.selected {
            background-color: #E6F2F3;
        }
        .orderline .product-name {
            text-align: left;
            white-space: nowrap;
            padding: 0 4px 0 0;
            height: 16px;
            min-height: auto;
            min-width: auto;
        }
        .orderline .product-price {
            text-align: right;
            height: 16px;
            width: 44px;
            display: block;
            min-height: auto;
            min-width: auto;
        }
        .price-per-unit {
            height: 17px;
            width: 80%;
            margin-left: 10px;
            display: list-item;
        }
        .order-summary {
            background-color: #F9FAFB;
            height: auto;
            max-height: auto;
            padding: 8px 16px 8px 16px;
            min-height: auto;
            min-width: auto;
        }
        .order-summary .subentry {
            font-size: 14px;
        }
        .empty-cart {
            text-align: center;
            height: 150px;
            background-color: #e7e7e7;
            padding: 16px 24px 16px 24px;
        }
        .empty-cart i {
            font-size: 56px;
            line-height: 56px;
            color: #737373;
            height: 56px;
            width: 52px;
        }
        .empty-cart h3 {
            font-size: 15px;
            font-weight: 500;
            margin: 8px 0 8px 0;
        }
        #cart-body {
            padding: 0;
        }
        .control_buttons {
            font-size: 14px;
            line-height: 21px;
            white-space: nowrap;
            word-spacing: 0px;
            background-color: #d8dadd;
            height: auto;
            border-top: 1px solid #d8dadd;
            border-bottom: 1px solid #d8dadd;
        }
        .control_buttons .k_price_list_button,
        .control_buttons .btn {
            background-color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            line-height: 21px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            width: 32.7%;
            border: 1px solid #e7e9ed;
            padding: 8px;
            margin: 0 1px 2px 1px;
            min-height: auto;
            min-width: auto;
        }
        .control_buttons #reset-cart {
            width: 100%;
        }
        .calculator_buttons {
            font-size: 14px;
            line-height: 21px;
            word-spacing: 0px;
            background-color: #D8DADD;
            height: auto;
            border-top: 1px solid #D8DADD;
            border-bottom: 1px solid #D8DADD;
            min-height: auto;
            min-width: auto;
        }
        #vertical_buttons .btn {
            width: 100%;
        }
        .calculator_buttons .btn {
            font-size: 14px;
            font-weight: 700;
            line-height: 21px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
            height: 65px;
            width: 24.3%;
            border: 1px solid #E7E9ED;
            padding: 8px;
            margin: 0 1px 2px 1px;
            min-height: auto;
            min-width: auto;
        }
        .calculator_buttons .btn.selected {
            background-color: #e0edef;
            border: 1px solid #017E84;
        }
        .calculator_buttons #pay {
            background-color: #03565b;
            color: white;
        }
        .calculator_buttons #pay:hover {
            background-color: #044145;
            color: white;
        }
        .calculator_buttons .k_price_list_button[style*="background-color: #F5D976"] {
            background-color: #F5D976 !important;
        }
        .fixed-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: white;
            margin-top: 30px;
            z-index: 100;
            display: flex;
        }
        .fixed-bar .btn-switch_pane {
            text-align: center;
            vertical-align: middle;
            background-color: #f1f3f4;
            height: 58px;
            width: 50%;
            padding: 5px 10px;
            min-width: auto;
        }
        .fixed-bar .btn-switch_pane:hover {
            background-color: #ffffff;
        }
        .fixed-bar #pay-order {
            background-color: #026d72;
        }
        .fixed-bar #pay-order:hover {
            background-color: #035d62;
        }

        /* Responsive Design */
        @media screen and (max-width: 1024px) {
            .product-list {
                --bs-columns: 4;
            }
        }
        @media screen and (max-width: 768px) {
            .product-list {
                --bs-columns: 3;
            }
            .main {
                padding: 12px;
            }
            .navbar {
                padding: 8px 12px;
            }
            .navbar .navbar-brand img {
                height: 32px;
            }
            .category_section_buttons {
                height: auto;
            }
            .category_button {
                padding: 6px 12px;
                margin: 4px;
            }
        }
        @media screen and (max-width: 507px) {
            .product-list {
                --bs-columns: 2;
            }
            .product-name,
            .price-tag {
                font-size: 12px;
            }
            .main {
                padding: 8px;
            }
        }
        @media screen and (max-width: 380px) {
            .product-name,
            .price-tag {
                font-size: 11px;
            }
        }
        @media (min-width: 990px) {
            .fixed-bar {
                display: none;
            }
        }
    </style>
    @endsection
    @section('content')
    <main class="main">
        <div class="row">
            <!-- Product Section -->
            <section class="col-lg-7 col-md-12" style="height: 100vh;" id="product-box">
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Search products..." aria-label="Search products">
                    <i class="bi bi-search search-icon"></i>
                </div>

                <!-- Categories -->
                <div class="category_section_buttons">
                    <div class="d-flex w-100">
                        <span class="category_button home">
                            <i class="bi bi-house-fill"></i>
                        </span>
                        <div class="d-flex w-100 section_buttons cursor-pointer">
                            <span class="category_button gap-2">
                                <img class="rounded-1" src="{{ asset('assets/images/default/product.png')}}" height="15px"style="height: 35px;" alt="Rooms">
                                <span>Rooms</span>
                            </span>
                            <span class="category_button gap-2">
                                <img class="rounded-1" src="{{ asset('assets/images/default/product.png')}}" height="15px"style="height: 35px;" alt="Dining">
                                Dining
                            </span>
                            <span class="category_button gap-2">
                                <img class="rounded-1" src="{{ asset('assets/images/default/product.png')}}" height="15px"style="height: 35px;" alt="Amenities">
                                Amenities
                            </span>
                            <span class="category_button gap-2">
                                <img class="rounded-1" src="{{ asset('assets/images/default/product.png')}}" height="15px"style="height: 35px;" alt="Services">
                                Services
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="product-list row row-cols-2 row-cols-md-3 row-cols-lg-4 gap-2 p-3">
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">20</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Deluxe Suite">
                        <div class="product-content">
                            <div class="product-name">Deluxe Suite</div>
                            <div class="price-tag">KSh 20,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">50</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Breakfast">
                        <div class="product-content">
                            <div class="product-name">Breakfast</div>
                            <div class="price-tag">KSh 2,500</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">20</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Spa Session">
                        <div class="product-content">
                            <div class="product-name">Spa Session</div>
                            <div class="price-tag">KSh 5,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">15</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Airport Transfer">
                        <div class="product-content">
                            <div class="product-name">Airport Transfer</div>
                            <div class="price-tag">KSh 3,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">8</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Executive Suite">
                        <div class="product-content">
                            <div class="product-name">Executive Suite</div>
                            <div class="price-tag">KSh 25,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">30</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Dinner Package">
                        <div class="product-content">
                            <div class="product-name">Dinner Package</div>
                            <div class="price-tag">KSh 4,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">100</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Pool Access">
                        <div class="product-content">
                            <div class="product-name">Pool Access</div>
                            <div class="price-tag">KSh 1,500</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">40</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Gym Session">
                        <div class="product-content">
                            <div class="product-name">Gym Session</div>
                            <div class="price-tag">KSh 2,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">5</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Conference Room">
                        <div class="product-content">
                            <div class="product-name">Conference Room</div>
                            <div class="price-tag">KSh 10,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">200</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Laundry Service">
                        <div class="product-content">
                            <div class="product-name">Laundry Service</div>
                            <div class="price-tag">KSh 1,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">12</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="City Tour">
                        <div class="product-content">
                            <div class="product-name">City Tour</div>
                            <div class="price-tag">KSh 6,000</div>
                        </div>
                    </article>
                    <article class="product">
                        <div class="product-information-tag">
                            <i class="bi bi-info" aria-label="Product info"></i>
                        </div>
                        <div class="badge badge-info">25</div>
                        <img src="{{ asset('assets/images/default/product.png')}}" class="card-img-top" alt="Bar Package">
                        <div class="product-content">
                            <div class="product-name">Bar Package</div>
                            <div class="price-tag">KSh 3,500</div>
                        </div>
                    </article>
                </div>
            </section>

            <!-- Checkout Section -->
            <section class="col-lg-5 col-md-12 d-none d-lg-block" id="checkout-box">
                <div class="card border-0 shadow-sm">
                    <div class="card-body" id="cart-body">
                        <div class="order-container-bg-view overflow-y-auto flex-grow-1 d-flex flex-column text-start">
                            <ul>
                                <li class="orderline p-2 lh-sm cursor-pointer selected">
                                    <div class="d-flex">
                                        <div class="product-name w-75 d-inline-block flex-grow-1 fw-bolder pe-1 text-truncate">
                                            <span class="text-wrap">Cheese Burger</span>
                                        </div>
                                        <div class="product-price w-25 d-inline-block text-end price fw-bolder">
                                            KSh 25,000
                                        </div>
                                    </div>
                                    <ul>
                                        <li class="price-per-unit">
                                            <em class="qty fst-normal fw-bolder me-1">2 </em> units x KSh 10,000
                                        </li>
                                        <li class="price-per-unit text-muted">
                                            15% discount
                                        </li>
                                    </ul>
                                </li>
                                <li class="orderline p-2 lh-sm cursor-pointer">
                                    <div class="d-flex">
                                        <div class="product-name w-75 d-inline-block flex-grow-1 fw-bolder pe-1 text-truncate">
                                            <span class="text-wrap">Chapati</span>
                                        </div>
                                        <div class="product-price w-25 d-inline-block text-end price fw-bolder">
                                            KSh 15.00
                                        </div>
                                    </div>
                                    <ul>
                                        <li class="price-per-unit">
                                            <em class="qty fst-normal fw-bolder me-1">5 </em> units x KSh 75.00
                                        </li>
                                        <li class="price-per-unit text-muted">
                                            15% discount
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="order-summary w-100 py-2 px-3 bg-100 text-end fw-bolder fs-2 lh-sm">
                            Total: <span class="total">KSh 20,000</span>
                            <div class="text-muted subentry">
                                Taxes: <span class="tax">(+) KSh 540</span>
                            </div>
                        </div>
                        <div class="control_buttons d-flex bg-300 border-bottom flex-wrap">
                            <button class="k_price_list_button btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-tag-fill"></i> Pricelists
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-arrow-counterclockwise"></i> Refund
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-upc"></i> Enter Barcode
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stars"></i> Gifts
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> Customer Note
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-upload"></i> Enregistrer
                            </button>
                            <button class="btn btn-light rounded-0 fw-bolder" id="reset-cart">
                                <i class="bi bi-x"></i> Cancel Order
                            </button>
                        </div>
                        <div class="calculator_buttons d-flex bg-300 border-bottom flex-wrap">
                            <div class="w-25 flex-wrap d-flex" id="vertical_buttons">
                                <button class="btn btn-light rounded-0 fw-bolder h-25">
                                    <i class="bi bi-people"></i> Customer
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder h-75" id="pay">
                                    Pay
                                </button>
                            </div>
                            <div class="w-75 flex-wrap d-flex">
                                <button class="k_price_list_button btn btn-light rounded-0 fw-bolder">
                                    1
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    2
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    3
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder selected">
                                    Qty
                                </button>
                                <button class="k_price_list_button btn btn-light rounded-0 fw-bolder">
                                    4
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    5
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    6
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder" disabled>
                                    <i class="bi bi-percent"></i> Disc
                                </button>
                                <button class="k_price_list_button btn btn-light rounded-0 fw-bolder">
                                    7
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    8
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    9
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder" disabled>
                                    Price
                                </button>
                                <button class="k_price_list_button btn btn-light rounded-0 fw-bolder" style="background-color: #F5D976;">
                                    ÷
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    0
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    ,
                                </button>
                                <button class="btn btn-light rounded-0 fw-bolder">
                                    <i class="bi bi-backspace"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mobile Checkout -->
            <section class="d-lg-none" id="mobile-checkout-box">
                <div class="fixed-bar">
                    <button class="btn-switch_pane rounded-0 fw-bolder text-white review-button" id="pay-order">
                        <span class="fs-1 d-block">Pay</span>
                        <span>KSh 20,000</span>
                    </button>
                    <button class="btn-switch_pane text-black rounded-0 fw-bolder review-button">
                        <span class="fs-1 d-block">Cart</span>
                        <span>2 items</span>
                    </button>
                </div>
            </section>
        </div>
    </main>
    @endsection
</div>
