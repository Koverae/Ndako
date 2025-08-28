<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['value','data']));

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

foreach (array_filter((['value','data']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
  $carouselId = 'kcrd-'.($this->guest->id ?? 'x');
?>

<style>
/* ---- Scoped polish ---- */
.k_inner_group { --k-gap: 10px; }
.k_horizontal_separator{letter-spacing:.08em;color:#6b7280}
.k_kanban_view{position:relative;background:#fafafa;border:1px solid #eee;border-radius:12px}

/* CAROUSEL */
.k_carousel{position:relative}
.k_kanban_renderer{
  display:flex; gap:12px; padding:10px 6px 12px;
  overflow-x:auto; overscroll-behavior-x:contain;
  scroll-snap-type:x mandatory; -webkit-overflow-scrolling:touch;
  scrollbar-width:none; flex-wrap:nowrap !important;
}
.k_kanban_renderer::-webkit-scrollbar{display:none}
.k_kanban_card{
  background:#fff;border:1px solid #eee;border-radius:14px;padding:12px;
  min-width:clamp(280px,88vw,560px); max-width:560px;
  width:auto; box-sizing:border-box; flex:0 0 auto;
  scroll-snap-align:start; scroll-margin:8px; scroll-snap-stop:always;
}
.k_kanban_card_content{display:flex; align-items:flex-start; gap:12px}
.k_kanban_image{object-fit:cover;border-radius:12px;border:1px solid #e5e7eb}
.k_kanban_details .h2{font-size:1.1rem;margin:0}
.k_kanban_record_title span{font-size:.875rem;color:#6b7280}
.k_room_badge{background:#eef2ff;color:#4338ca;border-radius:999px;padding:2px 10px;font-size:.75rem;display:inline-flex;align-items:center;gap:6px}
.k_meta{font-size:.85rem;color:#4b5563}
.k_meta strong{color:#111827}
.k_hint{font-size:.75rem;color:#6b7280}
.k_inline_help{font-size:.75rem;color:#6b7280;margin-top:4px}
.k_stack{display:flex;flex-direction:column;gap:8px}
.k_dl{display:grid;grid-template-columns:130px 1fr;row-gap:6px;column-gap:12px}
.k_dl dt{color:#6b7280}
.k_dl dd{margin:0;color:#111827}
.k_total{font-weight:700}
.k_sep{height:1px;background:#eee;margin:8px 0}
.k_actions{display:flex;gap:8px;flex-wrap:wrap}
.k_tag{background:#f3f4f6;border:1px solid #e5e7eb;border-radius:10px;padding:4px 10px;font-size:.75rem;color:#374151}

/* Nav + fades */
.k_carousel_nav{position:absolute; inset:0 0 0 0; display:flex; justify-content:space-between; align-items:center; pointer-events:none}
.k_carousel_btn{
  pointer-events:auto; border:none; background:rgba(255,255,255,.95);
  border:1px solid #e5e7eb; width:36px; height:36px; border-radius:999px;
  display:grid; place-items:center; box-shadow:0 2px 10px rgba(0,0,0,.06);
}
.k_carousel_btn[disabled]{opacity:.45; cursor:not-allowed}
.k_edge{position:absolute; top:0; bottom:0; width:60px; pointer-events:none; opacity:0; transition:opacity .2s}
.k_edge--left{left:0; background:linear-gradient(to right, #fafafa, rgba(250,250,250,0))}
.k_edge--right{right:0; background:linear-gradient(to left, #fafafa, rgba(250,250,250,0))}
.k_carousel.has-left .k_edge--left{opacity:1}
.k_carousel.has-right .k_edge--right{opacity:1}

/* Empty states */
.k_empty{display:flex; flex-direction:column; align-items:center; text-align:center; gap:10px; padding:28px}
.k_empty_card{
  background:#fff;border:1px dashed #e5e7eb;border-radius:14px;padding:24px;
  max-width:560px;margin:8px auto;color:#4b5563
}
.k_empty_icon{font-size:2rem; line-height:1}
.k_btn{
  display:inline-flex; align-items:center; gap:8px;
  background:#0E6163; color:#fff; border:1px solid #0E6163;
  border-radius:10px; padding:8px 12px; text-decoration:none; font-weight:600;
}
.k_btn--ghost{background:#fff;color:#0E6163;border-color:#cfe7e8}

/* Small-screen refinement (same design, better proportions) */
@media (max-width: 480px){
  .k_kanban_card{min-width:92vw; padding:10px}
  .k_kanban_card_content{flex-direction:column}
  .k_kanban_image{width:100%; height:140px}
  .k_dl{grid-template-columns:1fr} /* readable stack */
  .k_actions{gap:6px}
}
</style>

<!-- Left Side -->
<div class="k_inner_group col-md-6 col-lg-6">
  <div class="g-col-sm-2">
    <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
      <?php echo e($value->label); ?>

    </div>
  </div>

  <div class="row align-items-start">
    <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
      <?php $bookings = $this->guest->bookings()->isActive()->get(); ?>

      <div class="p-2 k_kanban_view">
        <!--[if BLOCK]><![endif]--><?php if($bookings->count()): ?>
          <div class="k_carousel" data-carousel-root>
            <div id="<?php echo e($carouselId); ?>" class="k_kanban_renderer" tabindex="0" aria-label="Active stays carousel">
              <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-1 k_kanban_card">
                  <div class="k_kanban_card_content">
                    <img class="rounded cursor-pointer k_kanban_image k_image_62_cover" style="height:100px; width:100px" src="<?php echo e(asset('assets/images/default/property.jpeg')); ?>">
                    <div class="k_kanban_details k_stack">
                      <div class="k_kanban_record_title">
                        <div class="gap-2 d-flex align-items-center flex-wrap">
                          <h2 class="h2 m-0"><?php echo e($b->hotel->name ?? 'Hotel'); ?> <i class="bi bi-pencil-square" title="Edit hotel"></i></h2>
                          <span class="k_room_badge"><i class="bi bi-door-closed"></i> <?php echo e($b->room->number ?? '—'); ?></span>
                        </div>
                        <span><?php echo e($b->room->label ?? 'Room'); ?> · <?php echo e($b->room->type ?? 'Type'); ?></span>
                        <span class="mb-1 d-block">Nightly rate: <strong><?php echo e(format_currency($b->rate ?? 5700)); ?></strong></span>
                      </div>

                      <div class="k_meta">
                        <dl class="k_dl">
                          <dt>Check-in</dt><dd><strong><?php echo e(optional($b->check_in)->format('M d, Y') ?? '—'); ?></strong></dd>
                          <dt>Check-out</dt><dd><strong><?php echo e(optional($b->check_out)->format('M d, Y') ?? '—'); ?></strong></dd>
                          <dt>Nights</dt>
                          <dd>
                            <?php $n = ($b->check_in && $b->check_out) ? $b->check_in->diffInDays($b->check_out) : 0; ?>
                            <strong><?php echo e($n); ?></strong> <span class="k_hint">(auto-calculated)</span>
                          </dd>
                          <dt>Amount Due</dt><dd class="k_total"><?php echo e(format_currency($b->amount_due ?? 0)); ?></dd>
                        </dl>

                        <div class="k_sep"></div>
                        <div class="k_actions">
                          <span class="k_tag"><i class="bi bi-person-badge"></i> <?php echo e(ucfirst($b->origin ?? 'walk-in')); ?></span>
                          <span class="k_tag"><i class="bi bi-credit-card"></i> <?php echo e($b->pay_method_label ?? 'On-site payment'); ?></span>
                          <span class="k_tag"><i class="bi bi-clock-history"></i> <?php echo e($b->policy_label ?? 'Flexible checkout'); ?></span>
                        </div>

                        <div class="k_inline_help">Tip: Click the hotel or room badge to edit assignment, rates, or dates.</div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <?php if($bookings->count() > 1): ?>
              <div class="k_carousel_nav" aria-hidden="true">
                <button class="k_carousel_btn" data-dir="prev" title="Previous"><i class="bi bi-chevron-left"></i></button>
                <button class="k_carousel_btn" data-dir="next" title="Next"><i class="bi bi-chevron-right"></i></button>
              </div>
              <div class="k_edge k_edge--left"></div>
              <div class="k_edge k_edge--right"></div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
          </div>
        <?php else: ?>
          <div class="k_empty">
            <div class="k_empty_card">
              <div class="k_empty_icon">🛏️</div>
              <h5 class="m-0">No active stays</h5>
              <p class="m-0">This guest doesn’t have a booking yet. Create one to get started.</p>
              <div class="mt-2" role="group" aria-label="Empty actions">
                <a href="javascript:void(0)" class="k_btn"><i class="bi bi-plus-circle"></i> New Booking</a>
                <a href="javascript:void(0)" class="k_btn k_btn--ghost"><i class="bi bi-door-open"></i> Assign Room</a>
              </div>
            </div>
          </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </div>
    <?php else: ?>
      <div class="p-2 k_kanban_view">
        <div class="k_empty">
          <div class="k_empty_card">
            <div class="k_empty_icon">👤</div>
            <h5 class="m-0">Select a guest</h5>
            <p class="m-0">Pick a guest from the list to view hotel stays and billing.</p>
            <div class="mt-2">
              <a href="javascript:void(0)" class="k_btn"><i class="bi bi-search"></i> Find Guest</a>
              <a href="javascript:void(0)" class="k_btn k_btn--ghost"><i class="bi bi-person-plus"></i> Add Guest</a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function(){
  const easeOutCubic = t => 1 - Math.pow(1 - t, 3);

  function getCards(scroller){ return Array.from(scroller.children).filter(n => n.classList.contains('k_kanban_card')); }
  function getIndexFromScroll(scroller, cards){
    const sl = scroller.scrollLeft, mid = sl + scroller.clientWidth/2;
    let best = 0, bestDist = Infinity;
    cards.forEach((el,i) => {
      const left = el.offsetLeft, right = left + el.offsetWidth, center = (left+right)/2;
      const d = Math.abs(center - mid);
      if(d < bestDist) { bestDist = d; best = i; }
    });
    return best;
  }
  function scrollToIndex(scroller, cards, idx, ms=320){
    idx = Math.max(0, Math.min(idx, cards.length-1));
    const target = cards[idx].offsetLeft - 6; // small padding compensation
    const start = scroller.scrollLeft;
    const dist = target - start;
    if(Math.abs(dist) < 1){ scroller.scrollLeft = target; return Promise.resolve(); }

    let raf, t0;
    return new Promise(resolve => {
      const step = (ts) => {
        if(!t0) t0 = ts;
        const p = Math.min(1, (ts - t0)/ms);
        scroller.scrollLeft = start + dist * easeOutCubic(p);
        if(p < 1){ raf = requestAnimationFrame(step); }
        else { cancelAnimationFrame(raf); resolve(); }
      };
      if(scroller._anim) cancelAnimationFrame(scroller._anim);
      scroller._anim = requestAnimationFrame(step);
    });
  }

  function updateUI(root, scroller){
    const eps = 2;
    const atStart = scroller.scrollLeft <= eps;
    const atEnd = scroller.scrollLeft + scroller.clientWidth >= scroller.scrollWidth - eps;
    root.classList.toggle('has-left', !atStart);
    root.classList.toggle('has-right', !atEnd);
    const prev = root.querySelector('[data-dir="prev"]');
    const next = root.querySelector('[data-dir="next"]');
    if(prev) prev.disabled = atStart;
    if(next) next.disabled = atEnd;
  }

  function wireCarousel(root){
    if(root.dataset.kInit === '1') return; // avoid double-binding
    root.dataset.kInit = '1';

    const scroller = root.querySelector('.k_kanban_renderer');
    if(!scroller) return;
    const cards = getCards(scroller);
    if(!cards.length) return;

    const prev = root.querySelector('[data-dir="prev"]');
    const next = root.querySelector('[data-dir="next"]');

    let idx = getIndexFromScroll(scroller, cards);

    const go = dir => {
      idx = getIndexFromScroll(scroller, cards) + (dir === 'next' ? 1 : -1);
      scrollToIndex(scroller, cards, idx).then(() => updateUI(root, scroller));
    };

    prev && prev.addEventListener('click', () => go('prev'));
    next && next.addEventListener('click', () => go('next'));

    scroller.addEventListener('scroll', () => updateUI(root, scroller), { passive:true });
    window.addEventListener('resize', () => updateUI(root, scroller));

    // keyboard support
    scroller.addEventListener('keydown', (e) => {
      if(e.key === 'ArrowRight'){ e.preventDefault(); go('next'); }
      if(e.key === 'ArrowLeft'){  e.preventDefault(); go('prev'); }
    });

    // initial paint
    requestAnimationFrame(() => updateUI(root, scroller));
  }

  function initAll(){
    document.querySelectorAll('[data-carousel-root]').forEach(wireCarousel);
  }

  document.addEventListener('DOMContentLoaded', initAll);
  window.addEventListener('load', initAll);
  document.addEventListener('livewire:load', initAll);
  document.addEventListener('livewire:navigated', initAll);
  if (window.Livewire && Livewire.hook) {
    try { Livewire.hook('message.processed', initAll); } catch(e){}
  }
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/form/tab/group/special/journey.blade.php ENDPATH**/ ?>