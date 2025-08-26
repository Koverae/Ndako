@section('title', $pos->name)
@section('styles')
<style>


</style>
@endsection

<main
  class="relative main"
  x-data="posRoot(@entangle('isLocked'))"
  {{-- :inert="isLocked" --}}
>
  <!-- Lock Screen -->
  <div
    x-show="isLocked"
    x-transition.opacity
    style="z-index: 99999;"
    class="fixed inset-0 flex items-center {{ $isLocked ? '' : 'd-none' }} justify-center bg-opacity-75 d-print-none bg-body-secondary backdrop-blur animate-fade-in"
    role="dialog" aria-modal="true" aria-labelledby="lockscreen-time"
  >
    <div class="relative flex flex-col items-center justify-center w-full h-full bg-white">
      <!-- Top Bar: Date/Time (left) and Logo (right) -->
      <div class="top-0 px-4 py-4 position-absolute start-0 end-0 d-flex justify-content-between align-items-center" style="width: 100%;">
        <!-- Date & Time (Left) -->
        <div>
          <div id="lockscreen-datetime"
               class="justify-between px-4 py-3 bg-opacity-75 d-flex align-items-center rounded-3"
               style="backdrop-filter: blur(6px); letter-spacing: 0.02em; font-family: 'Segoe UI', sans-serif; min-width: 280px;">
            <div class="time fs-1 fw-bold text-dark d-flex align-items-center">
              <i class="bi bi-clock me-2 fs-4 text-secondary" aria-hidden="true"></i>
              <span id="lockscreen-time" class="fs-1" aria-live="polite"></span>
            </div>
            <div class="date text-end ps-3">
              <div id="lockscreen-weekday" class="fw-semibold text-dark small"></div>
              <div id="lockscreen-full-date" class="text-muted small"></div>
            </div>
          </div>
        </div>
        <!-- Logo (Right) -->
        <div>
          <img
            src="{{ asset('assets/images/logo/ndako.png') }}"
            alt="Ndako Logo"
            style="height: 60px;"
            loading="lazy" decoding="async" fetchpriority="low"
          />
        </div>
      </div>

      <!-- Full screen center card: Continue Selling -->
      <div class="flex-grow d-flex justify-content-center align-items-center w-100">
        <button
          wire:click="{{ (session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'continueSelling' : 'openRegister' }}"
          class="gap-2 p-5 bg-white cursor-pointer text-dark fw-semibold fs-2 border-1 bg-opacity-90 align-items-center animate-fade-up"
          style="transition: box-shadow 0.2s; height: 200px; border-radius: 10px;"
        >
          <i class="fas fa-shopping-basket" style="font-size: 45px;" aria-hidden="true"></i>
          <div>
            @php
              $label = (session()->has("pos_session_id_{$this->pos->id}") || $this->pos->active_session_id) ? 'Continue Selling' : 'Open Register';
            @endphp
            {{ $label }}
          </div>
        </button>
      </div>

      <!-- Bottom Bar: Backend Button -->
      <div class="bottom-0 pb-4 position-absolute start-0 end-0 d-flex justify-content-center align-items-center w-100">
        <button
          wire:click="goToBackend"
          class="px-5 py-2 shadow-sm btn btn-outline-dark rounded-pill fw-semibold fs-4"
        >
          <i class="bi bi-gear me-2" aria-hidden="true"></i> {{ __('Backend') }}
        </button>
      </div>
    </div>
  </div>
  <!-- Lock Screen -->

  <!-- Navbar -->
  <nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky dark:bg-gray-800">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
              aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 m-0">
        <a href="" aria-label="Home">
          <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" class="navbar-brand-image normal" loading="lazy" decoding="async">
          <img src="{{ asset('assets/images/logo/ndako-white.png') }}" alt="Ndako Logo" class="navbar-brand-image dark" loading="lazy" decoding="async">
        </a>
      </h1>

      <div class="flex-row navbar-nav order-md-last">
        <div class="d-md-flex d-flex">
          <div class="nav-item dropdown d-md-flex me-3">
            <a href="#" class="px-0 nav-link text-dark" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-placement="bottom" aria-label="Translate">
              <i class="bi bi-translate" style="font-size: 16px;"></i>
            </a>
          </div>

          <div class="nav-item dropdown">
            <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
            </a>
            <div class="p-0 dropdown-menu dark-menu pos-burger-menu-items dropdown-menu-end dropdown-menu-arrow">
              <div class="p-2 pb-3 mb-2 border-bottom">
                <span class="text-center btn pos-customer-screen btn-lg w-100 dark:bg-gray-700 dark:text-gray-200">
                  <i class="fas fa-desktop"></i>
                </span>
              </div>
              <div class="p-2 rounded menu-items">
                <span class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 toggle-theme">
                  <span class="theme-label">{{ __('Switch to Dark Mode') }}</span>
                </span>
                <span class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Cash In/Out') }}
                </span>
                <span wire:click="goToBackend" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Backend') }}
                </span>
                <span @click="isLocked=true" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Lock Screen') }}
                </span>
                <span wire:click="closeRegister" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Close Register') }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
          <ul class="navbar-nav">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
              <li class="cursor-pointer nav-item" data-turbolinks>
                <a class="nav-link kover-navlink {{ $interface == 'tables' ? 'selected' : '' }} dark:text-gray-200"
                   wire:click="switchInterface('tables')" style="margin-right: 5px;">
                  <span class="nav-link-title">{{ __('Tables') }}</span>
                </a>
              </li>
              <li class="cursor-pointer nav-item" data-turbolinks>
                <a class="nav-link kover-navlink {{ $interface == 'register' ? 'selected' : '' }} dark:text-gray-200"
                   wire:click="switchInterface('register')" style="margin-right: 5px;">
                  <span class="nav-link-title">{{ __('Register') }}</span>
                </a>
              </li>
              <li class="cursor-pointer nav-item" data-turbolinks>
                <a class="nav-link kover-navlink {{ $interface == 'orders' ? 'selected' : '' }} dark:text-gray-200"
                   wire:click="switchInterface('orders')" style="margin-right: 5px;">
                  <span class="nav-link-title">{{ __('Orders') }}</span>
                </a>
              </li>
              @if($selectedTable)
                <li class="nav-item" data-turbolinks>
                  <span class="text-white cursor-pointer badge rounded-pill bg-info fs-4 fw-bolder text-truncate dark:bg-blue-700">
                    {{ $selectedTable->table_name ?? __('Direct Sale') }}
                  </span>
                </li>
              @endif
            </div>
          </ul>
        </div>
      </div>
    </div>
    {{-- Offline banner (sticky, responsive, accessible) --}}
<div x-data="offlineBanner()" x-init="init()" class="position-relative">
  <!-- subtle 'back online' toast -->
  <div x-show="flashOnline"
       x-transition.opacity.duration.250ms
       class="net-toast alert alert-success py-1 px-2 small shadow-sm"
       role="status" aria-live="polite">
    <i class="bi bi-wifi"></i> {{ __('Back online') }}
  </div>

  <!-- main offline banner -->
  <div x-show="!online && !dismissed"
       x-transition.duration.200ms
       class="net-banner border-top border-bottom"
       role="status" aria-live="polite">
    <div class="container-fluid d-flex flex-wrap align-items-center gap-2">
      <div class="d-flex align-items-center gap-2 flex-grow-1">
        <i class="bi bi-wifi-off fs-5" aria-hidden="true"></i>
        <div class="d-flex flex-column">
          <strong class="net-title">{{ __('You’re offline') }}</strong>
          <span class="net-subtle">
            {{ __('We’ll reconnect automatically. Some actions may be delayed.') }}
          </span>
        </div>
      </div>

      <div class="d-flex align-items-center gap-2 ms-auto">
        <button type="button" class="btn btn-sm btn-outline-secondary"
                @click="manualRetry()">
          <i class="bi bi-arrow-clockwise"></i> {{ __('Retry') }}
        </button>
        <button type="button" class="btn btn-sm btn-link text-decoration-none"
                @click="dismissed = true">
          {{ __('Hide') }}
        </button>
      </div>
    </div>
  </div>
</div>

  </nav>

  <!-- Register -->
  <div
    x-data="cartStore(@entangle('cart').defer, @entangle('isOnline').defer, {{ $pos->id }})"
        x-init="init()"
            class="row {{ $interface == 'register' ? '' : 'd-none' }} d-print-none"
                >
    <!-- Product Section -->
    @include('pos::partials.pos.products')
    <!-- Product Section -->

    <!-- Checkout Section -->
    @include('pos::partials.pos.checkout')
    <!--Checkout Section -->

    <!-- Mobile Checkout Switcher -->
    @include('pos::partials.pos.mobile-checkout-switcher')
    <!-- Mobile Checkout Switcher -->

  </div>
  <!-- Register -->

  <!-- Payment -->
  @include('pos::partials.pos.payment')
  <!-- Payment -->

  <!-- Receipt (print) -->
  @include('pos::partials.pos.receipt')
  <!-- Receipt -->

  <!-- Tables -->
  <div class="table-container d-print-none bg-white {{ $interface == 'tables' ? '' : 'd-none' }} dark:bg-gray-800 h-screen-d">
    <div class="gap-3 px-3 table-navbar d-flex flex-column gap-lg-1 d-print-none">
      <div class="gap-5 p-2 table-navbar-main d-flex flex-nowrap justify-content-between align-items-lg-start flex-grow-1">
        <div class="gap-1 table-navbar-left d-flex align-items-center order-0">
          <button wire:click="newOrder" class="new-order btn btn-primary fs-3 btn-lg lh-lg dark:bg-indigo-600">
            <i class="bi bi-plus fs-3" aria-hidden="true"></i> <span class="d-none d-lg-flex">{{ __('New Order') }}</span>
          </button>
        </div>
        <div id="actions" class="order-2 gap-2 d-inline-flex rounded-2 table-navbar-actions d-flex align-items-center justify-content-between order-lg-1">
          <div class="gap-3 d-flex align-items-center">
            <div class="table-navbar-buttons align-items-center">
              @foreach ($floorPlanOptions as $plan)
                <span
                  wire:click="changeFloorPlan('{{ $plan->id }}')"
                  class="w-auto gap-1 k_switch_view fs-3 d-lg-inline-block btn btn-secondary {{ $plan->id == $selectedPlanId ? 'active' : '' }} k-list dark:bg-gray-800 dark:text-gray-200"
                  role="button"
                >
                  {{ $plan->name }}
                </span>
              @endforeach
            </div>
          </div>
        </div>
        <div class="flex-wrap order-3 align-items-end table-navbar-left d-flex flex-md-wrap align-items-center justify-content-end gap-l-1 gap-xl-5 order-lg-2 flex-grow-1">
          <div class="table-navbar-buttons d-print-none d-xl-inline-flex btn-group"></div>
        </div>
      </div>
    </div>

    <div class="p-5 overflow-y-auto table-section row h-100">
      @foreach($floorPlanOptions->where('id', $selectedPlanId)->first()->tables as $table)
        <div class="floor col-md-3">
          <div class="p-0 rounded cursor-pointer floor-table flex-column justify-content-between position-absolute dark:bg-gray-700">
            <div
              wire:click="selectTable('{{ $table->id }}')"
              class="info {{ $selectedTable?->id == $table->id ? 'active' : '' }} w-100 h-100 overflow-hidden dark:text-gray-200"
            >
              <div class="label top-50 start-50 fw-bolder position-absolute fs-3 translate-middle text-center">
                {{ $table->table_name }}<br>
                <small>{{ inverseSlug($table->status) }}</small>
              </div>
            </div>
            @if($table->status == 'occupied')
              <button
                wire:click="releaseTable('{{ $table->id }}')"
                class="bottom-0 m-1 btn btn-danger btn-sm position-absolute end-0 dark:bg-red-800 dark:border-red-800"
              >
                {{ __('Release') }}
              </button>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
  <!-- Tables -->

  <!-- Orders -->
  @include('pos::partials.pos.orders')
  <!-- Orders -->
</main>

@push('scripts')
<script>
/* ============================================================================
   Root Alpine module: keeps lockscreen timer tidy & single-instanced
   ========================================================================== */
function posRoot(lockedEntangle){
  return {
    isLocked: lockedEntangle,
    clockInterval: null,

    init(){
      this.startClock();
      // Clean up on page cache restore (e.g., bfcache)
      window.addEventListener('pageshow', (e) => {
        if (e.persisted) this.startClock();
      }, { passive: true });
      window.addEventListener('pagehide', () => this.stopClock(), { passive: true });
    },

    startClock(){
      const timeEl = document.getElementById('lockscreen-time');
      const weekdayEl = document.getElementById('lockscreen-weekday');
      const fullDateEl = document.getElementById('lockscreen-full-date');
      if (!timeEl || !weekdayEl || !fullDateEl) return;

      const render = () => {
        const now = new Date();
        timeEl.textContent = now.toLocaleTimeString(undefined, { hour: '2-digit', minute: '2-digit' });
        weekdayEl.textContent = now.toLocaleDateString(undefined, { weekday: 'short' });
        fullDateEl.textContent = now.toLocaleDateString(undefined, { day: '2-digit', month: 'short', year: 'numeric' });
      };
      render();
      this.stopClock(); // prevent duplicate intervals
      this.clockInterval = setInterval(render, 1000);
    },

    stopClock(){
      if (this.clockInterval) { clearInterval(this.clockInterval); this.clockInterval = null; }
    }
  }
}

/* ============================================================================
   Livewire bridge: sound + print (idempotent, avoids duplicate listeners)
   ========================================================================== */
(() => {
  if (!window.__POS_LW_BOUND__) {
    window.__POS_LW_BOUND__ = true;

    // Play Sound
    Livewire.on('play-sound', (payload) => {
      try { playSound(payload?.type); } catch (e) { /* no-op if playSound missing */ }
    });

    // Print (bill/receipt)
    Livewire.on('print-bill', () => {
      console.log("Printing launched");
      window.print();
    });
  }
})();

/* ============================================================================
   Calculator (Alpine) — responsive, guarded, and Livewire-friendly
   ========================================================================== */
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
      { label: '', value: 'Backspace', icon: 'bi bi-backspace', style: 'background-color: #FAA0A0;' },
    ],

    press(value) {
      // Guard: require selected product
      if (!$wire.selectedProductId) return;

      // Mode keys (sticky)
      if (['qty', 'discount', 'price'].includes(value)) {
        $wire.selectCalculatorMode(value);
        return;
      }

      // Hotkeys
      switch (value) {
        case 'q': $wire.selectCalculatorMode('qty'); return;
        case 'p': $wire.selectCalculatorMode('price'); return;
        case 'd': $wire.selectCalculatorMode('discount'); return;
        case '/': this.input += '/'; break;
        case 'Backspace': this.input = this.input.slice(0, -1); break;
        case 'Enter': /* hook for confirm if needed */ return;
        default:
          if (/^[0-9]$/.test(value) || value === '.') this.input += value;
          else return; // ignore unknown keys
      }

      // Push to Livewire every keypress (keeps UI snappy)
      $wire.set('calculatorInput', this.input);
      $wire.applyCalculatorInput();
    },
  };
}

  // Press "/" to focus product search (when register is visible & not typing elsewhere)
  (() => {
    const onKey = (e) => {
      if (e.key !== '/') return;
      const isRegisterVisible = !document.querySelector('.row.d-print-none').classList.contains('d-none') && '{{ $interface }}' === 'register';
      if (!isRegisterVisible) return;
      const tag = (e.target.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea' || e.target.isContentEditable) return;
      e.preventDefault();
      document.getElementById('prod-search-input')?.focus();
    };
    window.addEventListener('keydown', onKey, { passive: false });
  })();

/* ============================================================================
   Theme toggle (light/dark) — resilient and single-instanced
   ========================================================================== */
(() => {
  const html = document.documentElement;
  const toggleButton = document.querySelector('.toggle-theme');
  if (!toggleButton) return;
  const themeLabel = toggleButton.querySelector('.theme-label');

  const apply = (mode) => {
    html.setAttribute('data-theme', mode);
    localStorage.setItem('theme', mode);
    if (themeLabel) {
      themeLabel.textContent = mode === 'dark'
        ? '{{ __('Switch to Light Mode') }}'
        : '{{ __('Switch to Dark Mode') }}';
    }
  };

  let currentTheme = localStorage.getItem('theme');
  if (!currentTheme) {
    currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }
  apply(currentTheme);

  toggleButton.addEventListener('click', () => apply(currentTheme = (currentTheme === 'light' ? 'dark' : 'light')));

  // Sync with system if user didn't set an explicit preference
  const mq = window.matchMedia('(prefers-color-scheme: dark)');
  mq.addEventListener?.('change', (e) => {
    if (!localStorage.getItem('theme')) apply(e.matches ? 'dark' : 'light');
  });
})();

  // Measure the actual mobile switcher height and update the CSS var for perfect spacing
  (function(){
    const updateBarHeight = () => {
      const el = document.getElementById('m-mobile-switcher');
      if(!el) return;
      const h = el.offsetHeight || 72;
      document.documentElement.style.setProperty('--mobile-bar-h', `${h}px`);
    };
    // Run on load and on resize/orientation changes
    window.addEventListener('load', updateBarHeight, { passive: true });
    window.addEventListener('resize', updateBarHeight, { passive: true });
    window.addEventListener('orientationchange', updateBarHeight, { passive: true });
    // In case Livewire re-renders the footer
    document.addEventListener('livewire:navigated', updateBarHeight, { passive: true });
  })();


  // Alpine helper for network awareness
  function offlineBanner(){
    return {
      online: navigator.onLine,
      dismissed: false,
      flashOnline: false,
      timer: null,

      init(){
        window.addEventListener('online',  () => this.onOnline(),  {passive:true});
        window.addEventListener('offline', () => this.onOffline(), {passive:true});
        // gentle auto-retry ping while offline (optional)
        this.pulse();
      },

      onOnline(){
        this.online = true;
        this.dismissed = false; // show again next time if it drops
        this.flash();           // brief "Back online" toast
      },

      onOffline(){
        this.online = false;
      },

      manualRetry(){
        // lightweight ping instead of full reload
        fetch(window.location.href, { method:'HEAD', cache:'no-store' })
          .then(() => this.onOnline())
          .catch(() => this.onOffline());
      },

      pulse(){
        clearInterval(this.timer);
        this.timer = setInterval(() => {
          if (!this.online) this.manualRetry();
        }, 8000);
      },

      flash(){
        this.flashOnline = true;
        setTimeout(() => this.flashOnline = false, 1600);
      }
    }
  }
  
  // Online-first cart with offline fallback + replay queue
  function cartStore(cartEntangle, posId) {
    return {
      online: navigator.onLine,
      syncing: false,
      // local shadow so the UI still feels live when offline
      shadowCart: JSON.parse(localStorage.getItem(`pos:${posId}:cart`) || '{}'),
      queue:      JSON.parse(localStorage.getItem(`pos:${posId}:queue`) || '[]'),

      init() {
        // Track connectivity
        window.addEventListener('online',  () => { this.online = true;  this.flushQueue() });
        window.addEventListener('offline', () => { this.online = false });

        // Try a quick flush on load if we’re already online
        if (this.online) this.flushQueue();
      },

      // ---------- helpers ----------
      persist() {
        localStorage.setItem(`pos:${posId}:cart`,  JSON.stringify(this.shadowCart));
        localStorage.setItem(`pos:${posId}:queue`, JSON.stringify(this.queue));
      },
      itemsCount() {
        return Object.values(this.shadowCart).reduce((t, i) => t + Number(i.quantity || 0), 0);
      },
      localAdd({ id, name, price }) {
        const line = this.shadowCart[id] || { id, name, unit_price: price, quantity: 0, discount: 0 };
        line.quantity += 1;
        this.shadowCart[id] = line;
        this.persist();
      },
      enqueue(op) { this.queue.push(op); this.persist(); },

      // ---------- online-first actions with fallback ----------
      async add({ id, name, price }) {
        if (this.online) {
          try {
            await $wire.addToCart(id); // Livewire is the source of truth when online
            return; // UI will hydrate from server normally
          } catch (e) {
            // Fall back to local if server call fails
          }
        }
        // Offline (or server failed): queue & update local shadow
        this.enqueue({ type: 'add', id, name, price });
        this.localAdd({ id, name, price });
      },

      // (extend later) updateQty/remove/discount with same pattern

      // ---------- replay queue on reconnect ----------
      async flushQueue() {
        if (!this.online || !this.queue.length) return;
        this.syncing = true;

        // Replay FIFO
        while (this.queue.length && this.online) {
          const op = this.queue[0];
          try {
            if (op.type === 'add')  await $wire.addToCart(op.id);
            // (extend later) other types...
            this.queue.shift();
            this.persist();
          } catch (e) {
            // Stop on first error to avoid thrashing the server
            break;
          }
        }

        this.syncing = false;
        try { await $wire.$refresh(); } catch (e) {}
      },

      // expose the API under `cart.*` to match your current template usage
      cart: {
        add:     (args) => this.add(args),
        items:   () => this.itemsCount(),
        syncing: () => this.syncing
      }
    }
  }

</script>
@endpush
