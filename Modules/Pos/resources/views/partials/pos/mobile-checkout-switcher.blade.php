
<section class="d-lg-none" id="mobile-checkout-box" aria-label="{{ __('Cart and payment actions') }}">
  <nav id="m-mobile-switcher" role="tablist">
    <div class="wrap">
      {{-- PAY --}}
      <button
        type="button"
        class="btn btn-tab btn-pay {{ $tab === 'pay' ? 'active' : '' }} {{ empty($cart) ? 'disabled' : '' }}"
        wire:click="changeTab('pay')"
        @if(empty($cart)) disabled aria-disabled="true" @endif
        aria-selected="{{ $tab === 'pay' ? 'true' : 'false' }}"
        aria-label="{{ __('Go to Payment') }}"
      >
        <div class="label">
          <div class="title">{{ __('Pay') }}</div>
          <div class="sub">{{ format_currency($cartTotal) }}</div>
        </div>
        <i class="bi bi-credit-card-2-front icon" aria-hidden="true"></i>
      </button>

      {{-- CART --}}
      <button
        type="button"
        class="btn btn-tab btn-light {{ $tab === 'cart' ? 'active' : '' }}"
        wire:click="changeTab('cart')"
        aria-selected="{{ $tab === 'cart' ? 'true' : 'false' }}"
        aria-label="{{ __('Go to Cart') }}"
      >
        <div class="label">
          <div class="title">{{ __('Cart') }}</div>
          <div class="sub">{{ count($cart) }} {{ __('items') }}</div>
        </div>
        <i class="bi bi-bag icon" aria-hidden="true"></i>
      </button>
    </div>
  </nav>

  {{-- Keeps page content visible above the fixed bar --}}
  <div class="m-mobile-switcher-spacer"></div>
</section>