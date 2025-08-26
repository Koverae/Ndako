
<section class="col-lg-5 col-md-12 {{ $tab == 'pay' ? 'd-none d-lg-block' : '' }}" id="checkout-box">
  <div class="border-0 shadow-sm card h-screen-d">

    <!-- Header -->
    <div class="co-section-head">
      <h3 class="co-title m-0 d-flex">
        {{ __('Checkout') }}
        <span class="co-subtle ms-2">· {{ count($cart) }} {{ __('items') }}</span>
      </h3>
      <div class="co-subtle gap-2">
        @if($selectedTable)
          <i class="bi bi-geo-alt"></i> {{ $selectedTable->table_name }}
        @else
          <i class="bi bi-bag"></i> {{ __('Direct Sale') }}
        @endif

        {{-- NEW: overall KDS status pill --}}
        @if($kdsOverall)
            <span class="kds-pill kds-{{ $kdsOverall }}">
            @switch($kdsOverall)
                @case('ready')      <i class="bi bi-check2-circle"></i> {{ __('Ready') }} @break
                @case('queued')     <i class="bi bi-clock"></i> {{ __('Queued') }} @break
                @default            <i class="bi bi-tools"></i> {{ __('Preparing') }}
            @endswitch
            </span>
        @endif

        {{-- NEW: compact counts (Queued · Prep · Ready) --}}
        <span class="kds-counts">
            <span class="kds-chip" title="{{ __('Queued') }}"><i class="bi bi-clock"></i> {{ $kdsSummary['queued'] }}</span>
            <span class="kds-chip" title="{{ __('Preparing') }}"><i class="bi bi-tools"></i> {{ $kdsSummary['preparing'] }}</span>
            <span class="kds-chip" title="{{ __('Ready') }}"><i class="bi bi-check2-circle"></i> {{ $kdsSummary['ready'] }}</span>
        </span>

        <button
          wire:click="cancelOrder('{{ $this->order?->id }}')"
          wire:confirm="{{ __('Are you sure to reset the cart?') }}"
          class="btn btn-outline-danger btn-sm rounded-3 fw-semibold d-flex gap-1 {{ empty($cart) ? 'disabled' : '' }}">
          <i class="fas fa-trash"></i> <span>{{ __('Cancel') }}</span>
        </button>
      </div>
    </div>

    <!-- Actions -->
    <div class="co-actions">
      <button onclick="Livewire.dispatch('openModal', {component: 'pos::modal.service-type-modal'})"
              class="btn btn-sm fw-semibold">
        @if($selectedService)
          <i class="{{ $selectedService['icon'] }}"></i> <span>{{ $selectedService['label'] }}</span>
        @else
          <i class="bi bi-truck"></i> {{ __('Service Type') }}
        @endif
      </button>

      <button class="btn btn-sm fw-semibold"
              wire:click="switchInterface('tables')">
        <i class="fas fa-chair"></i>
        <span>{{ $selectedTable->table_name ?? __('Table') }}</span>
      </button>

      <button class="btn btn-sm fw-semibold"
              onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-modal'})">
        <i class="fas fa-user"></i>
        <span>{{ $this->guest ? Str::limit($this->guest->name, 16) : __('Guest') }}</span>
      </button>

      <button class="btn btn-sm fw-semibold"
              data-bs-toggle="collapse" data-bs-target="#customer-note">
        <i class="bi bi-stickies"></i> <span>{{ __('Customer Note') }}</span>
        @if(!empty($orderNote)) <span class="badge text-bg-info ms-1">1</span>@endif
      </button>

      {{-- Advanced actions (inline on ≥sm) --}}
      <div class="co-toolbar d-none d-sm-flex">
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="sendOrderToKds"><i class="bi bi-send"></i> {{ __('Send to KDS') }}</button>
        @can('send_to_kitchen')
        @endcan
        @can('hold_resume_order')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="toggleHold"><i class="bi bi-pause-circle"></i> {{ $onHold ? __('Resume') : __('Hold') }}</button>
        @endcan
        @can('split_bill')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openSplitBill"><i class="bi bi-scissors"></i> {{ __('Split') }}</button>
        @endcan
        @can('print_kitchen_ticket')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="printKitchenTicket"><i class="bi bi-printer"></i> {{ __('KOT') }}</button>
        @endcan
        @can('transfer_order')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openTransferOrder"><i class="bi bi-arrow-left-right"></i> {{ __('Transfer') }}</button>
        @endcan
        @can('move_table')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openMoveTable"><i class="bi bi-arrow-repeat"></i> {{ __('Move Table') }}</button>
        @endcan
        @can('merge_bills')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openMergeBills"><i class="bi bi-link-45deg"></i> {{ __('Merge') }}</button>
        @endcan

        @can('send_to_kitchen')
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="toggleRush"><i class="bi bi-lightning-charge"></i> {{ $rush ? __('Unrush') : __('Rush') }}</button>
        <button class="btn btn-slim fw-semibold btn-ghost" wire:click="openFireSchedule"><i class="bi bi-alarm"></i> {{ __('Fire Later') }}</button>
        @endcan
      </div>

      {{-- Mobile "More" (same actions in a tidy menu) --}}
      <div class="dropdown d-sm-none">
        <button class="btn btn-slim fw-semibold btn-ghost" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-three-dots"></i> {{ __('More') }}
        </button>
        <div class="dropdown-menu dropdown-menu-end co-menu">
          @can('send_to_kitchen')
            <button class="dropdown-item" wire:click="sendOrderToKds"><i class="bi bi-send"></i> {{ __('Send to KDS') }}</button>
          @endcan
          @can('hold_resume_order')
            <button class="dropdown-item" wire:click="toggleHold"><i class="bi bi-pause-circle"></i> {{ $onHold ? __('Resume') : __('Hold') }}</button>
          @endcan
          @can('split_bill')
            <button class="dropdown-item" wire:click="openSplitBill"><i class="bi bi-scissors"></i> {{ __('Split') }}</button>
          @endcan
          @can('print_kitchen_ticket')
            <button class="dropdown-item" wire:click="printKitchenTicket"><i class="bi bi-printer"></i> {{ __('KOT') }}</button>
          @endcan
          @can('transfer_order')
            <button class="dropdown-item" wire:click="openTransferOrder"><i class="bi bi-arrow-left-right"></i> {{ __('Transfer') }}</button>
          @endcan
          @can('move_table')
            <button class="dropdown-item" wire:click="openMoveTable"><i class="bi bi-arrow-repeat"></i> {{ __('Move Table') }}</button>
          @endcan
          @can('merge_bills')
            <button class="dropdown-item" wire:click="openMergeBills"><i class="bi bi-link-45deg"></i> {{ __('Merge') }}</button>
          @endcan

          <div class="dropdown-divider"></div>

          @can('send_to_kitchen')
          <button class="dropdown-item" wire:click="toggleRush"><i class="bi bi-lightning-charge"></i> {{ $rush ? __('Unrush') : __('Rush') }}</button>
          <button class="dropdown-item" wire:click="openFireSchedule"><i class="bi bi-alarm"></i> {{ __('Fire Later') }}</button>
          @endcan
        </div>
      </div>

      </div>
      @can('send_to_kitchen')
      <div class="btn-group btn-group-sm p-2" role="group" aria-label="Courses">
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('starters')"><i class="bi bi-egg-fried"></i> {{ __('Fire Starters') }}</button>
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('mains')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-fork-knife" viewBox="0 0 16 16">
                <path d="M13 .5c0-.276-.226-.506-.498-.465-1.703.257-2.94 2.012-3 8.462a.5.5 0 0 0 .498.5c.56.01 1 .13 1 1.003v5.5a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5zM4.25 0a.25.25 0 0 1 .25.25v5.122a.128.128 0 0 0 .256.006l.233-5.14A.25.25 0 0 1 5.24 0h.522a.25.25 0 0 1 .25.238l.233 5.14a.128.128 0 0 0 .256-.006V.25A.25.25 0 0 1 6.75 0h.29a.5.5 0 0 1 .498.458l.423 5.07a1.69 1.69 0 0 1-1.059 1.711l-.053.022a.92.92 0 0 0-.58.884L6.47 15a.971.971 0 1 1-1.942 0l.202-6.855a.92.92 0 0 0-.58-.884l-.053-.022a1.69 1.69 0 0 1-1.059-1.712L3.462.458A.5.5 0 0 1 3.96 0z"/>
            </svg> {{ __('Fire Mains') }}</button>
        <button class="btn btn-outline-secondary fire fw-semibold" wire:click="fireCourse('desserts')"><i class="bi bi-cup-straw"></i> {{ __('Fire Desserts') }}</button>
      </div>
      @endcan


    <!-- Customer Note -->
    <div id="customer-note" class="collapse soft-panel">
      <div class="p-3">
        <label class="form-label mb-2">{{ __('Note to kitchen') }}</label>

        <div class="d-flex flex-wrap gap-1 mb-2">
          @foreach (['No onions','Extra spicy','Allergy: nuts','No salt','On side','Well done'] as $q)
            <button type="button" class="note-chip"
                    wire:click="$set('orderNote', trim(($orderNote ?? '') + ' ' + '{{ $q }}'))">
              {{ $q }}
            </button>
          @endforeach
        </div>

        <textarea
          class="form-control"
          rows="3"
          placeholder="{{ __('No onions, extra spicy, send when table 7 is seated…') }}"
          wire:model="orderNote"></textarea>

        <div class="d-flex align-items-center justify-content-between mt-2">
          <small class="text-muted">{{ __('Visible on KDS') }}</small>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary"
                    wire:click="$set('orderNote','')">
              {{ __('Clear') }}
            </button>
            <button class="btn btn-sm btn-primary"
                    wire:click="saveOrderNote">
              {{ __('Save note') }}
            </button>
          </div>
        </div>

        @if (session()->has('note_saved'))
          <div class="alert alert-success py-1 px-2 mt-2 mb-0 small">{{ session('note_saved') }}</div>
        @endif
      </div>
    </div>

    <!-- Cart -->
    <div class="cart-scroll">
      @forelse ($cart as $item)
        <ul class="m-0 p-0" wire:click="selectProduct('{{ $item['id'] }}')">
          <li class="orderline {{ $selectedProductId == $item['id'] ? 'selected' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
              <div class="pe-2">
                <div class="product-name text-truncate">{{ $item['name'] }}</div>
                <div class="meta mt-1">
                  <em class="qty fw-bold me-1">{{ $item['quantity'] }}</em>
                  × {{ format_currency($item['unit_price']) }}
                  @if ($item['discount'] > 0)
                    · <span class="text-success">{{ $item['discount'] }}% {{ __('off') }}</span>
                  @endif
                </div>
              </div>
              <div class="line-total">{{ format_currency($item['unit_price'] * $item['quantity']) }}</div>
            </div>
          </li>
        </ul>
      @empty
        <div class="empty-cart d-flex flex-column align-items-center justify-content-center py-5 text-muted">
          <i class="rotate-45 bi bi-cart-fill" style="font-size:62px;" aria-hidden="true"></i>
          <div class="lead mt-2">{{ __('No items in cart.') }}</div>
          <div class="small">{{ __('Add products from the left panel') }}</div>
        </div>
      @endforelse
    </div>

    <!-- Totals & Payment -->
    <div class="checkout-footer">
      <div class="co-totals">
        <div>
          <div class="co-amount">
            {{ __('Total') }}:
            <span class="total">{{ format_currency(($cartTotal ?? 0)) }}</span>
          </div>
          <div class="text-muted small">
            {{ __('Taxes') }}:
            <span class="tax">(+) {{ format_currency(($cartTax ?? 0)) }}</span>
          </div>
        </div>
        <div class="d-flex gap-2 w-auto">
          <button
            wire:click="processPayment"
            wire:loading.attr="disabled"
            class="btn btn-primary pay-cta {{ empty($cart) ? 'disabled' : '' }}">
            <i class="bi bi-credit-card-2-front"></i> {{ __('Payment') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Calculator (kept; spacing only) -->
    <div class="calculator_buttons d-flex bg-300 border-top">
      <div class="w-25 d-flex" id="vertical_buttons">
        <button
          wire:click="processPayment"
          wire:loading.attr="disabled"
          class="btn btn-light rounded-0 fw-bolder {{ empty($cart) ? 'disabled' : '' }}"
          id="pay" title="{{ __('Payment') }}">
          {{ __('Payment') }}
        </button>
      </div>

      <div
        x-data="calculatorComponent(@this)"
        x-init="
          window.addEventListener('keydown', (e) => {
            const tag=(e.target.tagName||'').toLowerCase();
            const typing = tag==='input'||tag==='textarea'||e.target.isContentEditable;
            if (document.querySelector('.row:not(.d-none)') && !typing && '{{ $interface }}'==='register') press(e.key);
          });
        "
        class="w-75 d-flex flex-wrap"
      >
        <template x-for="key in keys" :key="key.label + key.value">
          <button
            type="button"
            @click="press(key.value)"
            :class="['btn','rounded-0','fw-bolder', key.class, (key.mode && $wire.calculatorMode === key.value) ? 'selected' : '']"
            :style="key.style"
          >
            <template x-if="key.icon"><i :class="key.icon" aria-hidden="true"></i></template>
            <template x-if="!key.icon"><span x-text="key.label"></span></template>
          </button>
        </template>
      </div>
    </div>
    <!-- /Calculator -->

  </div>
</section>
