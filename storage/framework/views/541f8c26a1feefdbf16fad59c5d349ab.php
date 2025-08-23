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

  $start      = $this->startDate ? Carbon::parse($this->startDate) : null;
  $end        = $this->endDate ? Carbon::parse($this->endDate) : null;
  $nights     = ($start && $end) ? max(0, $start->diffInDays($end)) : 0;
  $datesReady = ($this->startDate && $this->endDate);
?>

<div class="row g-4 overflow-auto justify-content-lg-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>" style="max-height:80vh;">

  
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm luxury-card">
      <div class="card-body p-0">

        
        <div class="luxury-header p-3 p-md-4 rounded-top-3">
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3">

            <div class="reveal">
              <div class="d-flex align-items-center gap-2">
                <span class="luxury-dot"></span>
                <h5 class="mb-0 fw-semibold">
                  <?php echo e($this->availableRooms->count()); ?>

                  <?php echo e(Str::plural(__('Room'), $this->availableRooms->count())); ?>

                  <?php echo e(__('available')); ?>

                </h5>
              </div>
              <div class="mt-2 d-flex flex-wrap align-items-center gap-2 text-muted small">
                <span class="chip"><i class="bi bi-people me-1"></i><b><?php echo e((int) $this->people); ?></b> <?php echo e(Str::plural(__('person'), (int) $this->people)); ?></span>
                <!--[if BLOCK]><![endif]--><?php if($start && $end): ?>
                  <span class="chip"><i class="bi bi-calendar-range me-1"></i><?php echo e($start->format('d M Y')); ?> → <?php echo e($end->format('d M Y')); ?></span>
                  <span class="chip"><i class="bi bi-moon-stars me-1"></i><?php echo e($nights); ?> <?php echo e(Str::plural(__('night'), $nights)); ?></span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
              </div>
            </div>

            
            <div class="reveal d-flex flex-column flex-sm-row gap-2">
              <div class="d-flex align-items-center gap-2">
                <span class="filter-label text-muted small"><?php echo e(__('Sort by')); ?></span>
                <select class="form-select form-select-sm rounded-pill shadow-none filter-pill" wire:model.live="filterBy">
                  <option value="price"><?php echo e(__('Price')); ?></option>
                  <option value="capacity"><?php echo e(__('Capacity')); ?></option>
                  <option value="name"><?php echo e(__('Number')); ?></option>
                </select>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="filter-label text-muted small"><?php echo e(__('Order')); ?></span>
                <select class="form-select form-select-sm rounded-pill shadow-none filter-pill" wire:model.live="sortOrder">
                  <option value="asc"><?php echo e(__('Ascending')); ?></option>
                  <option value="desc"><?php echo e(__('Descending')); ?></option>
                </select>
              </div>
            </div>

          </div>
        </div>

        
        <div class="p-3 p-md-4 position-relative">

          
          <div class="vstack gap-3" wire:key="rooms-list">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $isSelected = $this->selectedRoom && $this->selectedRoom->id == $room->id;
                $rate   = $this->rateService->getDefaultRate($room->unitType->id);
                $price  = $rate->price ?? 0;
                $lease  = $rate->lease->name ?? __('night');

                // ---------- IMAGE URL: robust resolution + placeholder fallback ----------
                $placeholder = asset('assets/images/default/placeholder.png');
                $imageRef    = $room->unitType->firstImage(); // whatever your method returns

                // Default to placeholder
                $imgSrc = $placeholder;

                if (!empty($imageRef)) {
                    // If already an absolute URL, use as-is
                    if (Str::startsWith($imageRef, ['http://', 'https://', '//'])) {
                        $imgSrc = $imageRef;
                    } else {
                        // Normalize leading slash
                        $normalized = ltrim($imageRef, '/');

                        // If it already points to public/storage, just asset() it
                        if (Str::startsWith($normalized, 'storage/')) {
                            $imgSrc = asset($normalized);
                        } else {
                            // Try the public disk first; fall back to default disk
                            try {
                                $imgSrc = Storage::disk('public')->url($normalized);
                            } catch (\Throwable $e) {
                                $imgSrc = Storage::url($normalized);
                            }
                        }
                    }
                }
                // -------------------------------------------------------------------------
              ?>

              <div class="room-card card border-0 shadow-sm <?php echo e($isSelected ? 'selected' : ''); ?> reveal">
                <div class="card-body p-2 p-md-3">
                  <div class="row g-3 g-lg-4 align-items-stretch">

                    
                    <div class="col-12 col-lg-6">
                      <div class="luxury-media ratio ratio-16x9 rounded-3 overflow-hidden position-relative">
                        <img src="<?php echo e($imgSrc); ?>" alt="<?php echo e($room->name); ?>" class="w-100 h-100 object-fit-cover luxury-img" loading="lazy" style="max-height: 100%;">
                        <div class="media-overlay-top d-flex justify-content-between w-100 px-3 pt-3">
                          <span class="badge badge-pill py-2  bg-[#017E84] text-white" style="height: 25px; background-color: #017E84;">
                            <?php echo e($room->unitType->name); ?>

                          </span>
                          <span class="price-pill">
                            <span class="fw-semibold" style="color: #017E84;"><?php echo e(format_currency($price)); ?></span>
                            <span class="small text-muted">/ <?php echo e($lease); ?></span>
                          </span>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($isSelected): ?>
                          <div class="media-selected-check">
                            <i class="bi bi-check2-circle"></i>
                          </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                      </div>
                    </div>

                    
                    <div class="col-12 col-lg-6 d-flex flex-column">
                      <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                          <h6 class="mb-1 fw-semibold"><?php echo e($room->name); ?></h6>
                          <div class="text-muted small"><?php echo e(__('Unit')); ?> #<?php echo e($room->id); ?></div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($isSelected): ?>
                          <span class="badge selected-badge d-inline-flex align-items-center gap-1">
                            <i class="bi bi-check2"></i><?php echo e(__('Selected')); ?>

                          </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                      </div>

                      <div class="mt-3 d-flex flex-wrap gap-2">
                        <span class="chip soft"><i class="bi bi-people me-1"></i><?php echo e($room->capacity); ?> <?php echo e(__('people')); ?></span>
                        <span class="chip soft"><i class="fa fa-bed me-1"></i><?php echo e($room->beds); ?> <?php echo e(Str::plural(__('bed'), $room->beds)); ?></span>
                        <span class="chip soft"><i class="fa fa-bath me-1"></i><?php echo e($room->bathrooms); ?> <?php echo e(Str::plural(__('bathroom'), $room->bathrooms)); ?></span>
                        <!--[if BLOCK]><![endif]--><?php if($room->unitType->size): ?>
                          <span class="chip soft"><i class="fa fa-ruler-combined me-1"></i><?php echo e($room->unitType->size); ?> <?php echo e(__('sq ft')); ?></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($nights > 0): ?>
                          <span class="chip"><i class="bi bi-moon-stars me-1"></i><?php echo e($nights); ?> <?php echo e(Str::plural(__('night'), $nights)); ?></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                      </div>

                      <!--[if BLOCK]><![endif]--><?php if($room->unitType->description): ?>
                        <p class="text-muted mt-3 mb-0 text-truncate-3"><?php echo e($room->unitType->description); ?></p>
                      <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                      
                      <div class="mt-auto pt-3 d-grid d-sm-flex gap-2">
                        <button
                          class="btn luxury-cta flex-grow-1 <?php echo e($isSelected ? 'btn-success selected-cta' : 'bg-[#017E84]'); ?>"
                          wire:click="pickRoom('<?php echo e($room->id); ?>')"
                          <?php echo e(!$datesReady ? 'disabled' : ''); ?>

                          <?php if($isSelected): ?> disabled <?php endif; ?>
                          wire:loading.attr="disabled"
                          title="<?php echo e(!$datesReady ? __('Select dates to enable') : ($isSelected ? __('Already selected') : __('Choose this room'))); ?>"
                        >
                          <!--[if BLOCK]><![endif]--><?php if($isSelected): ?>
                            <i class="bi bi-check2-circle me-1"></i><?php echo e(__('Selected')); ?>

                          <?php else: ?>
                            <i class="bi bi-door-open me-1"></i><?php echo e(__('Choose')); ?>

                          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($this->availableRooms->count() === 0): ?>
              <div class="text-center py-5 border border-dashed rounded-3 bg-light-subtle reveal">
                <i class="bi bi-door-closed text-secondary" style="font-size:2.25rem;"></i>
                <p class="text-muted mt-2 mb-0"><?php echo e(__('No rooms match your criteria. Try different dates, people count, or sorting.')); ?></p>
              </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
          </div>

          
          <!--[if BLOCK]><![endif]--><?php if($this->availableRooms->count() >= $this->perPage): ?>
            <div class="d-flex justify-content-center mt-4">
              <button
                wire:click="loadMore"
                wire:target="loadMore"
                wire:loading.attr="disabled"
                class="btn btn-outline-primary px-4 luxury-loadmore"
              >
                <span wire:loading.remove wire:target="loadMore">
                  <i class="bi bi-chevron-down me-1"></i><?php echo e(__('Load more')); ?>

                </span>
                <span wire:loading wire:target="loadMore">
                  <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span><?php echo e(__('Loading…')); ?>

                </span>
              </button>
            </div>
          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
      </div>
    </div>
  </div>

  
  <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm luxury-side position-sticky" style="top:1rem;">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-check text-primary"></i> <?php echo e(__('Selected guest')); ?>

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
                class="rounded-3 object-fit-cover"
                style="width:88px;height:88px;"
                loading="lazy"
              >
            </div>
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
  :root{
    --luxury-grad: linear-gradient(135deg, rgba(var(--bs-primary-rgb), .10), rgba(99,102,241,.08) 40%, rgba(16,185,129,.08));
    --luxury-border: rgba(0,0,0,.06);
    --luxury-glow: 0 0 0 .25rem rgba(var(--bs-primary-rgb), .15);
  }

  .luxury-card { overflow: hidden; border-radius: 1rem; }
  .luxury-header {
    background: var(--luxury-grad);
    border-bottom: 1px solid var(--luxury-border);
    backdrop-filter: blur(6px);
  }
  .luxury-dot { width:.6rem;height:.6rem;border-radius:999px;background:var(--bs-primary); box-shadow:0 0 0 .35rem rgba(var(--bs-primary-rgb), .12); }

  .filter-pill { background: rgba(255,255,255,.95); border:1px solid var(--luxury-border); }
  .filter-pill:focus { box-shadow: var(--luxury-glow); }

  .chip {
    border:1px solid var(--luxury-border);
    background: rgba(255,255,255,.9);
    padding:.35rem .6rem; border-radius:999px; display:inline-flex; align-items:center; gap:.25rem;
  }
  .chip.soft { background: rgba(0,0,0,.03); }

  .room-card { border-radius: 1rem; transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease; }
  .room-card:hover { transform: translateY(-2px); box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.06); }
  .room-card.selected { border:2px solid var(--bs-primary) !important; box-shadow: var(--luxury-glow) !important; position:relative; }

  .luxury-media { background: #f6f7fb; }
  .luxury-img { transition: transform .6s ease; will-change: transform; }
  .luxury-media:hover .luxury-img { transform: scale(1.04); }

  .media-overlay-top { position:absolute; top:0; left:0; right:0; pointer-events:none; }
  .luxury-badge { background: rgba(255,255,255,.96); border:1px solid var(--luxury-border); color: #0f172a; font-weight:600; border-radius:999px; padding:.35rem .6rem; }
  .price-pill { background: rgba(var(--bs-primary-rgb), .08); border: 1px solid rgba(var(--bs-primary-rgb), .25); color: var(--bs-primary); border-radius:999px; padding:.35rem .7rem; font-weight:600; }

  .media-selected-check { position:absolute; bottom: .75rem; right: .75rem; background: #16a34a; color:#fff; width:2rem;height:2rem; border-radius:999px; display:flex; align-items:center; justify-content:center; box-shadow:0 .25rem .75rem rgba(22,163,74,.28); }

  .selected-badge { background: rgba(var(--bs-primary-rgb), .12); color: var(--bs-primary); border:1px solid rgba(var(--bs-primary-rgb), .25); border-radius:999px; padding:.25rem .5rem; font-weight:600; }

  .luxury-cta { border-radius: .75rem; }
  .luxury-cta.btn-primary {
    background: linear-gradient(135deg, var(--bs-primary), #6c5ce7);
    border: 0;
  }
  .luxury-cta.btn-primary:hover { filter: brightness(1.04); }
  .luxury-cta.selected-cta { background: #16a34a; border:0; }

  .luxury-side { border-radius: 1rem; }
  .avatar-wrap { position:relative; }
//   .avatar-wrap::after { content:''; position:absolute; inset:-4px; border-radius:1rem; border:1px dashed rgba(0,0,0,.05); pointer-events:none; }

  .text-truncate-3 {
    display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
  }

  /* Reveal animation (subtle) */
  .reveal { animation: slideUp .45s ease both; }
  .reveal:nth-child(2) { animation-delay: .05s; }
  .reveal:nth-child(3) { animation-delay: .1s; }
  @keyframes slideUp {
    from { opacity:0; transform: translateY(6px); }
    to   { opacity:1; transform: translateY(0); }
  }

  @media (prefers-reduced-motion: reduce) {
    .luxury-img, .room-card, .reveal { transition:none !important; animation:none !important; }
  }
</style>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/booking/choose-room.blade.php ENDPATH**/ ?>