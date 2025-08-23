<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
  use Carbon\Carbon;
  use Illuminate\Support\Str;

  $start        = $this->startDate ? Carbon::parse($this->startDate) : null;
  $end          = $this->endDate ? Carbon::parse($this->endDate) : null;
  $nights       = ($start && $end) ? max(0, $start->diffInDays($end)) : 0;

  $hasRoom      = (bool) $this->selectedRoom;
  $rate         = $hasRoom ? $this->rateService->getDefaultRate($this->selectedRoom->unitType->id) : null;
  $unitPrice    = $rate->price ?? 0;
  $leaseName    = $rate->lease->name ?? __('night');

  $total        = (float) ($this->totalAmount ?? 0);
  $minDue       = (float) ($this->downPaymentDue ?? 0);
  $paid         = (float) ($this->downPayment ?? 0);
  $balance      = max(0, $total - $paid);

  $pctPaid      = $total > 0 ? max(0, min(100, round(($paid / $total) * 100))) : 0;
  $pctMin       = $total > 0 ? max(0, min(100, round(($minDue / $total) * 100))) : 0;

  // Selected room image (robust)
  $placeholder  = asset('assets/images/default/placeholder.png');
  $imgSrc       = $placeholder;
  if ($hasRoom) {
      $ref = $this->selectedRoom->unitType->firstImage();
      if (!empty($ref)) {
          if (Str::startsWith($ref, ['http://','https://','//'])) {
              $imgSrc = $ref;
          } else {
              $norm = ltrim($ref, '/');
              if (Str::startsWith($norm, 'storage/')) {
                  $imgSrc = asset($norm);
              } else {
                  try { $imgSrc = Storage::disk('public')->url($norm); }
                  catch (\Throwable $e) { $imgSrc = Storage::url($norm); }
              }
          }
      }
  }
?>

<div class="<?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">
  <div class="row g-4 justify-content-lg-center">
    
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm luxury-card">
        <div class="card-body p-3 p-md-4">

          
          <div class="luxury-header p-3 p-md-4 rounded-3 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
              <div class="d-flex align-items-center gap-2">
                <span class="luxury-dot"></span>
                <h5 class="mb-0 fw-semibold"><?php echo e(__('Booking summary')); ?></h5>
              </div>
              <div class="d-flex flex-wrap gap-2 text-muted small">
                <!--[if BLOCK]><![endif]--><?php if($start && $end): ?>
                  <span class="chip"><i class="bi bi-calendar-event me-1"></i><?php echo e($start->format('d M Y')); ?> → <?php echo e($end->format('d M Y')); ?></span>
                  <span class="chip"><i class="bi bi-moon-stars me-1"></i><?php echo e($nights); ?> <?php echo e(Str::plural(__('night'), $nights)); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <span class="chip"><i class="bi bi-people me-1"></i><?php echo e((int) $this->people); ?> <?php echo e(Str::plural(__('guest'), (int) $this->people)); ?></span>
                <!--[if BLOCK]><![endif]--><?php if($hasRoom): ?>
                  <span class="chip"><i class="bi bi-door-open me-1"></i><?php echo e($this->selectedRoom->name); ?> · <?php echo e($this->selectedRoom->unitType->name); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
              </div>
            </div>
          </div>

          
          <!--[if BLOCK]><![endif]--><?php if($hasRoom): ?>
            <div class="row g-3 align-items-stretch mb-4">
              <div class="col-12 col-md-5">
                <div class="ratio ratio-16x9 rounded-3 overflow-hidden luxury-media">
                  <img src="<?php echo e($imgSrc); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo e($this->selectedRoom->name); ?>" loading="lazy" onerror="this.onerror=null;this.src='<?php echo e($placeholder); ?>'">
                </div>
              </div>
              <div class="col-12 col-md-7">
                <div class="soft-card h-100 p-3 rounded-3">
                  <h6 class="mb-1 fw-semibold"><i class="fa fa-bed me-2"></i><?php echo e(__('Room details')); ?></h6>
                  <div class="text-muted small mb-2"><?php echo e($this->selectedRoom->name); ?> · <?php echo e($this->selectedRoom->unitType->name); ?></div>
                  <div class="d-flex flex-wrap gap-2">
                    <span class="chip soft"><i class="bi bi-people me-1"></i><?php echo e($this->selectedRoom->unitType->capacity); ?> <?php echo e(__('guests')); ?></span>
                    <span class="chip soft"><i class="bi bi-cash-coin me-1"></i><?php echo e(format_currency($unitPrice)); ?> / <?php echo e($leaseName); ?></span>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

          
          <div class="soft-card p-3 p-md-4 rounded-3 mb-4">
            <div class="d-flex align-items-center gap-2 mb-3">
              <i class="bi bi-calendar2-week text-primary"></i>
              <h6 class="mb-0 fw-semibold"><?php echo e(__('Booking period')); ?></h6>
            </div>
            <div class="row g-3">
              <div class="col-6 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small"><?php echo e(__('Check-in')); ?></div>
                  <div class="mini-value fw-semibold"><?php echo e($start ? $start->format('d M Y') : '—'); ?></div>
                </div>
              </div>
              <div class="col-6 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small"><?php echo e(__('Check-out')); ?></div>
                  <div class="mini-value fw-semibold"><?php echo e($end ? $end->format('d M Y') : '—'); ?></div>
                </div>
              </div>
              <div class="col-12 col-md-4">
                <div class="mini-meta">
                  <div class="mini-label text-muted small"><?php echo e(__('Total days')); ?></div>
                  <div class="mini-value fw-semibold"><?php echo e($nights); ?> <?php echo e(Str::plural(__('day'), $nights)); ?></div>
                </div>
              </div>

              <!--[if BLOCK]><![endif]--><?php if($this->startDate == now()->toDateString()): ?>
                <div class="col-12">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="checkInNow" wire:model="checkedIn">
                    <label for="checkInNow" class="form-check-label"><?php echo e(__('Will the guest check in after booking confirmation?')); ?></label>
                  </div>
                </div>
              <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
          </div>

          
          <!--[if BLOCK]><![endif]--><?php if($hasRoom): ?>
            <div class="soft-card p-3 p-md-4 rounded-3 mb-4">
              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-receipt text-primary"></i>
                <h6 class="mb-0 fw-semibold"><?php echo e(__('Pricing summary')); ?></h6>
              </div>

              <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6">
                  <div class="d-flex justify-content-between small text-muted">
                    <span><?php echo e(__('Minimum down payment')); ?></span>
                    <span><?php echo e(format_currency($minDue)); ?></span>
                  </div>
                  <div class="progress mt-1" style="height:6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo e($pctMin); ?>%;" aria-valuenow="<?php echo e($pctMin); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="d-flex justify-content-between small text-muted">
                    <span><?php echo e(__('Paid (entered)')); ?></span>
                    <span><?php echo e(format_currency($paid)); ?> (<?php echo e($pctPaid); ?>%)</span>
                  </div>
                  <div class="progress mt-1" style="height:6px;">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo e($pctPaid); ?>%;" aria-valuenow="<?php echo e($pctPaid); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="d-flex justify-content-between fw-semibold fs-6">
                    <span><?php echo e(__('Total')); ?></span>
                    <span><?php echo e(format_currency($total)); ?></span>
                  </div>
                  <div class="d-flex justify-content-between text-muted small">
                    <span><?php echo e(__('Balance after payment')); ?></span>
                    <span><?php echo e(format_currency($balance)); ?></span>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

          
          <!--[if BLOCK]><![endif]--><?php if($hasRoom): ?>
            <div class="soft-card p-3 p-md-4 rounded-3">
              <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-credit-card-2-front text-primary"></i>
                <h6 class="mb-0 fw-semibold"><?php echo e(__('Make a payment')); ?></h6>
              </div>

              <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                  <span><?php echo e(session('error')); ?></span>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo e(__('Close')); ?>"></button>
                </div>
              <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

              <div class="row g-3">
                
                <div class="col-12 col-md-6">
                  <label for="paymentMethod" class="form-label"><?php echo e(__('Payment method')); ?></label>
                  <select
                    id="paymentMethod"
                    class="form-select <?php $__errorArgs = ['paymentMethod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    wire:model.live="paymentMethod"
                  >
                    <option value=""><?php echo e(__('Select…')); ?></option>
                    <option value="cash"><?php echo e(__('Cash')); ?></option>
                    <option value="bank"><?php echo e(__('Bank')); ?></option>
                    <option value="m-pesa"><?php echo e(__('M-Pesa')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php if(settings()->has_paystack): ?>
                      <option value="paystack"><?php echo e(__('Paystack (Bank, Mobile Money, …)')); ?></option>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                  </select>
                  <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentMethod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if(in_array($this->paymentMethod, ['bank','m-pesa'])): ?>
                  <div class="col-12 col-md-6">
                    <label for="transactionId" class="form-label"><?php echo e(__('Transaction ID')); ?></label>
                    <input
                      type="text"
                      id="transactionId"
                      class="form-control <?php $__errorArgs = ['transactionId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      placeholder="<?php echo e(__('Enter transaction ID')); ?>"
                      wire:model="transactionId"
                    >
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['transactionId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                      <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                  </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                
                <div class="col-12">
                  <label for="downPayment" class="form-label">
                    <?php echo e(__('Payment amount')); ?>

                    <span class="text-muted">(<?php echo e(__('Minimum')); ?>: <?php echo e(format_currency($minDue)); ?>)</span>
                  </label>
                  <div class="input-group">
                    <input
                      type="number"
                      step="0.01"
                      min="0"
                      max="<?php echo e($total); ?>"
                      id="downPayment"
                      class="form-control <?php $__errorArgs = ['downPayment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      placeholder="<?php echo e(__('Enter payment amount')); ?>"
                      wire:model.live.debounce.300ms="downPayment"
                    >
                    <span class="input-group-text"><?php echo e(__('of')); ?> <?php echo e(format_currency($total)); ?></span>
                  </div>
                  <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['downPayment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                  <!--[if BLOCK]><![endif]--><?php if($paid > 0 && $paid < $minDue): ?>
                    <div class="form-text text-warning"><?php echo e(__('Heads up: This is below the recommended minimum down payment.')); ?></div>
                  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                
                <div class="col-12 d-grid d-sm-flex gap-2 justify-content-sm-end mt-2">
                  <button
                    type="button"
                    wire:click="createBooking"
                    wire:loading.attr="disabled"
                    class="btn btn-primary luxury-cta" style="background-color: #017E84; border-color: #017E84;"
                    <?php if(!$this->paymentMethod || $paid < 0 || $paid > $total): echo 'disabled'; endif; ?>
                  >
                    <span wire:loading.remove wire:target="createBooking">
                      <?php echo e($paid > 0 ? __('Pay & Confirm') : __('Confirm Booking')); ?>

                    </span>
                    <span wire:loading wire:target="createBooking">
                      <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      <?php echo e(__('Processing…')); ?>

                    </span>
                  </button>
                </div>

              </div>
            </div>
          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
      </div>
    </div>

    
    <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
      <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm luxury-side position-sticky" style="top:1rem;">
          <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-person-check text-primary"></i> <?php echo e(__('Guest')); ?>

            </h6>
            <span class="badge selected-badge">
              <i class="bi bi-check2-circle me-1"></i><?php echo e(__('Selected')); ?>

            </span>
          </div>

          <div class="card-body">
            <div class="d-flex align-items-start gap-3">
              <div class="avatar-wrap">
                <img
                    src="<?php echo e($this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>"
                    alt="<?php echo e($this->guest->name); ?>"
                    class="rounded-2 object-fit-cover"
                    style="width:88px;height:88px;"
                    loading="lazy"
                >
              </div>
              <div class="flex-grow-1">
                <div class="fw-semibold text-truncate"><?php echo e($this->guest->name); ?></div>
                <div class="small text-muted text-truncate"><i class="bi bi-envelope me-1"></i><?php echo e($this->guest->email); ?></div>
                <div class="small text-muted text-truncate"><i class="bi bi-phone me-1"></i><?php echo e($this->guest->phone); ?></div>
                <div class="small text-muted text-truncate"><i class="bi bi-geo me-1"></i><?php echo e(__('Qwetu Parklands')); ?></div>
              </div>
            </div>
          </div>

          <?php $activeCount = $this->guest->bookings()->isActive()->count(); ?>
          <div class="card-footer bg-white border-0 d-flex align-items-center justify-content-between">
            <!--[if BLOCK]><![endif]--><?php if($activeCount >= 1): ?>
              <span class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">
                <i class="bi bi-activity me-1"></i><?php echo e(__('Active')); ?>

              </span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <a href="#" class="btn btn-outline-secondary btn-sm"
               onclick="event.preventDefault(); Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: <?php echo e($this->guest->id); ?> })">
              <i class="bi bi-pencil-square me-1"></i><?php echo e(__('Edit')); ?>

            </a>
          </div>
        </div>
      </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
  </div>

      <?php
        $__scriptKey = '4234737171-0';
        ob_start();
    ?>
  <script>
    $wire.on('openPaystackPopup', url => {
      const width = 600, height = 700;
      const left = (screen.width - width) / 2;
      const top  = (screen.height - height) / 2;
      window.open(url, 'Paystack Payment', `width=${width},height=${height},top=${top},left=${left}`);
    });
  </script>
      <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div>


<style>
  :root{
    --luxury-grad: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .10), rgba(99,102,241,.08) 40%, rgba(16,185,129,.08));
    --luxury-border: rgba(0,0,0,.06);
    --luxury-glow: 0 0 0 .25rem rgba(var(--bs-primary-rgb), .15);
  }
  .luxury-card { border-radius: 1rem; }
  .luxury-header { background: var(--luxury-grad); border:1px solid var(--luxury-border); }
  .luxury-dot { width:.6rem;height:.6rem;border-radius:999px;background:var(--bs-primary); box-shadow:0 0 0 .35rem rgba(var(--bs-primary-rgb), .12); }

  .soft-card { background: rgba(255,255,255,.9); border:1px solid var(--luxury-border); }
  .chip { border:1px solid var(--luxury-border); background: rgba(255,255,255,.95); padding:.35rem .6rem; border-radius:999px; display:inline-flex; align-items:center; gap:.35rem; }
  .chip.soft { background: rgba(0,0,0,.03); }

  .luxury-media { background:#f6f7fb; }
  .selected-badge { background: rgba(var(--bs-primary-rgb), .12); color: var(--bs-primary); border:1px solid rgba(var(--bs-primary-rgb), .25); border-radius:999px; padding:.25rem .5rem; font-weight:600; }

  .luxury-cta { border-radius: .7rem; }
  .luxury-side { border-radius: 1rem; }
  .avatar-wrap { position:relative; }
  .avatar-wrap::after { content:''; position:absolute; inset:-4px; border-radius:1rem; border:1px dashed rgba(0,0,0,.05); pointer-events:none; }
</style>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/booking/confirmation.blade.php ENDPATH**/ ?>