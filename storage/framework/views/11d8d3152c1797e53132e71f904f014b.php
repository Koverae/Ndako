<div class="p-4 bg-white rounded-3 shadow-sm calendar-container" wire:key="calendar-shell">
  <style>
    :root{
      --k-primary:#017e84;
      --k-primary-10:rgba(1,126,132,.1);
      --k-ok:#017e84;
      --k-warn:#fbc02d;
      --k-info:#1e88e5;
      --k-danger:#e53935;
      --k-fallback:#757575;

      --k-bg:#ffffff;
      --k-muted:#6b7280;
      --k-text:#111827;
      --k-border:#e5e7eb;
      --k-soft:#f7fafc;
    }

    /* Toolbar */
    .k-toolbar{
      display:flex; gap:.75rem; align-items:center; justify-content:space-between; flex-wrap:wrap;
      margin-bottom:.75rem;
    }
    .k-toolbar-left, .k-toolbar-right{ display:flex; gap:.5rem; align-items:center; flex-wrap:wrap }
    .k-chip{
      display:inline-flex; align-items:center; gap:.35rem;
      border:1px solid var(--k-border); background:#fff; color:var(--k-text);
      padding:.5rem .65rem; border-radius:.75rem; font-size:.875rem; cursor:pointer;
      transition: all .15s ease;
    }
    .k-chip:hover{ border-color: var(--k-primary); box-shadow: 0 0 0 3px var(--k-primary-10); }
    .k-chip.active{ background:var(--k-primary); color:#fff; border-color:var(--k-primary) }

    .k-search{ position:relative; min-width:220px }
    .k-search input{
      padding-left:2.25rem; border-radius:.75rem; border:1px solid var(--k-border); height:40px;
    }
    .k-search i{ position:absolute; top:50%; left:.65rem; transform: translateY(-50%); color:var(--k-muted) }

    /* Legend (also a filter) */
    .k-legend{ display:flex; flex-wrap:wrap; gap:.5rem; align-items:center }
    .k-legend .item{
      display:flex; align-items:center; gap:.45rem; padding:.25rem .5rem; border-radius:.5rem;
      background:#f8fafc; border:1px solid var(--k-border); cursor:pointer; user-select:none;
      transition: all .15s ease; font-size:.85rem;
    }
    .k-legend .swatch{ width:14px; height:14px; border-radius:4px }
    .k-legend .item.active{ background:#eefaf9; border-color:var(--k-primary) }

    /* Filters (properties + floors) */
    .filter-section{ margin-bottom:.6rem }
    .filter-pills{ display:flex; gap:.5rem; flex-wrap:wrap }
    .filter-pills .pill{
      border:1px solid var(--k-border); border-radius:.75rem; padding:.45rem .8rem; background:#fff; cursor:pointer;
      transition: all .15s ease; min-width: 88px; text-align:center;
    }
    .filter-pills .pill:hover{ border-color:var(--k-primary) }
    .filter-pills .pill.selected{
      background:var(--k-primary); border-color:var(--k-primary); color:#fff;
      box-shadow: 0 6px 14px rgba(1,126,132,.15);
    }

    /* ROOMS — enhanced cards */
    .rooms-section{ margin-bottom: .75rem }
    .rooms-header{ display:flex; align-items:center; justify-content:space-between; gap:.5rem; flex-wrap:wrap; margin-bottom:.5rem }
    .rooms-header h3{ font-size:1.05rem; margin:0 }
    .rooms-header-actions{ display:flex; gap:.5rem; align-items:center }
    .btn-ghost{
      background:#f3f4f6; border:1px solid var(--k-border); color:#374151; border-radius:.65rem; padding:.45rem .75rem;
    }
    .btn-ghost:hover{ background:#eef1f4 }

    .rooms-container{ position:relative }
    .rooms-scroll{
      display:flex; gap:.75rem; overflow-x:auto; padding-bottom:.25rem; scroll-snap-type:x mandatory;
      scrollbar-width:thin; scrollbar-color:#d1d5db #f3f4f6;
    }
    .rooms-scroll.is-dragging{ cursor:grabbing }

    .k-rc{ all:unset; display:block; cursor:pointer; }
    .room-card.k-rc{
      position:relative;
      scroll-snap-align:start; flex:0 0 18rem;
      background:linear-gradient(45deg,#fafafa 25%,transparent 25%,transparent 50%,#fafafa 50%,#fafafa 75%,transparent 75%,transparent);
      background-size:4px 4px; border:1px solid var(--k-border); border-radius:.85rem; padding:.85rem;
      transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease, background .15s ease;
    }
    .room-card.k-rc:hover{ transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0,0,0,.08) }
    .room-card.k-rc.selected{ border:2px solid var(--k-primary); background:#fff }
    .k-rc-top{ display:flex; align-items:center; justify-content:space-between; gap:.5rem }
    .k-rc-title{ display:flex; align-items:center; gap:.45rem; min-width:0 }
    .k-rc-title h4{ margin:0; font-size:.98rem; font-weight:700; letter-spacing:.2px; max-width:12rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis }
    .k-rc-title .bi{ opacity:.8 }
    .k-chip-status{
      display:inline-flex; align-items:center; gap:.4rem; padding:.2rem .55rem; border-radius:999px;
      font-size:.72rem; font-weight:700; letter-spacing:.2px; text-transform:uppercase;
      border:1px solid var(--k-border); background:#fff; color:#374151;
    }
    .k-chip-status.vacant{ background: #ecfdf5; border-color:#d1fae5; color:#065f46 }
    .k-chip-status.occupied{ background: #fef2f2; border-color:#fee2e2; color:#991b1b }
    .k-rc-meta{ margin:.5rem 0 .35rem; display:flex; gap:.4rem; flex-wrap:wrap }
    .k-tag{
      display:inline-flex; align-items:center; gap:.35rem;
      background:#f8fafc; border:1px solid var(--k-border); color:#4b5563;
      padding:.25rem .5rem; border-radius:.5rem; font-size:.78rem;
    }
    .k-rc-bar{ display:flex; align-items:center; gap:.5rem; margin-top:.35rem }
    .k-rc-bar .dot{ width:.6rem; height:.6rem; border-radius:50% }
    .k-rc-bar .dot.vacant{ background: var(--k-ok) }
    .k-rc-bar .dot.occupied{ background: var(--k-danger) }
    .k-rc-label{ font-size:.8rem; font-weight:600; color:#374151 }
    .k-rc-chevron{ opacity:.35; transition:transform .15s ease, opacity .15s ease }
    .room-card.k-rc:hover .k-rc-chevron{ opacity:.6; transform: translateX(2px) }

    .gradient-overlay-left,.gradient-overlay-right{ position:absolute; top:0; width:1.25rem; height:100%; pointer-events:none }
    .gradient-overlay-left{ left:0; background:linear-gradient(to right,#fff,transparent) }
    .gradient-overlay-right{ right:0; background:linear-gradient(to left,#fff,transparent) }

    /* FullCalendar theming & layout improvements */
    .fc .fc-toolbar-title{ font-weight:700; letter-spacing:.2px }
    .fc .fc-button{
      padding:.45rem .75rem; border-radius:.65rem; border:1px solid var(--k-border);
      background:#fff; color:#111827; transition: all .15s ease;
    }
    .fc .fc-button:hover{ background: var(--k-primary); color:#fff; border-color: var(--k-primary) }
    .fc .fc-daygrid-event{ border-radius:.6rem }
    .fc .fc-day-today { background: rgba(1,126,132,.06) !important; }
    .fc .fc-col-header-cell-cushion{ padding:.5rem 0 }
    .fc .fc-daygrid-day-number{ font-weight:600 }

    /* Event card – left accent + tidy internals */
    .fc-event-custom{
      position:relative;
      background-color: var(--status-color, var(--k-fallback)) !important;
      border:none !important; border-radius:.66rem; padding:.6rem .6rem .5rem .75rem; margin: 2px 0;
      box-shadow: 0 2px 4px rgba(0,0,0,0.08); transition: transform .12s ease, box-shadow .12s ease, background .12s ease;
      cursor:pointer; color:#fff; font-size:.86rem; line-height:1.25;
    }
    .fc-event-custom::before{
      content:""; position:absolute; left:0; top:0; bottom:0; width:4px; border-radius:.66rem 0 0 .66rem;
      background: rgba(255,255,255,.9);
      opacity:.85;
    }
    .fc-event-custom:hover{
      transform: translateY(-1px);
      box-shadow:0 6px 12px rgba(0,0,0,.12);
      border:2px solid transparent;
      background: linear-gradient(var(--status-color), var(--status-color)) padding-box, linear-gradient(45deg, #0E6163, #3aa8aa) border-box;
    }
    .k-ev{ display:flex; flex-direction:column; gap:.25rem }
    .k-ev-hd{ display:flex; align-items:center; justify-content:space-between; gap:.5rem }
    .k-ev-ref{ font-weight:800; letter-spacing:.2px; max-width: 14rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-shadow: 0 1px 1px rgba(0,0,0,.25) }
    .k-ev-chip{
      background:#fff; color:#111827; font-size:.7rem; font-weight:700; padding:2px 6px; border-radius:999px;
      display:inline-flex; align-items:center; gap:.35rem;
    }
    .k-ev-row{ display:flex; align-items:center; gap:.5rem; opacity:.95; white-space:nowrap; overflow:hidden; text-overflow:ellipsis }
    .k-ev-row i{ opacity:.9 }
    .k-ev-ft{ display:flex; align-items:center; justify-content:space-between; gap:.5rem; margin-top:.1rem }
    .k-ev-actions{ display:flex; align-items:center; gap:.5rem }
    .k-ev-actions i{ opacity:.9 } .k-ev-actions i:hover{ opacity:1 }

    /* Tooltip */
    .calendar-tooltip{
      position:fixed; background:#fff; border:1px solid var(--k-border); padding:.6rem .7rem; border-radius:.65rem;
      box-shadow:0 12px 28px rgba(0,0,0,.15); z-index: 1000; max-width:340px; font-size:.86rem; color:#374151;
    }
    .calendar-tooltip .line{ display:flex; gap:.5rem; margin:.175rem 0 }
    .calendar-tooltip .key{ width:74px; color:#6b7280; font-weight:600 }

    /* Loading overlay */
    .k-loading{
      position:absolute; inset:0; display:none; align-items:center; justify-content:center;
      background: rgba(255,255,255,.65); backdrop-filter: blur(1px); z-index: 10;
    }
    .k-loading.show{ display:flex }
    .k-spinner{ width:22px;height:22px;border-radius:50%; border:3px solid #e5e7eb;border-top-color:var(--k-primary); animation:spin .7s linear infinite }
    @keyframes spin{ to{ transform: rotate(360deg) } }

    @media (max-width:640px){
      .rooms-scroll{ gap:.6rem }
      .room-card.k-rc{ flex:0 0 16rem }
      .k-search{ min-width: 180px }
      .k-ev-ref{ max-width: 11rem }
    }
  </style>

  
  <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ['success'=>'alert-success','error'=>'alert-danger','warning'=>'alert-warning']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!--[if BLOCK]><![endif]--><?php if(session()->has($key)): ?>
      <div class="alert <?php echo e($class); ?> d-flex align-items-center justify-content-between mb-3" role="alert">
        <span class="fw-semibold"><?php echo e(session($key)); ?></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

  
  <div class="k-toolbar">
    <div class="k-toolbar-left">
      <div class="k-search">
        <i class="bi bi-search"></i>
        <input id="kSearch" type="search" class="form-control" placeholder="<?php echo e(__('Search guest, reference, room…')); ?>">
      </div>

      
      <div class="k-legend" id="kLegend">
        <div class="item active" data-status="pending">
          <span class="swatch" style="background:var(--k-warn)"></span><span><?php echo e(__('Pending')); ?></span>
        </div>
        <div class="item active" data-status="confirmed">
          <span class="swatch" style="background:var(--k-ok)"></span><span><?php echo e(__('Confirmed')); ?></span>
        </div>
        <div class="item active" data-status="completed">
          <span class="swatch" style="background:var(--k-info)"></span><span><?php echo e(__('Completed')); ?></span>
        </div>
        <div class="item active" data-status="canceled">
          <span class="swatch" style="background:var(--k-danger)"></span><span><?php echo e(__('Canceled')); ?></span>
        </div>
        <div class="item active" data-status="fallback">
          <span class="swatch" style="background:var(--k-fallback)"></span><span><?php echo e(__('Fallback')); ?></span>
        </div>
      </div>
    </div>

    <div class="k-toolbar-right">
      <button id="kToday" class="k-chip"><i class="bi bi-calendar-event"></i><?php echo e(__('Today')); ?></button>
      <button id="kNext7" class="k-chip"><i class="bi bi-arrow-right-circle"></i><?php echo e(__('Next 7 days')); ?></button>
      <button id="kDensity" class="k-chip" data-mode="cozy"><i class="bi bi-aspect-ratio"></i><span><?php echo e(__('Compact')); ?></span></button>
    </div>
  </div>

  
  <div class="filter-section">
    <div class="filter-pills">
      <button wire:click="selectProperty('')" class="pill <?php echo e(!$selectedProperty ? 'selected' : ''); ?>"><?php echo e(__('All Properties')); ?></button>
      <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ($properties ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button wire:click="selectProperty('<?php echo e($property->id); ?>')" class="pill <?php echo e((string)$selectedProperty === (string)$property->id ? 'selected' : ''); ?>">
          <?php echo e($property->name); ?>

        </button>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
  </div>

  
  <div class="filter-section">
    <div class="filter-pills">
      <button wire:click="selectFloor('')" class="pill <?php echo e(!$selectedFloor ? 'selected' : ''); ?>"><?php echo e(__('All Floors')); ?></button>
      <!--[if BLOCK]><![endif]--><?php $__currentLoopData = ($floors ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button wire:click="selectFloor('<?php echo e($floor->id); ?>')" class="pill <?php echo e((string)$selectedFloor === (string)$floor->id ? 'selected' : ''); ?>">
          <?php echo e($floor->name); ?>

        </button>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </div>
  </div>

  
  <div class="rooms-section">
    <div class="rooms-header">
      <h3 class="fw-semibold"><?php echo e(__('Rooms')); ?></h3>
      <div class="rooms-header-actions">
        <!--[if BLOCK]><![endif]--><?php if($selectedUnit || $selectedFloor || $selectedProperty): ?>
          <button wire:click="clearUnitFilter" class="btn-ghost"><?php echo e(__('Clear filter')); ?></button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('role', 'front-desk')): ?>
          <!--[if BLOCK]><![endif]--><?php if(($units ?? collect())->isNotEmpty()): ?>
            <?php $startDate = now(); $endDate = now()->addDay(); ?>
            <button
              onclick="Livewire.dispatch('openModal', {
                component: 'channelmanager::modal.add-booking-modal',
                arguments: { startDate: '<?php echo e($startDate->toISOString()); ?>', endDate: '<?php echo e($endDate->toISOString()); ?>' }
              })"
              class="btn btn-primary"
              style="border-radius:.65rem;"
            >
              <i class="bi bi-plus-lg me-1"></i><?php echo e(__('New Booking')); ?>

            </button>
          <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </div>
    </div>

    <div class="rooms-container">
      <div class="rooms-scroll" id="roomsScroll" role="listbox" aria-label="<?php echo e(__('Rooms list')); ?>">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = ($units ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
            $isSelected = (string)$selectedUnit === (string)$unit->id;
            $isVacant = $unit->status === 'vacant';
          ?>

          <button
            type="button"
            wire:key="unit-<?php echo e($unit->id); ?>"
            wire:click="selectUnit(<?php echo e($unit->id); ?>)"
            class="room-card k-rc <?php echo e($isSelected ? 'selected' : ''); ?>"
            role="option"
            aria-selected="<?php echo e($isSelected ? 'true' : 'false'); ?>"
            title="<?php echo e($unit->name); ?> • <?php echo e($unit->unitType->name ?? 'N/A'); ?> • <?php echo e(inverseSlug($unit->status)); ?>"
          >
            <div class="k-rc-top">
              <div class="k-rc-title">
                <i class="bi bi-door-open"></i>
                <h4><?php echo e($unit->name); ?></h4>
              </div>
              <span class="k-chip-status <?php echo e($isVacant ? 'vacant' : 'occupied'); ?>">
                <?php echo e($isVacant ? __('Vacant') : __('Occupied')); ?>

              </span>
            </div>

            <div class="k-rc-meta">
              <span class="k-tag"><i class="bi bi-tag"></i><?php echo e($unit->unitType->name ?? 'N/A'); ?></span>
              <span class="k-tag"><i class="bi bi-people"></i><?php echo e($unit->capacity ?? '—'); ?></span>
              <!--[if BLOCK]><![endif]--><?php if(isset($unit->floor->name)): ?>
                <span class="k-tag"><i class="bi bi-layers"></i><?php echo e($unit->floor->name); ?></span>
              <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="k-rc-bar">
              <span class="dot <?php echo e($isVacant ? 'vacant' : 'occupied'); ?>"></span>
              <span class="k-rc-label"><?php echo e(inverseSlug($unit->status)); ?></span>
              <i class="bi bi-chevron-right ms-auto k-rc-chevron" aria-hidden="true"></i>
            </div>
          </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <p class="text-muted small m-0"><?php echo e(__('No rooms available.')); ?></p>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
      </div>

      <div class="gradient-overlay-left"></div>
      <div class="gradient-overlay-right"></div>
    </div>
  </div>

  
  <div class="position-relative">
    <div class="k-loading" id="kLoading"><div class="k-spinner" aria-hidden="true"></div></div>
    <div id="calendar" class="rounded-3" style="min-height:520px;" wire:ignore></div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  // ---- Config & State ------------------------------------------------------
  const STATUS_COLORS = {
    pending:   '#fbc02d',
    confirmed: '#017e84',
    completed: '#1e88e5',
    canceled:  '#e53935',
    fallback:  '#757575'
  };

  const state = {
    filters: {
      statuses: new Set(['pending','confirmed','completed','canceled','fallback']),
      search: ''
    },
    density: 'cozy' // 'cozy' | 'compact'
  };

  const showLoading = (on=true)=> {
    const el = document.getElementById('kLoading');
    if(!el) return; el.classList.toggle('show', !!on);
  };

  // Debounce helper
  const debounce = (fn, ms=250) => { let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); }; };

  // Keep one calendar instance globally
  window.ndakoCalendar = window.ndakoCalendar || null;

  document.addEventListener('DOMContentLoaded', () => {
    if (!window.ndakoCalendar) {
      initializeCalendar(<?php echo json_encode($events ?? [], 15, 512) ?>);
    } else {
      window.ndakoCalendar.updateSize();
      refilterCalendar(); // re-apply filters if user navigated back
    }
    wireToolbar();
    wireRoomsScroll();
  });

  // Livewire v2 hook
  if (window.Livewire && Livewire.hook) {
    try {
      Livewire.hook('message.processed', () => {
        if (window.ndakoCalendar && document.getElementById('calendar')) {
          window.ndakoCalendar.updateSize();
          refilterCalendar();
        }
      });
    } catch (e) {}
  }
  // Livewire v3 hook (fallback)
  document.addEventListener('livewire:navigated', () => {
    if (window.ndakoCalendar && document.getElementById('calendar')) {
      window.ndakoCalendar.updateSize();
      refilterCalendar();
    }
  });

  // ---- Calendar init -------------------------------------------------------
  function initializeCalendar(eventsRaw){
    const calendarEl = document.getElementById('calendar');
    if(!calendarEl) return;

    // If already exists, just feed events
    if (window.ndakoCalendar) {
      onCalendarUpdated(eventsRaw);
      return;
    }

    const eventsData = (eventsRaw || []).map(e => ({ ...e, displayEnd: addOneDay(e.end) }));

    const cal = new FullCalendar.Calendar(calendarEl, {
      initialView: '<?php echo e($options['initialView']); ?>',
      editable: <?php echo e($options['editable'] ? 'true' : 'false'); ?>,
      selectable: <?php echo e($options['selectable'] ? 'true' : 'false'); ?>,
      nowIndicator: true,
      stickyHeaderDates: true,
      displayEventTime: false,
      dayMaxEventRows: 4,
      expandRows: true,
      timeZone: 'local',
      eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },
      firstDay: 1,
      weekNumbers: false,
      // nicer scrolling in week/day
      slotMinTime: '00:00:00',
      slotMaxTime: '24:00:00',

      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },

      select: (info) => {
        Livewire.dispatch('openModal', {
          component: 'channelmanager::modal.add-booking-modal',
          arguments: { startDate: info.startStr, endDate: info.endStr }
        });
      },

      events: eventsData.map(e => ({ ...e, end: e.displayEnd })),

      eventDidMount: (info) => {
        // a11y title
        const ep = info.event.extendedProps || {};
        info.el.title = `${ep.reference || ''} — ${ep.guest || ''}`;

        // density tweaks (applied dynamically on toggle too)
        if(state.density === 'compact') {
          info.el.style.padding = '6px';
          info.el.style.fontSize = '0.8rem';
        }
      },

      eventDrop: (info) => {
        const newStart = info.event.start?.toISOString() ?? null;
        const newEnd = subtractOneDay(info.event.end?.toISOString());
        Livewire.dispatch('updateBookingDate', {
          bookingId: info.event.id, start: newStart, end: newEnd
        });
      },

      eventResize: (info) => {
        const newStart = info.event.start?.toISOString() ?? null;
        const newEnd = subtractOneDay(info.event.end?.toISOString());
        Livewire.dispatch('updateBookingDate', {
          bookingId: info.event.id, start: newStart, end: newEnd
        });
      },

      eventMouseEnter: (info) => {
        const e = info.event, ep = e.extendedProps || {};
        const tip = document.createElement('div');
        tip.className = 'calendar-tooltip';
        tip.innerHTML = `
          <div class="fw-semibold mb-1">${safe(ep.reference)}</div>
          <div class="line"><span class="key"><?php echo e(__('Guest')); ?></span><span>${safe(ep.guest)}</span></div>
          <div class="line"><span class="key"><?php echo e(__('Room')); ?></span><span>${safe(ep.room)} – ${safe(ep.unitType)}</span></div>
          <div class="line"><span class="key"><?php echo e(__('Stay')); ?></span><span>${fmt(e.start)} ~ ${fmt(subtractOneDay(e.end))}</span></div>
          <div class="line"><span class="key"><?php echo e(__('Status')); ?></span><span>${safe(ep.status)}</span></div>
          <div class="line"><span class="key"><?php echo e(__('Source')); ?></span><span>${safe(ep.channel)}</span></div>
        `;
        document.body.appendChild(tip);

        const move = (ev) => {
          let x = ev.pageX + 12, y = ev.pageY + 12;
          if(x + tip.offsetWidth > window.innerWidth) x = ev.pageX - tip.offsetWidth - 12;
          if(y + tip.offsetHeight > window.innerHeight) y = ev.pageY - tip.offsetHeight - 12;
          tip.style.left = x + 'px'; tip.style.top = y + 'px';
        };
        move(info.jsEvent);
        info.el.addEventListener('mousemove', move);
        info.el.addEventListener('mouseleave', () => tip.remove(), { once:true });
      },

      eventContent: (info) => {
        const e = info.event, ep = e.extendedProps || {};
        const statusColor = getStatusColor(ep.status);
        const html = `
          <div class="fc-event-custom" style="--status-color:${statusColor}">
            <div class="k-ev">
              <div class="k-ev-hd">
                <span class="k-ev-ref" role="button"
                      onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.booking-modal', arguments: {booking: ${e.id}}})">
                  ${safe(ep.reference)}
                </span>
                <span class="k-ev-chip"><i class="bi bi-calendar2-event"></i>${safe(ep.channel)}</span>
              </div>
              <div class="k-ev-row"><i class="bi bi-person"></i><span>${safe(ep.guest)}</span></div>
              <div class="k-ev-row"><i class="bi bi-door-open"></i><span>${safe(ep.room)} — ${safe(ep.unitType)}</span></div>
              <div class="k-ev-row"><i class="bi bi-clock"></i><span>${fmt(e.start)} ~ ${fmt(subtractOneDay(e.end))}</span></div>
              <div class="k-ev-ft">
                <span class="k-ev-chip" style="background:#fff;color:#111827"><i class="bi bi-circle-fill" style="font-size:8px;color:${statusColor}"></i>${safe(ep.status)}</span>
                <div class="k-ev-actions">
                  <i class="fas fa-user-cog" role="button"
                     onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: ${e.id}}})"></i>
                </div>
              </div>
            </div>
          </div>`;
        return { html };
      }
    });

    cal.render();
    window.ndakoCalendar = cal;
    // Apply current filters once first batch is in DOM
    setTimeout(refilterCalendar, 0);
  }

  // ---- Livewire event to refresh events (no DOM re-render) -----------------
  const onCalendarUpdated = (eventsRaw) => {
    const cal = window.ndakoCalendar;
    if (!cal) { initializeCalendar(eventsRaw || []); return; }
    showLoading(true);
    const events = (eventsRaw || []).map(e => ({ ...e, end: addOneDay(e.end) }));
    cal.removeAllEvents();
    cal.addEventSource(events);
    showLoading(false);
    setTimeout(() => { cal.updateSize(); refilterCalendar(); }, 0);
  };

  // Livewire v2
  if (window.Livewire && Livewire.on) {
    Livewire.on('calendarUpdated', onCalendarUpdated);
  }
  // Livewire v3
  window.addEventListener('calendarUpdated', (e) => onCalendarUpdated(e.detail || e));

  // ---- Filters & Toolbar wiring -------------------------------------------
  function wireToolbar(){
    // Legend
    const legend = document.getElementById('kLegend');
    legend?.addEventListener('click', (e)=>{
      const item = e.target.closest('.item'); if(!item) return;
      const status = (item.dataset.status || '').toLowerCase();
      if(state.filters.statuses.has(status)){ state.filters.statuses.delete(status); item.classList.remove('active'); }
      else { state.filters.statuses.add(status); item.classList.add('active'); }
      refilterCalendar();
    });

    // Search
    const search = document.getElementById('kSearch');
    search?.addEventListener('input', debounce((ev)=>{
      state.filters.search = (ev.target.value || '').trim().toLowerCase();
      refilterCalendar();
    }, 250));

    // Today
    document.getElementById('kToday')?.addEventListener('click', ()=> window.ndakoCalendar?.today());

    // Next 7 days
    document.getElementById('kNext7')?.addEventListener('click', ()=>{
      const start = new Date();
      window.ndakoCalendar?.changeView('timeGridWeek', start);
    });

    // Density toggle
    const dens = document.getElementById('kDensity');
    dens?.addEventListener('click', ()=>{
      state.density = state.density === 'cozy' ? 'compact' : 'cozy';
      dens.dataset.mode = state.density;
      dens.querySelector('span').textContent = state.density === 'cozy' ? '<?php echo e(__('Compact')); ?>' : '<?php echo e(__('Cozy')); ?>';
      // Re-render to apply compact sizing on all events
      window.ndakoCalendar?.render();
      refilterCalendar();
    });
  }

  // Strong filter application using FullCalendar API (display prop)
  function refilterCalendar(){
    const cal = window.ndakoCalendar;
    if(!cal) return;
    cal.getEvents().forEach(ev => {
      const visible = eventMatchesFilters(ev);
      // Prefer using display prop so FC can reflow rows
      ev.setProp('display', visible ? 'auto' : 'none');
      // Density per current mode (applied again for newly visible events)
      const el = ev.el;
      if(el){
        if(state.density === 'compact'){ el.style.padding='6px'; el.style.fontSize='0.8rem'; }
        else{ el.style.padding=''; el.style.fontSize=''; }
      }
    });
  }

  // Rooms drag/keyboard scroll UX
  function wireRoomsScroll(){
    const scroller = document.getElementById('roomsScroll');
    if(!scroller) return;

    // Drag to scroll
    let isDown=false, startX=0, scrollLeft=0;
    scroller.addEventListener('mousedown', (e)=>{ isDown=true; scroller.classList.add('is-dragging'); startX=e.pageX - scroller.offsetLeft; scrollLeft=scroller.scrollLeft; });
    window.addEventListener('mouseup',   ()=>{ isDown=false; scroller.classList.remove('is-dragging'); });
    scroller.addEventListener('mouseleave', ()=>{ isDown=false; scroller.classList.remove('is-dragging'); });
    scroller.addEventListener('mousemove', (e)=>{
      if(!isDown) return;
      e.preventDefault();
      const x = e.pageX - scroller.offsetLeft;
      const walk = (x - startX) * 1;
      scroller.scrollLeft = scrollLeft - walk;
    });

    // Shift + wheel = horizontal
    scroller.addEventListener('wheel', (e)=>{
      if(e.shiftKey){
        e.preventDefault();
        scroller.scrollLeft += (e.deltaY || e.deltaX);
      }
    }, { passive:false });

    // Keyboard navigation
    scroller.addEventListener('keydown', (e)=>{
      const items = Array.from(scroller.querySelectorAll('.room-card.k-rc'));
      if(!items.length) return;
      const active = document.activeElement && items.includes(document.activeElement) ? document.activeElement : null;
      const idx = active ? items.indexOf(active) : -1;

      if(e.key === 'ArrowRight'){
        e.preventDefault();
        const next = items[Math.min(items.length-1, idx+1)] || items[0];
        next.focus({preventScroll:true});
        next.scrollIntoView({behavior:'smooth', inline:'center', block:'nearest'});
      }
      if(e.key === 'ArrowLeft'){
        e.preventDefault();
        const prev = items[Math.max(0, idx-1)] || items[items.length-1];
        prev.focus({preventScroll:true});
        prev.scrollIntoView({behavior:'smooth', inline:'center', block:'nearest'});
      }
      if(e.key === 'Enter' && active){
        active.click();
      }
    });
  }

  // ---- Helpers -------------------------------------------------------------
  function getStatusColor(status){
    const key = (status || '').toLowerCase();
    return STATUS_COLORS[key] || STATUS_COLORS.fallback;
  }
  function safe(v){ return String(v ?? '').replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s])); }
  function fmt(date){ const d = new Date(date); return d.toLocaleDateString(undefined, { year:'numeric', month:'short', day:'numeric' }); }
  function addOneDay(s){ const d = new Date(s); d.setDate(d.getDate()+1); return d.toISOString(); }
  function subtractOneDay(s){ if(!s) return null; const d = new Date(s); d.setDate(d.getDate()-1); return d.toISOString(); }

  function eventMatchesFilters(ev){
    const ep = ev.extendedProps || {};
    const status = String(ep.status || '').toLowerCase();
    if(!state.filters.statuses.has(status) && !(status === '' && state.filters.statuses.has('fallback'))) return false;

    const q = state.filters.search;
    if(!q) return true;
    const hay = [ep.reference, ep.guest, ep.room, ep.unitType, ep.channel]
      .map(x => String(x||'').toLowerCase())
      .join(' ');
    return hay.includes(q);
  }
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/template/calendar.blade.php ENDPATH**/ ?>