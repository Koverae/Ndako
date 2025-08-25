
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
