
  <!--[if BLOCK]><![endif]--><?php if($order): ?>
    <div class=" p-2 pos-receipt d-none d-print-block <?php echo e($this->toPrint == 'receipt' ? '' : 'd-print-none'); ?>">
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
        <div><?php echo e(__('Served by')); ?>: <?php echo e($order->cashier->name ?? 'Unknown'); ?></div>
        <div class="receipt-number"><span class="fs-3"><?php echo e($order->receipt_number ?? 'N/A'); ?></span></div>
      </div>

      <!-- Order list -->
      <div class="mt-2 order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
        <ul>
          <!--[if BLOCK]><![endif]--><?php if($order): ?>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <li class="p-2 cursor-pointer orderline lh-sm">
                <div class="d-flex">
                  <div class="gap-2 w-75 d-flex pe-1 text-truncate">
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
      <div class="overflow-y-auto order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
              <div class="w-50 text-end fw-bold"><?php echo e(format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0))); ?></div>
            </div>
          </li>
          <li class="p-2 cursor-pointer orderline lh-sm">
            <div class="d-flex">
              <div class="w-75 pe-1 text-truncate"><?php echo e(__('Payment')); ?></div>
              <div class="w-50 text-end"><?php echo e(format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0))); ?></div>
            </div>
            <ul>
              <li class="mt-1 price-per-unit" style="padding-left: 3px;"><?php echo e(__('Cash')); ?>: <?php echo e(format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0))); ?></li>
              <li class="mt-1 price-per-unit" style="padding-left: 3px;"><?php echo e(__('Card')); ?>: <?php echo e(format_currency(0)); ?></li>
            </ul>
          </li>
        </ul>
      </div>

      <!-- QR + meta -->
      <div class="mt-2 mb-2 text-center pos-receipt-order-data d-flex fs-5">
        <?php echo QrCode::size(100)->generate('https://ndako.koverae.com'); ?>

        <div class="d-block ms-2 text-start">
          <span class="fw-bolder"><?php echo e(__('Need an invoice?')); ?></span>
          <p>Code: <?php echo e($order->receipt_number ?? 'N/A'); ?></p>
        </div>
      </div>

      <div class="mt-2 text-center pos-receipt-order-data d-flex fs-5 flex-column align-items-center">
        <p><?php echo e(__('Powered by ')); ?> <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
        <div><?php echo e(\Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i')); ?></div>
      </div>
    </div>
    <!-- Bill -->
    <div class=" p-2 pos-receipt d-none d-print-block <?php echo e($this->toPrint == 'bill' ? '' : 'd-print-none'); ?>">
      <!-- Logo -->
      <div class="d-flex flex-column justify-content-center align-items-center">
        <img src="<?php echo e(asset('assets/images/logo/ndako.png')); ?>" alt="Ndako Logo" class="pos-receipt-logo">
      </div>

      <!-- Company Info -->
      <div class="d-flex flex-column align-items-center company-info">
        <span><?php echo e(current_company()->address ?? 'Moi Avenue'); ?></span>
        <!--[if BLOCK]><![endif]--><?php if(current_company()->phone): ?>
          <span>Tel: <?php echo e(current_company()->phone); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <div>-------------------------</div>
        <div><?php echo e(__('Served by')); ?>: <?php echo e($order->cashier->name ?? 'Unknown'); ?></div>
        <div class="receipt-number"><?php echo e(__('M-Pesa Till')); ?>: <span class="fs-3">987654</span></div>
      </div>

      <!-- Order list -->
      <div class="mt-2 order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
        <ul>
          <!--[if BLOCK]><![endif]--><?php if($order): ?>
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <li class="p-2 cursor-pointer orderline lh-sm">
                <div class="d-flex">
                  <div class="gap-2 w-75 d-flex pe-1 text-truncate">
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
      <div class="overflow-y-auto order-container-bg-view-receipt flex-grow-1 d-flex flex-column text-start">
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
              <div class="w-50 text-end fw-bold"><?php echo e(format_currency(($order->total_amount ?? 0) + ($cartTax ?? 0))); ?></div>
            </div>
          </li>
        </ul>
      </div>

      <div class="mt-2 text-center pos-receipt-order-data d-flex fs-5 flex-column align-items-center">
        <p><?php echo e(__('Powered by ')); ?> <a href="https://ndako.koverae.com" target="_blank" class="fw-bold">Ndako</a></p>
        <div><?php echo e(\Carbon\Carbon::parse($order->date ?? now())->format('d-m-y H:i')); ?></div>
      </div>
    </div>
  <?php endif; ?><!--[if ENDBLOCK]><![endif]--><?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/partials/pos/receipt.blade.php ENDPATH**/ ?>