<div>
  <div class="modal-content">
    
        <div class="modal-header">
            <h5 class="modal-title"><?php echo e(__("Make Payment for")); ?> #<?php echo e($order->receipt_number); ?></h5>
            <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
        </div>

    <div class="modal-body pt-0">
      
      <div class="row g-2 g-md-3 my-3">
        <div class="col-6">
          <div class="card h-100 cursor-pointer <?php echo e($tab === 'offline' ? 'border-primary shadow-sm' : 'border-light'); ?>"
               role="tab" aria-selected="<?php echo e($tab === 'offline' ? 'true' : 'false'); ?>"
               wire:click="$set('tab','offline')">
            <div class="card-body p-3 text-center">
              <i class="bi bi-cash-coin fs-2 mb-1 text-primary"></i>
              <div class="fw-semibold"><?php echo e(__('Offline')); ?></div>
              <small class="text-muted"><?php echo e(__('Cash, Card, M-Pesa (offline)')); ?></small>
            </div>
          </div>
        </div>
        <div class="col-6">
          <div class="card h-100 cursor-pointer <?php echo e($tab === 'online' ? 'border-success shadow-sm' : 'border-light'); ?>"
               role="tab" aria-selected="<?php echo e($tab === 'online' ? 'true' : 'false'); ?>"
               wire:click="$set('tab','online')">
            <div class="card-body p-3 text-center">
              <i class="bi bi-credit-card-2-front fs-2 mb-1 text-success"></i>
              <div class="fw-semibold"><?php echo e(__('Online')); ?></div>
              <small class="text-muted"><?php echo e(__('Paystack, M-Pesa (STK)')); ?></small>
            </div>
          </div>
        </div>
      </div>

      
      <div aria-live="polite" aria-atomic="true">
        <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <span><?php echo e(session('error')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>"></button>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php if(session()->has('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span><?php echo e(session('success')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>"></button>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </div>

      
      <!--[if BLOCK]><![endif]--><?php if($tab === 'offline'): ?>
        <div class="mb-3">
          <label class="form-label"><?php echo e(__('Payment Method')); ?></label>
          <div class="row g-2">
            <div class="col-4">
              <button type="button"
                      class="btn w-100 <?php echo e($offlineMethod === 'cash' ? 'btn-outline-primary active' : 'btn-outline-secondary'); ?>"
                      wire:click="$set('offlineMethod','cash')">
                <i class="bi bi-cash-stack me-1"></i><?php echo e(__('Cash')); ?>

              </button>
            </div>
            <div class="col-4">
              <button type="button"
                      class="btn w-100 <?php echo e($offlineMethod === 'card' ? 'btn-outline-primary active' : 'btn-outline-secondary'); ?>"
                      wire:click="$set('offlineMethod','card')">
                <i class="bi bi-credit-card me-1"></i><?php echo e(__('Card')); ?>

              </button>
            </div>
            <div class="col-4">
              <button type="button"
                      class="btn w-100 <?php echo e($offlineMethod === 'mpesa' ? 'btn-outline-primary active' : 'btn-outline-secondary'); ?>"
                      wire:click="$set('offlineMethod','mpesa')">
                <img src="<?php echo e(asset('assets/images/third-icons/mpesa.svg')); ?>" alt="" style="height:16px" class="me-1">
                <?php echo e(__('M-Pesa')); ?>

              </button>
            </div>
          </div>
        </div>

        
        <div class="mb-3">
          <label class="form-label">
            <?php echo e(__('Amount')); ?>

            <small class="text-muted">(<?php echo e(__('To pay')); ?>: <?php echo e(format_currency($order->due_amount)); ?>)</small>
          </label>
          <div class="input-group">
            <span class="input-group-text"><?php echo e(settings()->currency->symbol); ?></span>
            <input type="number" step="0.01" min="0" inputmode="decimal"
                   class="form-control"
                   wire:model.live.debounce.300ms="amount"
                   placeholder="0.00"
                   aria-describedby="amount-help">
          </div>
          <div id="amount-help" class="form-text">
            <?php echo e(__('Enter the amount received / to charge.')); ?>

          </div>

          
          <div class="d-flex flex-wrap gap-2 mt-2">
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', <?php echo e($order->due_amount); ?>)">
              <?php echo e(__('Exact')); ?>

            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', <?php echo e(max(0, ceil(($order->due_amount ?? 0)/50)*50)); ?>)">
              <?php echo e(__('Round up')); ?>

            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('amount', <?php echo e(($order->due_amount ?? 0) + 100); ?>)">
              +100
            </button>
          </div>
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if($offlineMethod === 'cash'): ?>
          <?php
            $change = max(0, (float)($amount ?? 0) - (float)($order->due_amount ?? 0));
          ?>
          <div class="mb-3">
            <div class="alert <?php echo e($change > 0 ? 'alert-success' : 'alert-secondary'); ?> py-2 mb-0">
              <div class="d-flex justify-content-between">
                <span class="fw-semibold"><?php echo e(__('Change due')); ?></span>
                <span class="fw-bold"><?php echo e(format_currency($change)); ?></span>
              </div>
            </div>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($offlineMethod === 'card'): ?>
          <div class="mb-3">
            <label class="form-label"><?php echo e(__('POS Ref / Last 4 digits')); ?></label>
            <input type="text" class="form-control" wire:model.lazy="reference" placeholder="e.g. AUTH123, **** 1234">
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <!--[if BLOCK]><![endif]--><?php if($offlineMethod === 'mpesa'): ?>
          <div class="row g-2">
            <div class="col-12 col-md-6">
              <label class="form-label"><?php echo e(__('M-Pesa Code')); ?></label>
              <input type="text" class="form-control" wire:model.lazy="reference" placeholder="e.g. QJK1XY234">
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label"><?php echo e(__('Phone (optional)')); ?></label>
              <input type="tel" class="form-control" wire:model.lazy="msisdn" placeholder="+2547XXXXXXXX">
            </div>
          </div>
          <small class="text-muted d-block mt-1">
            <?php echo e(__('Use this when customer paid on a separate till/phone and shows you the confirmation code.')); ?>

          </small>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        
        <div class="d-grid mt-3">
          <button class="btn btn-primary"
                  wire:click="processOfflinePayment"
                  wire:loading.attr="disabled"
                  wire:target="processOfflinePayment"
                  @keydown.enter.prevent="null">
            <span wire:loading wire:target="processOfflinePayment" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <?php echo e(__('Confirm Payment')); ?>

          </button>
        </div>
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

      
      <!--[if BLOCK]><![endif]--><?php if($tab === 'online'): ?>
        
        <div class="row g-2 g-md-3 mb-3">
          <div class="col-6">
            <button type="button"
                    class="card btn p-0 text-start <?php echo e(($onlineProvider ?? 'paystack') === 'paystack' ? 'border-success shadow-sm' : 'border-light'); ?>"
                    wire:click="$set('onlineProvider','paystack')">
              <div class="card-body d-flex align-items-center gap-2">
                <img src="<?php echo e(asset('assets/images/third-icons/paystack.png')); ?>" alt="Paystack" style="height:20px">
                <div>
                  <div class="fw-semibold"><?php echo e(__('Paystack')); ?></div>
                  <small class="text-muted"><?php echo e(__('Card • Bank • Mobile')); ?></small>
                </div>
              </div>
            </button>
          </div>
          <div class="col-6">
            <button type="button"
                    class="card btn p-0 text-start <?php echo e(($onlineProvider ?? 'paystack') === 'mpesa' ? 'border-success shadow-sm' : 'border-light'); ?>"
                    wire:click="$set('onlineProvider','mpesa')">
              <div class="card-body d-flex align-items-center gap-2">
                <img src="<?php echo e(asset('assets/images/third-icons/mpesa.svg')); ?>" alt="M-Pesa" style="height:20px">
                <div>
                  <div class="fw-semibold"><?php echo e(__('M-Pesa STK')); ?></div>
                  <small class="text-muted"><?php echo e(__('Push to phone')); ?></small>
                </div>
              </div>
            </button>
          </div>
        </div>

        
        <div class="mb-3">
          <label class="form-label">
            <?php echo e(__('Amount')); ?>

            <small class="text-muted">(<?php echo e(__('To pay')); ?>: <?php echo e(format_currency($order->due_amount)); ?>)</small>
          </label>
          <div class="input-group">
            <span class="input-group-text"><?php echo e(settings()->currency->symbol); ?></span>
            <input type="number" step="0.01" min="0" inputmode="decimal"
                   class="form-control"
                   wire:model.live.debounce.300ms="onlineAmount"
                   placeholder="<?php echo e(number_format((float)($order->due_amount ?? 0), 2)); ?>">
          </div>
          <div class="d-flex flex-wrap gap-2 mt-2">
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('onlineAmount', <?php echo e($order->due_amount ?? 0); ?>)">
              <?php echo e(__('Exact')); ?>

            </button>
            <button class="btn btn-sm btn-light border" type="button"
                    wire:click="$set('onlineAmount', <?php echo e(max(0, ceil(($order->due_amount ?? 0)/50)*50)); ?>)">
              <?php echo e(__('Round up')); ?>

            </button>
          </div>
        </div>

        
        <!--[if BLOCK]><![endif]--><?php if(($onlineProvider ?? 'paystack') === 'paystack'): ?>
          <div class="text-center">
            <p class="text-muted mb-3"><?php echo e(__('Click below to pay securely with Paystack')); ?></p>
            <button class="btn btn-primary w-100"
                    wire:click="initiatePaystack"
                    wire:loading.attr="disabled"
                    wire:target="initiatePaystack">
              <span class="d-flex align-items-center justify-content-center gap-2" wire:loading.remove wire:target="initiatePaystack">
                <img src="<?php echo e(asset('assets/images/third-icons/paystack.png')); ?>" style="height:20px" alt="">
                <span><?php echo e(__('Pay with Paystack')); ?></span>
              </span>
              <span wire:loading wire:target="initiatePaystack">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <?php echo e(__('Processing...')); ?>

              </span>
            </button>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php if(($onlineProvider ?? 'paystack') === 'mpesa'): ?>
          <div class="mb-3">
            <label class="form-label"><?php echo e(__('Customer Phone (M-Pesa)')); ?></label>
            <input type="tel" class="form-control" wire:model.lazy="msisdn" placeholder="+2547XXXXXXXX">
            <small class="text-muted"><?php echo e(__('The customer will receive an STK prompt to enter their PIN.')); ?></small>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-success"
                    wire:click="initiateMpesaStk"
                    wire:loading.attr="disabled"
                    wire:target="initiateMpesaStk">
              <span wire:loading wire:target="initiateMpesaStk" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              <?php echo e(__('Send STK Push')); ?>

            </button>

            
            <button class="btn btn-outline-secondary"
                    wire:click="pollMpesaStatus"
                    wire:loading.attr="disabled"
                    wire:target="pollMpesaStatus,initiateMpesaStk">
              <span wire:loading wire:target="pollMpesaStatus" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
              <?php echo e(__('Check Status')); ?>

            </button>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="modal-footer flex-wrap">
      <button class="btn btn-secondary" wire:click="$dispatch('closeModal')"><?php echo e(__('Discard')); ?></button>
    </div>
  </div>

  
      <?php
        $__scriptKey = '3484234628-0';
        ob_start();
    ?>
  <script>
    // Paystack popup callback (unchanged but made resilient)
    $wire.on('openPaystackPopup', url => {
      const width = 600, height = 700;
      const left = (screen.width - width) / 2;
      const top = (screen.height - height) / 2;

      const win = window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);
      const iv = setInterval(() => {
        if (!win || win.closed) {
          clearInterval(iv);
          // You can also emit a Livewire event here if your backend expects it:
          // $wire.dispatch('poPaymentCompleted', { reference: localStorage.getItem('paystack_payment_reference') });
          $wire.dispatch('poPaymentCompleted');
        }
      }, 1000);
    });
  </script>
      <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/modal/payment-modal.blade.php ENDPATH**/ ?>