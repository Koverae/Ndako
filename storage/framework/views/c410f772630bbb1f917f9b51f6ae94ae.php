
<section class="col-lg-5 col-md-12 <?php echo e($tab == 'pay' ? 'd-none d-lg-block' : ''); ?>" id="checkout-box">
  <div class="border-0 shadow-sm card h-screen-d">

    <!-- Header -->
    <div class="co-section-head">
      <h3 class="co-title m-0 d-flex">
        <?php echo e(__('Checkout')); ?>

        <span class="co-subtle ms-2">· <?php echo e(count($cart)); ?> <?php echo e(__('items')); ?></span>
      </h3>
      <div class="co-subtle gap-2">
        <!--[if BLOCK]><![endif]--><?php if($selectedTable): ?>
          <i class="bi bi-geo-alt"></i> <?php echo e($selectedTable->table_name); ?>

        <?php else: ?>
          <i class="bi bi-bag"></i> <?php echo e(__('Direct Sale')); ?>

        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($kdsOverall): ?>
            <span class="kds-pill kds-<?php echo e($kdsOverall); ?> cursor-pointer" wire:click="refreshKdsSummary">
            <!--[if BLOCK]><![endif]--><?php switch($kdsOverall):
                case ('ready'): ?>      <i class="bi bi-check2-circle"></i> <?php echo e(__('Ready')); ?> <?php break; ?>
                <?php case ('queued'): ?>     <i class="bi bi-clock"></i> <?php echo e(__('Queued')); ?> <?php break; ?>
                <?php default: ?>            <i class="bi bi-tools"></i> <?php echo e(__('Preparing')); ?>

            <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
            </span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <span class="kds-counts d-sm-none">
            <span class="kds-chip" title="<?php echo e(__('Queued')); ?>"><i class="bi bi-clock"></i> <?php echo e($kdsSummary['queued']); ?></span>
            <span class="kds-chip" title="<?php echo e(__('Preparing')); ?>"><i class="bi bi-tools"></i> <?php echo e($kdsSummary['preparing']); ?></span>
            <span class="kds-chip" title="<?php echo e(__('Ready')); ?>"><i class="bi bi-check2-circle"></i> <?php echo e($kdsSummary['ready']); ?></span>
        </span>

        <button
          wire:click="cancelOrder('<?php echo e($this->order?->id); ?>')"
          wire:confirm="<?php echo e(__('Are you sure to reset the cart?')); ?>"
          class="btn btn-outline-danger btn-sm rounded-3 fw-semibold d-flex gap-1 <?php echo e(empty($cart) ? 'disabled' : ''); ?>">
          <i class="fas fa-trash"></i> <span><?php echo e(__('Cancel')); ?></span>
        </button>
      </div>
    </div>

    <!-- Actions -->
    <div class="co-actions">
      <button onclick="Livewire.dispatch('openModal', {component: 'pos::modal.service-type-modal'})"
              class="btn btn-sm fw-semibold">
        <!--[if BLOCK]><![endif]--><?php if($selectedService): ?>
          <i class="<?php echo e($selectedService['icon']); ?>"></i> <span><?php echo e($selectedService['label']); ?></span>
        <?php else: ?>
          <i class="bi bi-truck"></i> <?php echo e(__('Service Type')); ?>

        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </button>

      <button class="btn btn-sm fw-semibold"
              wire:click="switchInterface('tables')">
        <i class="fas fa-chair"></i>
        <span><?php echo e($selectedTable->table_name ?? __('Table')); ?></span>
      </button>

      <button class="btn btn-sm fw-semibold"
              onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-modal'})">
        <i class="fas fa-user"></i>
        <span><?php echo e($this->guest ? Str::limit($this->guest->name, 16) : __('Guest')); ?></span>
      </button>

      <button class="btn btn-sm fw-semibold"
              data-bs-toggle="collapse" data-bs-target="#customer-note">
        <i class="bi bi-stickies"></i> <span><?php echo e(__('Customer Note')); ?></span>
        <!--[if BLOCK]><![endif]--><?php if(!empty($orderNote)): ?> <span class="badge text-bg-info ms-1">1</span><?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </button>

      
      <div class="co-toolbar d-none d-sm-flex">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_kitchen')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="sendOrderToKds"><i class="bi bi-send"></i> <?php echo e(__('Send to KDS')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hold_resume_order')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="toggleHold"><i class="bi bi-pause-circle"></i> <?php echo e($onHold ? __('Resume') : __('Hold')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('split_bill')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openSplitBill"><i class="bi bi-scissors"></i> <?php echo e(__('Split')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print_kitchen_ticket')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="printKitchenTicket"><i class="bi bi-printer"></i> <?php echo e(__('KOT')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer_order')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openTransferOrder"><i class="bi bi-arrow-left-right"></i> <?php echo e(__('Transfer')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('move_table')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openMoveTable"><i class="bi bi-arrow-repeat"></i> <?php echo e(__('Move Table')); ?></button>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('merge_bills')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openMergeBills"><i class="bi bi-link-45deg"></i> <?php echo e(__('Merge')); ?></button>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_kitchen')): ?>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="toggleRush"><i class="bi bi-lightning-charge"></i> <?php echo e($rush ? __('Unrush') : __('Rush')); ?></button>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openFireSchedule"><i class="bi bi-alarm"></i> <?php echo e(__('Fire Later')); ?></button>
        <?php endif; ?>
      </div>

      
      <div class="dropdown d-sm-none">
        <button class="btn btn-slim fw-semibold btn-ghost" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-three-dots"></i> <?php echo e(__('More')); ?>

        </button>
        <div class="dropdown-menu dropdown-menu-end co-menu">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_kitchen')): ?>
            <button class="dropdown-item" wire:click="sendOrderToKds"><i class="bi bi-send"></i> <?php echo e(__('Send to KDS')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hold_resume_order')): ?>
            <button class="dropdown-item" wire:click="toggleHold"><i class="bi bi-pause-circle"></i> <?php echo e($onHold ? __('Resume') : __('Hold')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('split_bill')): ?>
            <button class="dropdown-item" wire:click="openSplitBill"><i class="bi bi-scissors"></i> <?php echo e(__('Split')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print_kitchen_ticket')): ?>
            <button class="dropdown-item" wire:click="printKitchenTicket"><i class="bi bi-printer"></i> <?php echo e(__('KOT')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer_order')): ?>
            <button class="dropdown-item" wire:click="openTransferOrder"><i class="bi bi-arrow-left-right"></i> <?php echo e(__('Transfer')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('move_table')): ?>
            <button class="dropdown-item" wire:click="openMoveTable"><i class="bi bi-arrow-repeat"></i> <?php echo e(__('Move Table')); ?></button>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('merge_bills')): ?>
            <button class="dropdown-item" wire:click="openMergeBills"><i class="bi bi-link-45deg"></i> <?php echo e(__('Merge')); ?></button>
          <?php endif; ?>

          <div class="dropdown-divider"></div>

          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_kitchen')): ?>
          <button class="dropdown-item" wire:click="toggleRush"><i class="bi bi-lightning-charge"></i> <?php echo e($rush ? __('Unrush') : __('Rush')); ?></button>
          <button class="dropdown-item" wire:click="openFireSchedule"><i class="bi bi-alarm"></i> <?php echo e(__('Fire Later')); ?></button>
          <?php endif; ?>
        </div>
      </div>

      </div>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('send_to_kitchen')): ?>
      <div class="btn-group btn-group-sm p-2" role="group" aria-label="Courses">
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('starters')"><i class="bi bi-egg-fried"></i> <?php echo e(__('Fire Starters')); ?></button>
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('mains')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fork-knife" viewBox="0 0 16 16">
                <path d="M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z"/>
            </svg> <?php echo e(__('Fire Mains')); ?></button>
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('desserts')"><i class="bi bi-cup-straw"></i> <?php echo e(__('Fire Desserts')); ?></button>
      </div>
      <?php endif; ?>


    <!-- Customer Note -->
    <div id="customer-note" class="collapse soft-panel">
      <div class="p-3">
        <label class="form-label mb-2"><?php echo e(__('Note to kitchen')); ?></label>

        <div class="d-flex flex-wrap gap-1 mb-2">
          <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['No onions','Extra spicy','Allergy: nuts','No salt','On side','Well done']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button type="button" class="note-chip"
                    wire:click="$set('orderNote', trim(($orderNote ?? '') + ' ' + '<?php echo e($q); ?>'))">
              <?php echo e($q); ?>

            </button>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <textarea
          class="form-control"
          rows="3"
          placeholder="<?php echo e(__('No onions, extra spicy, send when table 7 is seated…')); ?>"
          wire:model="orderNote"></textarea>

        <div class="d-flex align-items-center justify-content-between mt-2">
          <small class="text-muted"><?php echo e(__('Visible on KDS')); ?></small>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary"
                    wire:click="$set('orderNote','')">
              <?php echo e(__('Clear')); ?>

            </button>
            <button class="btn btn-sm btn-primary"
                    wire:click="saveOrderNote">
              <?php echo e(__('Save note')); ?>

            </button>
          </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if(session()->has('note_saved')): ?>
          <div class="alert alert-success py-1 px-2 mt-2 mb-0 small"><?php echo e(session('note_saved')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </div>
    </div>

    <!-- Cart -->
    <div class="cart-scroll">
      <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <ul class="m-0 p-0" wire:click="selectProduct('<?php echo e($item['id']); ?>')">
          <li class="orderline <?php echo e($selectedProductId == $item['id'] ? 'selected' : ''); ?>">
            <div class="d-flex justify-content-between align-items-start">
              <div class="pe-2">
                <div class="product-name text-truncate"><?php echo e($item['name']); ?></div>
                <div class="meta mt-1">
                  <em class="qty fw-bold me-1"><?php echo e($item['quantity']); ?></em>
                  × <?php echo e(format_currency($item['unit_price'])); ?>

                  <!--[if BLOCK]><![endif]--><?php if($item['discount'] > 0): ?>
                    · <span class="text-success"><?php echo e($item['discount']); ?>% <?php echo e(__('off')); ?></span>
                  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
              </div>
              <div class="line-total"><?php echo e(format_currency($item['unit_price'] * $item['quantity'])); ?></div>
            </div>
          </li>
        </ul>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-cart d-flex flex-column align-items-center justify-content-center py-5 text-muted">
          <i class="rotate-45 bi bi-cart-fill" style="font-size:62px;" aria-hidden="true"></i>
          <div class="lead mt-2"><?php echo e(__('No items in cart.')); ?></div>
          <div class="small"><?php echo e(__('Add products from the left panel')); ?></div>
        </div>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <!-- Totals & Payment -->
    <div class="checkout-footer">
      <div class="co-totals">
        <div>
          <div class="co-amount">
            <?php echo e(__('Total')); ?>:
            <span class="total"><?php echo e(format_currency(($cartTotal ?? 0))); ?></span>
          </div>
          <div class="text-muted small">
            <?php echo e(__('Taxes')); ?>:
            <span class="tax">(+) <?php echo e(format_currency(($cartTax ?? 0))); ?></span>
          </div>
        </div>
        <div class="d-flex gap-2 w-auto">
          <button
            wire:click="processPayment"
            wire:loading.attr="disabled"
            class="btn btn-primary pay-cta <?php echo e(empty($cart) ? 'disabled' : ''); ?>">
            <i class="bi bi-credit-card-2-front"></i> <?php echo e(__('Payment')); ?>

          </button>
        </div>
      </div>
    </div>

    <!-- Calculator (kept; spacing only) -->
    <div class="calculator_buttons d-flex bg-300 border-top">
      <div class="w-25 d-flex" id="vertical_buttons">
        <button
          wire:click="processPayment"
          wire:loading.attr="disabled"
          class="btn btn-light rounded-0 fw-bolder <?php echo e(empty($cart) ? 'disabled' : ''); ?>"
          id="pay" title="<?php echo e(__('Payment')); ?>">
          <?php echo e(__('Payment')); ?>

        </button>
      </div>

      <div
        x-data="calculatorComponent(window.Livewire.find('<?php echo e($_instance->getId()); ?>'))"
        x-init="
          window.addEventListener('keydown', (e) => {
            const tag=(e.target.tagName||'').toLowerCase();
            const typing = tag==='input'||tag==='textarea'||e.target.isContentEditable;
            if (document.querySelector('.row:not(.d-none)') && !typing && '<?php echo e($interface); ?>'==='register') press(e.key);
          });
        "
        class="w-75 d-flex flex-wrap"
      >
        <template x-for="key in keys" :key="key.label + key.value">
          <button
            type="button"
            @click="press(key.value)"
            :class="['btn','rounded-0','fw-bolder', key.class, (key.mode && $wire.calculatorMode === key.value) ? 'selected' : '']"
            :style="key.style"
          >
            <template x-if="key.icon"><i :class="key.icon" aria-hidden="true"></i></template>
            <template x-if="!key.icon"><span x-text="key.label"></span></template>
          </button>
        </template>
      </div>
    </div>
    <!-- /Calculator -->

  </div>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/partials/pos/checkout.blade.php ENDPATH**/ ?>