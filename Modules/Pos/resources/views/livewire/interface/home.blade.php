@section('title', $pos->name)
@section('styles')
<style>
/* =========================
   Employee login (lockscreen)
   ========================= */
.emp-login-wrap{
  width:100%;
  max-width:980px;
  margin-inline:auto;
  padding:0 16px;
}
.emp-login{
  display:grid;
  grid-template-columns: 1fr;
  gap:16px;
  width:100%;
}
@media (min-width: 992px){
  .emp-login{ grid-template-columns: 1fr 1fr; }
}

/* card */
.emp-card{
  background:#ffffff;
  border:1px solid #e5e7eb;
  border-radius:14px;
  padding:16px;
  box-shadow:0 8px 20px rgba(0,0,0,.06);
}
.emp-head{
  display:flex; align-items:center; justify-content:space-between; gap:10px;
  margin-bottom:10px;
}
.emp-title{ margin:0; font-weight:800; letter-spacing:.015em; font-size:1.15rem }
.emp-subtle{ color:#6b7280; font-size:.9rem }

/* pin pad */
.emp-pin{
  display:flex; flex-direction:column; gap:10px;
}
.emp-pin-display{
  display:flex; align-items:center; gap:8px; justify-content:space-between;
  border:1px solid #e5e7eb; border-radius:12px; padding:10px 12px; background:#fff;
}
.emp-pin-dots{ display:flex; gap:8px; flex:1; justify-content:center }
.emp-dot{
  width:12px; height:12px; border-radius:50%; background:#e5e7eb;
  transition:background .12s ease, transform .12s ease;
}
.emp-dot.filled{ background:#045054; transform:scale(1.1) }
.emp-pin-ctas{ display:flex; gap:8px; }
.emp-keypad{
  display:grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap:8px;
}
.emp-key{
  border:1px solid #e5e7eb; background:#fff; border-radius:12px;
  height:52px; font-weight:800; font-size:1.15rem;
  display:flex; align-items:center; justify-content:center;
  transition:transform .05s ease, background .12s ease, border-color .12s ease, box-shadow .12s ease;
}
.emp-key:active{ transform:translateY(1px) }
.emp-key:hover{ background:#f7fafc }
.emp-key--ok{
  background:#045054; color:#fff; border-color:#045054;
}
.emp-key--ok:hover{ filter:brightness(1.05) }
.emp-key--ghost{ background:#fafafa }

/* subtle link row */
.emp-help{
  display:flex; align-items:center; justify-content:space-between; gap:8px;
  font-size:.9rem; color:#6b7280; margin-top:6px;
}
.emp-help a{ color:#045054; text-decoration:none; font-weight:700 }
.emp-help a:hover{ color:#033a3f }

/* dark mode */
[data-theme="dark"] .emp-card{ background:#262A36; border-color:#4a5568; box-shadow:none }
[data-theme="dark"] .emp-title{ color:#e2e8f0 }
[data-theme="dark"] .emp-subtle{ color:#cbd5e0 }
[data-theme="dark"] .emp-pin-display{ background:#262A36; border-color:#4a5568 }
[data-theme="dark"] .emp-dot{ background:#4a5568 }
[data-theme="dark"] .emp-dot.filled{ background:#78d1d3 }
[data-theme="dark"] .emp-key{ background:#2b3040; border-color:#4a5568; color:#e2e8f0 }
[data-theme="dark"] .emp-key:hover{ background:#32384a }
[data-theme="dark"] .emp-key--ok{ background:#2a8a90; border-color:#2a8a90 }
[data-theme="dark"] .emp-help{ color:#cbd5e0 }
</style>
@endsection

<main
  class="relative main"
  x-data="posRoot(@entangle('isLocked'))"
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
        <div>
          <img
            src="{{ asset('assets/images/logo/ndako.png') }}"
            alt="Ndako Logo"
            style="height: 60px;"
            loading="lazy" decoding="async" fetchpriority="low"
          />
        </div>
      </div>

      <!-- Center: PIN only -->
      <div class="flex-grow d-flex flex-column align-items-center justify-content-center w-100">
        <div class="emp-login-wrap w-100">
          <div class="emp-login" x-data="employeeLogin()">
            <!-- PIN pad only -->
            <div class="emp-card">
              <div class="emp-head">
                <h3 class="emp-title">{{ __('Enter PIN') }}</h3>
                <div class="emp-subtle" x-show="error" x-text="error" style="color:#c0392b"></div>
              </div>

              <div class="emp-pin">
                <div class="emp-pin-display" aria-live="polite">
                  <div class="emp-pin-dots" :aria-label="'{{ __('Digits entered') }}: ' + pin.length">
                    <template x-for="i in 6" :key="i">
                      <span class="emp-dot" :class="{ 'filled': i <= pin.length }"></span>
                    </template>
                  </div>
                  <div class="emp-pin-ctas">
                    <button class="btn btn-sm btn-outline-secondary rounded-pill" @click="clear()" type="button">
                      {{ __('Clear') }}
                    </button>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill" @click="backspace()" type="button" aria-label="{{ __('Backspace') }}">
                      <i class="bi bi-backspace"></i>
                    </button>
                  </div>
                </div>

                <div class="emp-keypad" @keydown.stop.prevent="
                      if($event.key >= '0' && $event.key <= '9') press($event.key);
                      else if($event.key==='Backspace') backspace();
                      else if($event.key==='Enter') submit();
                    " tabindex="0">
                  <template x-for="n in [1,2,3,4,5,6,7,8,9]" :key="'k'+n">
                    <button type="button" class="emp-key" @click="press(String(n))" x-text="n"></button>
                  </template>
                  <button type="button" class="emp-key emp-key--ghost" @click="press('0')">0</button>
                  <button type="button" class="emp-key" @click="backspace()" aria-label="{{ __('Backspace') }}"><i class="bi bi-arrow-left"></i></button>
                  <button type="button" class="emp-key emp-key--ok" @click="submit()" :disabled="loading" x-bind:aria-busy="loading ? 'true' : 'false'">
                    <span x-show="!loading">{{ __('Unlock') }}</span>
                    <span x-show="loading"><span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>{{ __('Checking…') }}</span>
                  </button>
                </div>

                <div class="emp-help">
                  <span>{{ __('Forgot your PIN? Ask a manager to reset it.') }}</span>
                  <a href="javascript:void(0)" @click="$wire.goToBackend()">{{ __('Go to backend') }}</a>
                </div>
              </div>
            </div>
            <!-- /PIN pad -->
          </div>
        </div>
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
          <div class="nav-item dropdown" wire:ignore>
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
      <div x-show="flashOnline"
           x-transition.opacity.duration.250ms
           class="net-toast alert alert-success py-1 px-2 small shadow-sm"
           role="status" aria-live="polite">
        <i class="bi bi-wifi"></i> {{ __('Back online') }}
      </div>
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
    @include('pos::partials.pos.products')
    @include('pos::partials.pos.checkout')
    @include('pos::partials.pos.mobile-checkout-switcher')
  </div>
  <!-- Register -->

  @include('pos::partials.pos.payment')
  @include('pos::partials.pos.receipt')

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

  @include('pos::partials.pos.orders')
</main>

@push('scripts')
<script>
/* ============================================================================ */
/* posRoot (unchanged)                                                          */
/* ============================================================================ */
function posRoot(lockedEntangle){
  return {
    isLocked: lockedEntangle,
    clockInterval: null,

    init(){
      this.startClock();
      window.addEventListener('pageshow', (e) => { if (e.persisted) this.startClock(); }, { passive: true });
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
      this.stopClock();
      this.clockInterval = setInterval(render, 1000);
    },

    stopClock(){
      if (this.clockInterval) { clearInterval(this.clockInterval); this.clockInterval = null; }
    }
  }
}

/* ============================================================================ */
/* Employee login Alpine (PIN only)                                             */
/* ============================================================================ */
function employeeLogin(){
  return {
    pin: '', loading: false, error: '',
    press(d){ if(this.pin.length >= 6) return; this.pin += d; this.error = ''; },
    backspace(){ this.pin = this.pin.slice(0,-1) }, clear(){ this.pin = ''; this.error = '' },
    async submit(){
      if(this.pin.length < 4){ this.error = '{{ __('PIN must be at least 4 digits') }}'; return; }
      this.loading = true; this.error = '';
      try{
        const res = await $wire.unlockWithPin(this.pin);
        if(res && res.ok){
          // Server handles continue/open + unlock. Nothing else to do.
          return;
        }
        this.error = res?.message || '{{ __('Invalid PIN') }}'; this.shake();
      }catch(e){ this.error = '{{ __('Something went wrong') }}'; }
      finally{ this.loading = false; }
    },
    shake(){
      const el = document.querySelector('.emp-pin-display'); if(!el) return;
      el.animate([{transform:'translateX(0)'},{transform:'translateX(-3px)'},{transform:'translateX(3px)'},{transform:'translateX(0)'}], { duration: 180, iterations: 1 });
    }
  }
}

/* ============================================================================ */
/* Livewire bridge (unchanged)                                                  */
/* ============================================================================ */
(() => {
  if (!window.__POS_LW_BOUND__) {
    window.__POS_LW_BOUND__ = true;
    Livewire.on('play-sound', (payload) => { try { playSound(payload?.type); } catch (e) {} });
    Livewire.on('print-bill', () => { window.print(); });
  }
})();

/* ============================================================================ */
/* Calculator / shortcuts / theme / offline / cartStore (unchanged)            */
/* ============================================================================ */
function calculatorComponent($wire) {
  return {
    input: '',
    keys: [
      { label: '1', value: '1' }, { label: '2', value: '2' }, { label: '3', value: '3' }, { label: 'Qty', value: 'qty', class: 'btn-light', mode: true },
      { label: '4', value: '4' }, { label: '5', value: '5' }, { label: '6', value: '6' }, { label: 'Disc', value: 'discount', icon: 'bi bi-percent', class: 'btn-light', mode: true },
      { label: '7', value: '7' }, { label: '8', value: '8' }, { label: '9', value: '9' }, { label: 'Price', value: 'price', class: 'btn-light', mode: true },
      { label: '÷', value: '/', style: 'background-color: #F5D976;' }, { label: '0', value: '0' }, { label: '.', value: '.', style: 'background-color: #F5D7CB;' }, { label: '', value: 'Backspace', icon: 'bi bi-backspace', style: 'background-color: #FAA0A0;' },
    ],
    press(value) {
      if (!$wire.selectedProductId) return;
      if (['qty','discount','price'].includes(value)) { $wire.selectCalculatorMode(value); return; }
      switch (value) {
        case 'q': $wire.selectCalculatorMode('qty'); return;
        case 'p': $wire.selectCalculatorMode('price'); return;
        case 'd': $wire.selectCalculatorMode('discount'); return;
        case '/': this.input += '/'; break;
        case 'Backspace': this.input = this.input.slice(0, -1); break;
        case 'Enter': return;
        default: if (/^[0-9]$/.test(value) || value === '.') this.input += value; else return;
      }
      $wire.set('calculatorInput', this.input);
      $wire.applyCalculatorInput();
    },
  };
}

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
  if (!currentTheme) currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  apply(currentTheme);

  toggleButton.addEventListener('click', () => apply(currentTheme = (currentTheme === 'light' ? 'dark' : 'light')));

  const mq = window.matchMedia('(prefers-color-scheme: dark)');
  mq.addEventListener?.('change', (e) => {
    if (!localStorage.getItem('theme')) apply(e.matches ? 'dark' : 'light');
  });
})();

(function(){
  const updateBarHeight = () => {
    const el = document.getElementById('m-mobile-switcher');
    if(!el) return;
    const h = el.offsetHeight || 72;
    document.documentElement.style.setProperty('--mobile-bar-h', `${h}px`);
  };
  window.addEventListener('load', updateBarHeight, { passive: true });
  window.addEventListener('resize', updateBarHeight, { passive: true });
  window.addEventListener('orientationchange', updateBarHeight, { passive: true });
  document.addEventListener('livewire:navigated', updateBarHeight, { passive: true });
})();

function offlineBanner(){
  return {
    online: navigator.onLine,
    dismissed: false,
    flashOnline: false,
    timer: null,

    init(){
      window.addEventListener('online',  () => this.onOnline(),  {passive:true});
      window.addEventListener('offline', () => this.onOffline(), {passive:true});
      this.pulse();
    },

    onOnline(){ this.online = true; this.dismissed = false; this.flash(); },
    onOffline(){ this.online = false; },

    manualRetry(){
      fetch(window.location.href, { method:'HEAD', cache:'no-store' })
        .then(() => this.onOnline())
        .catch(() => this.onOffline());
    },

    pulse(){
      clearInterval(this.timer);
      this.timer = setInterval(() => { if (!this.online) this.manualRetry(); }, 8000);
    },

    flash(){ this.flashOnline = true; setTimeout(() => this.flashOnline = false, 1600); }
  }
}

function cartStore(cartEntangle, posId) {
  return {
    online: navigator.onLine,
    syncing: false,
    shadowCart: JSON.parse(localStorage.getItem(`pos:${posId}:cart`) || '{}'),
    queue:      JSON.parse(localStorage.getItem(`pos:${posId}:queue`) || '[]'),

    init() {
      window.addEventListener('online',  () => { this.online = true;  this.flushQueue() });
      window.addEventListener('offline', () => { this.online = false });
      if (this.online) this.flushQueue();
    },

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

    async add({ id, name, price }) {
      if (this.online) {
        try { await $wire.addToCart(id); return; } catch (e) {}
      }
      this.enqueue({ type: 'add', id, name, price });
      this.localAdd({ id, name, price });
    },

    async flushQueue() {
      if (!this.online || !this.queue.length) return;
      this.syncing = true;
      while (this.queue.length && this.online) {
        const op = this.queue[0];
        try {
          if (op.type === 'add')  await $wire.addToCart(op.id);
          this.queue.shift(); this.persist();
        } catch (e) { break; }
      }
      this.syncing = false;
      try { await $wire.$refresh(); } catch (e) {}
    },

    cart: {
      add:     (args) => this.add(args),
      items:   () => this.itemsCount(),
      syncing: () => this.syncing
    }
  }
}
</script>
@endpush
