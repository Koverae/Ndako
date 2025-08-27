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

<div class="row g-3 justify-content-lg-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">
  
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 pb-0">
        <h6 class="mb-1"><?php echo e(__('Stay details')); ?></h6>
        <p class="text-muted small mb-0"><?php echo e(__('Tell us how many people and your dates.')); ?></p>
      </div>

      <div class="card-body">
        <div class="row g-3">

          
          <div class="col-12 col-md-4">
            <label for="people" class="form-label d-flex align-items-center gap-2">
              <?php echo e(__('How many people?')); ?>

              <span class="badge text-bg-light"><?php echo e(__('Required')); ?></span>
            </label>
            <input
              type="number"
              min="1"
              step="1"
              inputmode="numeric"
              class="form-control <?php $__errorArgs = ['people'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
              id="people"
              wire:model="people"
              aria-describedby="peopleHelp"
              placeholder="1"
            >
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['people'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php else: ?>
              <div id="peopleHelp" class="form-text"><?php echo e(__('Minimum 1 person.')); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
          </div>

          
          <div class="col-12 col-md-4">
            <label for="startDate" class="form-label"><?php echo e(__('From')); ?></label>
            <div class="position-relative">
              <i class="bi bi-calendar-event position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input
                type="date"
                class="form-control ps-5 <?php $__errorArgs = ['startDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="startDate"
                min="<?php echo e(now()->toDateString()); ?>"
                wire:model="startDate"
                wire:change="calculatePrice"
              >
              <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startDate'];
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
          </div>

          
          <div class="col-12 col-md-4">
            <label for="endDate" class="form-label"><?php echo e(__('Until')); ?></label>
            <div class="position-relative">
              <i class="bi bi-calendar-check position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input
                type="date"
                class="form-control ps-5 <?php $__errorArgs = ['endDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="endDate"
                min="<?php echo e($this->startDate ?? now()->toDateString()); ?>"
                wire:model="endDate"
                wire:change="calculatePrice"
              >
              <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endDate'];
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
          </div>

          
          <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
              <div class="d-flex align-items-center gap-2">
                <?php
                    $nights = 0;
                    if ($this->startDate && $this->endDate && $this->startDate <= $this->endDate) {
                        $start = \Carbon\Carbon::parse($this->startDate);
                        $end = \Carbon\Carbon::parse($this->endDate);
                        $nights = $start->diffInDays($end);
                    }
                ?>
                <span class="badge text-bg-light border">
                  <i class="bi bi-moon-stars me-1"></i>
                  <?php echo e($nights); ?> <?php echo e(Str::plural('night', (int)($nights ?? 0))); ?>

                </span>

                <!--[if BLOCK]><![endif]--><?php if(($this->startDate && $this->endDate) && ($this->startDate > $this->endDate)): ?>
                  <span class="text-danger small"><?php echo e(__('End date must be after start date.')); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
              </div>

              
              <div class="text-muted small" wire:loading.delay.shortest wire:target="startDate,endDate,people,selectedRoom,calculatePrice">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                <?php echo e(__('Updating…')); ?>

              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  
  <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100 position-relative">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-check text-primary"></i> <?php echo e(__('Selected guest')); ?>

          </h6>
          <span class="badge text-bg-primary-subtle border border-primary-subtle text-primary-emphasis">
            <i class="bi bi-check2-circle me-1"></i><?php echo e(__('Selected')); ?>

          </span>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start gap-3">
            <img
              src="<?php echo e($this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>"
              alt="<?php echo e($this->guest->name); ?>"
              class="rounded-2 object-fit-cover"
              style="width:88px;height:88px;"
              loading="lazy"
            >
            <div class="flex-grow-1">
              <div class="fw-semibold text-truncate"><?php echo e($this->guest->name); ?></div>
              <div class="small text-muted text-truncate"><i class="bi bi-envelope me-1"></i><?php echo e($this->guest->email); ?></div>
              <div class="small text-muted text-truncate"><i class="bi bi-phone me-1"></i><?php echo e($this->guest->phone); ?></div>
              <div class="small text-muted text-truncate"><i class="bi bi-geo me-1"></i><?php echo e($this->guest->street); ?></div>
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
          <a
            href="#"
            class="btn btn-outline-secondary btn-sm"
            onclick="event.preventDefault(); Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: <?php echo e($this->guest->id); ?> })"
          >
            <i class="bi bi-pencil-square me-1"></i><?php echo e(__('Edit')); ?>

          </a>
        </div>
      </div>
    </div>
  <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>


<style>
  .object-fit-cover { object-fit: cover; }
</style>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/booking/view-count.blade.php ENDPATH**/ ?>