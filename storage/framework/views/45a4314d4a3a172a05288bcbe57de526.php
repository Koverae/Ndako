
<section class="d-lg-none" id="mobile-checkout-box" aria-label="<?php echo e(__('Cart and payment actions')); ?>">
  <nav id="m-mobile-switcher" role="tablist">
    <div class="wrap">
      
      <button
        type="button"
        class="btn btn-tab btn-pay <?php echo e($tab === 'pay' ? 'active' : ''); ?> <?php echo e(empty($cart) ? 'disabled' : ''); ?>"
        wire:click="changeTab('pay')"
        <?php if(empty($cart)): ?> disabled aria-disabled="true" <?php endif; ?>
        aria-selected="<?php echo e($tab === 'pay' ? 'true' : 'false'); ?>"
        aria-label="<?php echo e(__('Go to Payment')); ?>"
      >
        <div class="label">
          <div class="title"><?php echo e(__('Pay')); ?></div>
          <div class="sub"><?php echo e(format_currency($cartTotal)); ?></div>
        </div>
        <i class="bi bi-credit-card-2-front icon" aria-hidden="true"></i>
      </button>

      
      <button
        type="button"
        class="btn btn-tab btn-light <?php echo e($tab === 'cart' ? 'active' : ''); ?>"
        wire:click="changeTab('cart')"
        aria-selected="<?php echo e($tab === 'cart' ? 'true' : 'false'); ?>"
        aria-label="<?php echo e(__('Go to Cart')); ?>"
      >
        <div class="label">
          <div class="title"><?php echo e(__('Cart')); ?></div>
          <div class="sub"><?php echo e(count($cart)); ?> <?php echo e(__('items')); ?></div>
        </div>
        <i class="bi bi-bag icon" aria-hidden="true"></i>
      </button>
    </div>
  </nav>

  
  <div class="m-mobile-switcher-spacer"></div>
</section><?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/partials/pos/mobile-checkout-switcher.blade.php ENDPATH**/ ?>