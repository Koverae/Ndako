@section('title', $pos->name ?? 'Kitchen Display')

@section('styles')
<style>
  /* animations & utilities */
  @keyframes k-fade-in{from{opacity:0}to{opacity:1}}
  @keyframes k-fade-up{from{opacity:.4;transform:translateY(6px)}to{opacity:1;transform:none}}
  .animate-fade-in{animation:k-fade-in .18s ease}
  .animate-fade-up{animation:k-fade-up .22s ease}
  @media (prefers-reduced-motion:reduce){.animate-fade-in,.animate-fade-up{animation:none!important}}
  .h-screen-d{min-height:100dvh;height:100dvh}
  @media print {.d-print-none{display:none!important}}

  /* Header: station chips scroll on narrow viewports */
  .station-scroll{display:flex;gap:.25rem;align-items:center;overflow:auto;padding:.25rem .15rem}
  .station-scroll::-webkit-scrollbar{height:8px}
  .station-scroll::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:8px}
  .station-scroll:hover::-webkit-scrollbar-thumb{background:#c0c6cf}

  /* KDS Kanban — responsive */
  .kanban{display:grid;gap:16px;grid-template-columns:1fr}
  @media (min-width: 768px){ .kanban{grid-template-columns:repeat(2,minmax(260px,1fr))} }
  @media (min-width: 1200px){ .kanban{grid-template-columns:repeat(3,minmax(300px,1fr))} }

  .kan-col{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:14px;box-shadow:0 .25rem .75rem rgba(0,0,0,.05);display:flex;flex-direction:column;min-height:220px}
  .kan-head{padding:12px 14px;border-bottom:1px solid rgba(0,0,0,.06);display:flex;align-items:center;justify-content:space-between}
  .kan-head .title{font-weight:700;font-size:.95rem;letter-spacing:.02em}
  .badge-count{display:inline-flex;align-items:center;justify-content:center;min-width:24px;height:24px;padding:0 8px;border-radius:999px;background:#f3f4f6;font-weight:700;font-size:.72rem;color:#374151}

  /* Scrollable column body (and it stays scrollable on mobile) */
  .kan-body{
    padding:10px;display:flex;flex-direction:column;gap:10px;overflow:auto;
    max-height:calc(100dvh - 220px);
    -webkit-overflow-scrolling:touch; /* iOS momentum */
    touch-action: pan-y;               /* allow vertical scrolling while touching */
    overscroll-behavior: contain;      /* keep scroll contained */
  }
  /* Entire body is a dropzone */
  .dropzone{min-height:120px;border-radius:10px;transition:background .12s ease,outline .12s ease, box-shadow .12s ease;outline:2px dashed transparent;position:relative}
  .dropzone.drag-over{background:rgba(1,126,132,.06);outline-color:rgba(1,126,132,.35);box-shadow:inset 0 0 0 2px rgba(1,126,132,.12)}
  .dropzone.drag-over::after{
    content:'{{ __("Release to move") }}';
    position:absolute;inset:auto 10px 8px auto;
    font-size:.72rem;color:#0e6163;background:#e6f5f5;border-radius:8px;padding:2px 8px;border:1px solid rgba(1,126,132,.25)
  }

  .ticket{background:#fff;border:1px solid rgba(0,0,0,.06);border-radius:12px;padding:10px;transition:transform .12s ease,box-shadow .12s ease,border-color .12s ease}
  .ticket:hover{transform:translateY(-1px);box-shadow:0 .5rem 1rem rgba(0,0,0,.06)}
  .ticket-head{display:flex;align-items:center;justify-content:space-between;gap:8px}
  .ticket-id{font-weight:600;font-size:.95rem}
  .ticket-sub{color:#6b7280;font-size:.8rem}
  .chip{display:inline-flex;align-items:center;gap:6px;border:1px solid rgba(0,0,0,.08);border-radius:999px;padding:.2rem .5rem;background:#fafafa;font-size:.75rem;font-weight:600}

  /* Ticket note (compact → expandable) */
    .ticket-note{margin-top:10px;border-top:1px solid rgba(0,0,0,.06);padding-top:8px}
    .note-toggle{
    display:flex;align-items:center;justify-content:space-between;gap:.5rem;
    width:100%;background:#fafafa;border:1px solid rgba(0,0,0,.06);
    border-radius:10px;padding:.45rem .6rem;font-weight:700;line-height:1.2
    }
    .note-pill{display:inline-flex;align-items:center;gap:.4rem;background:#eef6f7;
    color:#017E84;border:1px solid rgba(1,126,132,.18);border-radius:999px;
    padding:.15rem .55rem;font-size:.75rem;font-weight:800
    }
    .note-body{
    margin-top:.5rem;background:#fff;border:1px solid rgba(0,0,0,.06);
    border-radius:10px;padding:.65rem .7rem;color:#374151;font-size:.95rem
    }
    .note-empty{color:#9ca3af;font-size:.9rem}
    .note-preview{
    display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
    overflow:hidden;max-height:3.2em
    }
    @media (max-width:576px){
    .note-toggle{padding:.5rem .6rem}
    .note-body{font-size:.95rem}
    }

  .items{margin-top:8px;display:flex;flex-direction:column;gap:6px}
  .item{background:#fbfbfb;border:1px solid rgba(0,0,0,.06);border-radius:10px;padding:8px;display:flex;align-items:flex-start;justify-content:space-between;gap:8px}

  .qty{font-weight:800;min-width:26px;text-align:center;background:#eef6f7;color:#017E84;border-radius:8px;padding:2px 6px}
  .name{font-weight:600}
  .note{color:#6b7280;font-size:.78rem}
  .empty{color:#9ca3af;font-size:.9rem;padding:16px;text-align:center}

  /* Real-feel drag: grab / grabbing cursors + subtle drag styling (mouse/trackpad) */
  .ticket, .item{cursor:grab; user-select:none; -webkit-user-drag: element}
  .ticket.dragging, .item.dragging{cursor:grabbing; opacity:.95; transform:scale(.995); box-shadow:0 .75rem 1.25rem rgba(0,0,0,.09)}
  /* Global (ensures pointer shows grabbing across doc while dragging) */
  html.kds-grabbing, html.kds-grabbing *{cursor:grabbing !important}

  /* Touch pick mode hint (coarse pointers) */
  .kds-hint{
    position:fixed;left:50%;bottom:12px;transform:translateX(-50%);
    background:#0E6163;color:#fff;border-radius:999px;padding:.4rem .8rem;font-size:.8rem;font-weight:700;box-shadow:0 .5rem 1rem rgba(0,0,0,.15);z-index:1040
  }
  [x-cloak]{ display:none !important; }
</style>
@endsection

<main
  class="relative main"
  x-data="posRoot(@entangle('isLocked').live)"
  {{-- :inert="isLocked" --}}
>

  {{-- Lock Screen (default hidden; set false on init) --}}
  <div
    x-show="isLocked" x-cloak
    x-transition.opacity
    style="z-index:99999;"
    class="fixed inset-0 {{ $isLocked ? '' : 'd-none' }} d-flex align-items-center justify-content-center bg-opacity-75 d-print-none bg-body-secondary backdrop-blur animate-fade-in"
    role="dialog" aria-modal="true" aria-labelledby="lockscreen-time"
  >
    <div class="relative d-flex flex-column align-items-center justify-content-center w-100 h-100 bg-white">
      <div class="top-0 px-4 py-4 position-absolute start-0 end-0 d-flex justify-content-between align-items-center" style="width:100%;">
        <div>
          <div id="lockscreen-datetime" class="justify-content-between px-4 py-3 bg-opacity-75 d-flex align-items-center rounded-3"
               style="backdrop-filter: blur(6px); letter-spacing:.02em; min-width:280px;">
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
          <img src="{{ asset('assets/images/logo/ndako.png') }}" alt="Ndako Logo" style="height:60px;" loading="lazy" decoding="async" />
        </div>
      </div>

      <div class="flex-grow d-flex justify-content-center align-items-center w-100">
        <button
          class="gap-2 p-5 bg-white cursor-pointer text-dark fw-semibold fs-2 border-1 bg-opacity-90 align-items-center animate-fade-up"
          style="transition: box-shadow .2s; height:200px; border-radius:10px;"
          @click="isLocked=false"
        >
          <i class="fas fa-utensils" style="font-size:45px;" aria-hidden="true"></i>
          <div>{{ __('Open KDS') }}</div>
        </button>
      </div>

      <div class="bottom-0 pb-4 position-absolute start-0 end-0 d-flex justify-content-center align-items-center w-100">
        <button wire:click="$dispatch('go-backend')" class="px-5 py-2 shadow-sm btn btn-outline-dark rounded-pill fw-semibold fs-4">
          <i class="bi bi-gear me-2" aria-hidden="true"></i> {{ __('Backend') }}
        </button>
      </div>
    </div>
  </div>
  {{-- /Lock Screen --}}

  {{-- Navbar --}}
  <nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky dark:bg-gray-800">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
              aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 m-0">
        <a href="#" aria-label="Home">
          <img src="{{ asset('assets/images/logo/ndako.png') }}" class="navbar-brand-image normal" alt="Ndako" loading="lazy" decoding="async">
          <img src="{{ asset('assets/images/logo/ndako-white.png') }}" class="navbar-brand-image dark" alt="Ndako" loading="lazy" decoding="async">
        </a>
      </h1>

      <div class="flex-row navbar-nav order-md-last">
        <div class="d-md-flex d-flex">
          <div class="nav-item dropdown d-md-flex me-3">
            <a href="#" class="px-0 nav-link text-dark" data-bs-toggle="dropdown" id="dropdownMenuButton" title="{{ __('Translate') }}" data-bs-placement="bottom" aria-label="{{ __('Translate') }}">
              <i class="bi bi-translate" style="font-size:16px;"></i>
            </a>
          </div>

          <div class="nav-item dropdown">
            <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="{{ __('Open user menu') }}">
              <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
            </a>
            <div class="p-0 dropdown-menu dark-menu pos-burger-menu-items dropdown-menu-end dropdown-menu-arrow">
              <div class="p-2 pb-3 mb-2 border-bottom">
                <span class="text-center btn btn-lg w-100 pos-customer-screen dark:bg-gray-700 dark:text-gray-200">
                  <i class="fas fa-desktop"></i>
                </span>
              </div>
              <div class="p-2 rounded menu-items">
                <span class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 toggle-theme">
                  <span class="theme-label">{{ __('Switch to Dark Mode') }}</span>
                </span>
                <span wire:click="$dispatch('go-backend')" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Backend') }}
                </span>
                <span @click="isLocked=true" class="cursor-pointer dropdown-item fs-4 kover-navlink rounded-1 dark:text-gray-200">
                  {{ __('Lock Screen') }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- responsive station chips --}}
      <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
          <ul class="navbar-nav w-100">
            <div class="station-scroll">
              @foreach($this->stations as $key => $label)
                <li class="cursor-pointer nav-item" data-turbolinks style="white-space:nowrap;">
                  <a class="nav-link kover-navlink {{ $station === $key ? 'selected' : '' }} dark:text-gray-200"
                     wire:click="switchStation('{{ $key }}')" style="margin-right:5px;">
                    <span class="nav-link-title">{{ __($label) }}</span>
                  </a>
                </li>
              @endforeach
              <li class="nav-item d-flex align-items-center ms-auto" style="min-width:160px">
                <select class="form-select form-select-sm" wire:model.live="sinceMins">
                  <option value="60">{{ __('Last 1h') }}</option>
                  <option value="90">{{ __('Last 1.5h') }}</option>
                  <option value="120">{{ __('Last 2h') }}</option>
                  <option value="240">{{ __('Last 4h') }}</option>
                  <option value="480">{{ __('Last 8h') }}</option>
                </select>
              </li>
            </div>
          </ul>
        </div>
      </div>
      {{-- /responsive station chips --}}
    </div>
  </nav>
  {{-- /Navbar --}}

  <div class="container-fluid py-3 h-screen-d overflow-y-auto d-print-none"
       x-data="kdsDnD(@this)"
       wire:key="kds-board"
       wire:poll.visible.2s>
    {{-- Touch pick mode hint --}}
    <div x-show="pickMode" x-transition.opacity class="kds-hint">
      {{ __('Tap a column to drop') }}
    </div>

    <div class="kanban" role="list" aria-label="{{ __('Kitchen tickets') }}">
      {{-- New --}}
      <section class="kan-col" aria-labelledby="col-new">
        <header class="kan-head">
          <div class="title" id="col-new">{{ __('New') }}</div>
          <div class="badge-count" aria-live="polite">{{ count($queued ?? []) }}</div>
        </header>
        <div class="kan-body dropzone"
             :class="{'drag-over': dragOver === 'queued'}"
             @dragover.prevent="dragOver='queued'; setDropEffect($event)"
             @dragleave="dragOver=null"
             @drop="handleDrop('queued',$event)"
             @click="handleTapDrop('queued')">
          @if(empty($queued))
            <div class="empty">{{ __('No new tickets') }}</div>
          @else
            @foreach($queued as $block)
              @php $order = $block->order; @endphp
              <article class="ticket" role="listitem" draggable="true"
                       @dragstart="dragOrder({{ $order->id }}, $event)"
                       @dragend="dragEnd($event)"
                       @pointerdown="touchStart('order', {{ $order->id }}, $event)"
                       @pointerup="touchEnd($event)"
                       @pointercancel="touchCancel($event)"
                       @pointerleave="touchCancel($event)"
                       wire:key="queued-order-{{ $order->id }}">
                <div class="ticket-head">
                  <div>
                    <div class="ticket-id">#{{ $order->reference ?? $order->id }}</div>
                    <div class="ticket-sub">{{ $order->table->table_name ?? __('Direct') }}</div>
                  </div>
                  <span class="chip"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($order->date)->diffForHumans(null,true) }}</span>
                </div>
                <div class="items">
                  @foreach($block->items as $it)
                    <div class="item" draggable="true"
                         @dragstart="dragItem({{ $it->id }}, $event)"
                         @dragend="dragEnd($event)"
                         @pointerdown="touchStart('item', {{ $it->id }}, $event)"
                         @pointerup="touchEnd($event)"
                         @pointercancel="touchCancel($event)"
                         @pointerleave="touchCancel($event)"
                         wire:key="queued-item-{{ $it->id }}">
                      <div class="d-flex align-items-start gap-2">
                        <span class="qty">{{ $it->quantity }}</span>
                        <div>
                          <div class="name">{{ $it->product->product_name ?? __('Item') }}</div>
                          @if(!empty($it->note))
                            <div class="note">{{ $it->note }}</div>
                          @endif
                        </div>
                      </div>
                      <div class="d-none d-md-block">
                        <span class="chip"><i class="bi bi-arrows-move"></i> {{ __('Drag →') }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>

                {{-- NOTE SECTION (ticket) --}}
                @php
                  $orderNote = $order->note ?? $order->customer_note ?? $order->kitchen_note ?? null;
                @endphp
                <div class="ticket-note" x-data="{ open: false }">
                  <button type="button" class="note-toggle w-100 d-flex justify-content-between" @click="open = !open" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                      <span class="note-pill"><i class="bi bi-chat-dots"></i> {{ __('Note') }}</span>
                      <span class="note-preview">
                        {{ $orderNote ? Str::limit(trim($orderNote), 120) : __('No note for this ticket') }}
                      </span>
                    </span>
                    <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                  </button>
                  <div class="note-body" x-show="open" x-transition.opacity>
                    @if($orderNote)
                      {{ $orderNote }}
                    @else
                      <div class="note-empty">{{ __('No customer note was added for this order.') }}</div>
                    @endif
                  </div>
                </div>
                {{-- /NOTE SECTION --}}
              </article>
            @endforeach
          @endif
        </div>
      </section>

      {{-- Preparing --}}
      <section class="kan-col" aria-labelledby="col-prep">
        <header class="kan-head">
          <div class="title" id="col-prep">{{ __('Preparing') }}</div>
          <div class="badge-count" aria-live="polite">{{ count($preparing ?? []) }}</div>
        </header>
        <div class="kan-body dropzone"
             :class="{'drag-over': dragOver === 'preparing'}"
             @dragover.prevent="dragOver='preparing'; setDropEffect($event)"
             @dragleave="dragOver=null"
             @drop="handleDrop('preparing',$event)"
             @click="handleTapDrop('preparing')">
          @if(empty($preparing))
            <div class="empty">{{ __('Nothing in prep') }}</div>
          @else
            @foreach($preparing as $block)
              @php $order = $block->order; @endphp
              <article class="ticket" role="listitem" draggable="true"
                       @dragstart="dragOrder({{ $order->id }}, $event)"
                       @dragend="dragEnd($event)"
                       @pointerdown="touchStart('order', {{ $order->id }}, $event)"
                       @pointerup="touchEnd($event)"
                       @pointercancel="touchCancel($event)"
                       @pointerleave="touchCancel($event)"
                       wire:key="prep-order-{{ $order->id }}">
                <div class="ticket-head">
                  <div>
                    <div class="ticket-id">#{{ $order->reference ?? $order->id }}</div>
                    <div class="ticket-sub">{{ $order->table->table_name ?? __('Direct') }}</div>
                  </div>
                  <span class="chip"><i class="bi bi-tools"></i> {{ __('In prep') }}</span>
                </div>
                <div class="items">
                  @foreach($block->items as $it)
                    <div class="item" draggable="true"
                         @dragstart="dragItem({{ $it->id }}, $event)"
                         @dragend="dragEnd($event)"
                         @pointerdown="touchStart('item', {{ $it->id }}, $event)"
                         @pointerup="touchEnd($event)"
                         @pointercancel="touchCancel($event)"
                         @pointerleave="touchCancel($event)"
                         wire:key="prep-item-{{ $it->id }}">
                      <div class="d-flex align-items-start gap-2">
                        <span class="qty">{{ $it->quantity }}</span>
                        <div class="name">{{ $it->product->product_name ?? __('Item') }}</div>
                      </div>
                      <div class="d-none d-md-block">
                        <span class="chip"><i class="bi bi-arrows-move"></i> {{ __('Drag →') }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>

                {{-- NOTE SECTION (ticket) --}}
                @php
                  $orderNote = $order->note ?? $order->customer_note ?? $order->kitchen_note ?? null;
                @endphp
                <div class="ticket-note" x-data="{ open: false }">
                  <button type="button" class="note-toggle w-100 d-flex justify-content-between" @click="open = !open" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2">
                      <span class="note-pill"><i class="bi bi-chat-dots"></i> {{ __('Note') }}</span>
                      <span class="note-preview">
                        {{ $orderNote ? Str::limit(trim($orderNote), 120) : __('No note for this ticket') }}
                      </span>
                    </span>
                    <i class="bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                  </button>
                  <div class="note-body" x-show="open" x-transition.opacity>
                    @if($orderNote)
                      {{ $orderNote }}
                    @else
                      <div class="note-empty">{{ __('No customer note was added for this order.') }}</div>
                    @endif
                  </div>
                </div>
                {{-- /NOTE SECTION --}}
              </article>
            @endforeach
          @endif
        </div>
      </section>

      {{-- Ready --}}
      <section class="kan-col" aria-labelledby="col-ready">
        <header class="kan-head">
          <div class="title" id="col-ready">{{ __('Ready') }}</div>
          <div class="badge-count" aria-live="polite">{{ count($ready ?? []) }}</div>
        </header>
        <div class="kan-body dropzone"
             :class="{'drag-over': dragOver === 'ready'}"
             @dragover.prevent="dragOver='ready'; setDropEffect($event)"
             @dragleave="dragOver=null"
             @drop="handleDrop('ready',$event)"
             @click="handleTapDrop('ready')">
          @if(empty($ready))
            <div class="empty">{{ __('No plates ready') }}</div>
          @else
            @foreach($ready as $block)
              @php $order = $block->order; @endphp
              <article class="ticket" role="listitem" draggable="true"
                       @dragstart="dragOrder({{ $order->id }}, $event)"
                       @dragend="dragEnd($event)"
                       @pointerdown="touchStart('order', {{ $order->id }}, $event)"
                       @pointerup="touchEnd($event)"
                       @pointercancel="touchCancel($event)"
                       @pointerleave="touchCancel($event)"
                       wire:key="ready-order-{{ $order->id }}">
                <div class="ticket-head">
                  <div>
                    <div class="ticket-id">#{{ $order->reference ?? $order->id }}</div>
                    <div class="ticket-sub">{{ $order->table->table_name ?? __('Direct') }}</div>
                  </div>
                  <span class="chip"><i class="bi bi-check2-circle"></i> {{ __('Ready') }}</span>
                </div>
                <div class="items">
                  @foreach($block->items as $it)
                    <div class="item" draggable="true"
                         @dragstart="dragItem({{ $it->id }}, $event)"
                         @dragend="dragEnd($event)"
                         @pointerdown="touchStart('item', {{ $it->id }}, $event)"
                         @pointerup="touchEnd($event)"
                         @pointercancel="touchCancel($event)"
                         @pointerleave="touchCancel($event)"
                         wire:key="ready-item-{{ $it->id }}">
                      <div class="d-flex align-items-start gap-2">
                        <span class="qty">{{ $it->quantity }}</span>
                        <div class="name">{{ $it->product->product_name ?? __('Item') }}</div>
                      </div>
                      <div class="d-none d-md-block">
                        <button class="btn btn-sm btn-success"
                                @click.prevent="$wire.moveItem({{ $it->id }}, 'delivered')">
                          {{ __('Bump') }}
                        </button>
                      </div>
                    </div>
                  @endforeach
                </div>
                <div class="mt-2 d-flex justify-content-end">
                  <button class="btn btn-sm btn-success"
                          @click.prevent="$wire.moveOrder({{ $order->id }}, 'delivered')">
                    {{ __('Bump order') }}
                  </button>
                </div>

                {{-- NOTE SECTION (ticket) --}}
                @php
                  $orderNote = $order->note ?? $order->customer_note ?? $order->kitchen_note ?? null;
                @endphp
                <div class="ticket-note mt-3" x-data="{ open: false }">
                  <button type="button" class="note-toggle w-100 d-flex justify-content-between" @click="open = !open" aria-expanded="false">
                    <span class="d-flex align-items-center gap-2 text-start">
                      <span class="note-pill"><i class="bi bi-chat-dots"></i> {{ __('Note') }}</span>
                      <span class="note-preview">
                        {{ $orderNote ? Str::limit(trim($orderNote), 120) : __('No note for this ticket') }}
                      </span>
                    </span>
                    <i class="text-end bi" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                  </button>
                  <div class="note-body" x-show="open" x-transition.opacity>
                    @if($orderNote)
                      {{ $orderNote }}
                    @else
                      <div class="note-empty">{{ __('No customer note was added for this order.') }}</div>
                    @endif
                  </div>
                </div>
                {{-- /NOTE SECTION --}}
              </article>
            @endforeach
          @endif
        </div>
      </section>

    </div>
  </div>
  {{-- /KDS Board --}}
</main>

@push('scripts')
<script>
/* ================= Root clock/lock ================= */
function posRoot(lockedEntangle){
  return {
    isLocked: lockedEntangle,
    clockInterval: null,
    init(){
      // Force default unlocked on first paint
    //   this.$nextTick(() => { this.isLocked = false; try{ this.$wire.set('isLocked', false); }catch(_){} });

      this.startClock();
      window.addEventListener('pageshow',e=>{ if(e.persisted) this.startClock() },{passive:true});
      window.addEventListener('pagehide',()=>this.stopClock(),{passive:true});
    },
    startClock(){
      const t=document.getElementById('lockscreen-time'),
            w=document.getElementById('lockscreen-weekday'),
            d=document.getElementById('lockscreen-full-date');
      if(!t||!w||!d) return;
      const render=()=>{
        const now=new Date();
        t.textContent=now.toLocaleTimeString(undefined,{hour:'2-digit',minute:'2-digit'});
        w.textContent=now.toLocaleDateString(undefined,{weekday:'short'});
        d.textContent=now.toLocaleDateString(undefined,{day:'2-digit',month:'short',year:'numeric'});
      };
      render(); this.stopClock(); this.clockInterval=setInterval(render,1000);
    },
    stopClock(){ if(this.clockInterval){ clearInterval(this.clockInterval); this.clockInterval=null; } }
  }
}

/* ================= KDS drag & drop (mouse + touch) ================= */
function kdsDnD($wire){
  const hasCoarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;

  return {
    /* shared state */
    dragOver:null, dragType:null, dragId:null, dragEl:null,
    /* touch pick mode */
    pickMode:false, pickType:null, pickId:null, longPressTimer:null,
    longPressDelay:220,

    /* mouse */
    setDropEffect(e){ try{ e.dataTransfer.dropEffect='move'; }catch(_){} },

    dragOrder(id,e){ if(hasCoarse) return; this._startDrag('order', id, e); },
    dragItem(id,e){  if(hasCoarse) return; this._startDrag('item',  id, e); },

    _startDrag(type, id, e){
      this.dragType = type; this.dragId = id; this.dragEl = e.currentTarget;
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/kds', JSON.stringify({type, id}));
      document.documentElement.classList.add('kds-grabbing');
      this.dragEl.classList.add('dragging');
      try{
        const clone = this.dragEl.cloneNode(true);
        clone.style.position='absolute'; clone.style.top='-9999px'; clone.style.pointerEvents='none'; clone.style.transform='scale(.98)';
        document.body.appendChild(clone);
        e.dataTransfer.setDragImage(clone, Math.min(120, clone.offsetWidth/2), 20);
        setTimeout(()=>clone.remove(), 0);
      }catch(_){}
      this.dragEl.setAttribute('aria-grabbed','true');
    },

    dragEnd(){ this._resetDragState(); },

    handleDrop(target,e){
      this.dragOver=null;
      try{
        const data = JSON.parse(e.dataTransfer.getData('text/kds')||'');
        if(data.type==='order') $wire.moveOrder(data.id, target);
        if(data.type==='item')  $wire.moveItem(data.id, target);
      }catch(_){}
      this._resetDragState();
    },

    _resetDragState(){
      document.documentElement.classList.remove('kds-grabbing');
      if(this.dragEl){
        this.dragEl.classList.remove('dragging');
        this.dragEl.removeAttribute('aria-grabbed');
      }
      this.dragEl=null; this.dragId=null; this.dragType=null;
      this._endPick();
    },

    /* touch: long-press to pick, then tap a column body to drop */
    touchStart(type,id,e){
      if(!hasCoarse) return;
      this.longPressTimer = setTimeout(()=>{
        this.pickMode = true; this.pickType = type; this.pickId = id;
        document.documentElement.classList.add('kds-grabbing');
        if(navigator.vibrate){ try{ navigator.vibrate(10); }catch(_){} }
      }, this.longPressDelay);
    },
    touchEnd(){ if(!this.pickMode) this._clearLongPress(); },
    touchCancel(){ this._clearLongPress(); this._endPick(); },

    _clearLongPress(){ if(this.longPressTimer){ clearTimeout(this.longPressTimer); this.longPressTimer=null; } },
    _endPick(){
      this.pickMode=false; this.pickType=null; this.pickId=null; this._clearLongPress();
      document.documentElement.classList.remove('kds-grabbing');
    },

    handleTapDrop(target){
      if(!this.pickMode) return;
      if(this.pickType==='order') $wire.moveOrder(this.pickId, target);
      if(this.pickType==='item')  $wire.moveItem(this.pickId, target);
      this._endPick();
    }
  }
}

/* ================= Theme toggle ================= */
(() => {
  const html=document.documentElement, toggle=document.querySelector('.toggle-theme'); if(!toggle) return;
  const label=toggle.querySelector('.theme-label');
  const apply=(m)=>{ html.setAttribute('data-theme',m); localStorage.setItem('theme',m); if(label) label.textContent = m==='dark' ? '{{ __('Switch to Light Mode') }}' : '{{ __('Switch to Dark Mode') }}'; };
  let cur=localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'); apply(cur);
  toggle.addEventListener('click',()=>apply(cur = (cur==='light'?'dark':'light')));
  const mq=window.matchMedia('(prefers-color-scheme: dark)'); mq.addEventListener?.('change',(e)=>{ if(!localStorage.getItem('theme')) apply(e.matches?'dark':'light'); });
})();


// ===================== New-ticket sound (minimal, non-intrusive) =====================

(() => {
    // Play Sound
    Livewire.on('play-sound', (payload) => {
      try { playSound(payload?.type); } catch (e) { /* no-op if playSound missing */ }
    });
})();

</script>
@endpush
