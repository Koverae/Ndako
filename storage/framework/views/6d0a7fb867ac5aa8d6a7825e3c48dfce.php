
    <?php $__env->startSection('title', $pos->name); ?>
    <main class="main">
        <div class="row">
            <!-- Product Section -->
            <section class="container-fluid <?php echo e($tab == 'cart' ? 'd-none d-lg-block' : ''); ?> col-lg-7 col-md-12" style="height: 100vh;" id="product-box">
                <!-- Search Bar -->
                <div class="search-bar">
                    <input type="text" class="form-control" placeholder="Search products..." aria-label="Search products">
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
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder preset">
                                <i class="fas fa-utensils"></i> <span>Eat In</span>
                            </button>

                            <button class="gap-3 btn btn-light rounded-0 fw-bolder" disabled style="background-color: #B7EDBE;">
                                <i class="fas fa-chair"></i> <span>T1</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Customer Note</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder">
                                <i class="bi bi-stickies"></i> <span>Note</span>
                            </button>

                            <button class="gap-2 btn btn-light rounded-0 fw-bolder" id="reset-cart">
                                <i class="fas fa-trash"></i> <span>Cancel Order</span>
                            </button>
                            <button class="gap-2 btn btn-light rounded-0 fw-bolder" id="reset-cart">
                                <i class="fas fa-user"></i> <span>Guest</span>
                            </button>

                        </div>

                        <!-- Calculator -->
                        <div class="flex-wrap calculator_buttons d-flex bg-300 border-bottom">
                            <div class="flex-wrap w-25 d-flex" id="vertical_buttons">
                                <button class="btn btn-light rounded-0 fw-bolder" id="pay">
                                    Pay <?php echo e($calculatorInput); ?> <?php echo e($calculatorMode); ?>

                                </button>
                            </div>
                            
                            <div x-data="calculatorComponent(window.Livewire.find('<?php echo e($_instance->getId()); ?>'))"
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
            { label: '', value: 'backspace', icon: 'bi bi-backspace', style: 'background-color: #FAA0A0;' },
        ],
        press(value) {
            if (['qty', 'discount', 'price'].includes(value)) {
                $wire.selectCalculatorMode(value); // Now $wire is defined
                return;
            }

            if (value === 'backspace') {
                this.input = this.input.slice(0, -1);
            } else {
                this.input += value;
            }

            // Optional: send to Livewire if needed
            $wire.set('calculatorInput', this.input);
        }
    };
}
</script>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/interface/home.blade.php ENDPATH**/ ?>