@props(['value'])

<div class="mt-3 container-fluid {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
  {{-- Header: Search + Add --}}
  <div class="mb-3 d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">

    {{-- Search --}}
    <div class="position-relative flex-grow-1">
      <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
      <input
        type="search"
        class="form-control ps-5"
        wire:model.live.debounce.400ms="search"
        placeholder="{{ __('Search guests by name or email...') }}"
        aria-label="{{ __('Search guests') }}"
      >
      {{-- Loading --}}
      <div class="position-absolute end-0 top-50 translate-middle-y me-3" wire:loading wire:target="search" aria-live="polite">
        <span class="spinner-border spinner-border-sm text-secondary" role="status" aria-hidden="true"></span>
        <span class="visually-hidden">{{ __('Searching…') }}</span>
      </div>
    </div>

    {{-- Add Guest --}}
    <button
      type="button"
      class="btn btn-primary fw-semibold d-flex align-items-center justify-content-center gap-2"
      onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})"
    >
      <i class="bi bi-person-plus"></i> {{ __('Add Guest') }}
    </button>
  </div>

  {{-- Guests grid --}}
  <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3" role="listbox" aria-label="{{ __('Guests') }}">
    @forelse($this->guests as $guest)
      @php $isSelected = $this->guest && $this->guest->id == $guest->id; @endphp
      <div class="col cursor-pointer" wire:key="guest-{{ $guest->id }}">
        <a
          class="card h-100 border-0 shadow-sm position-relative guest-card {{ $isSelected ? 'active-pick' : '' }}"
          wire:click="pickGuest('{{ $guest->id }}')"
          wire:navigate
          role="option"
          aria-selected="{{ $isSelected ? 'true' : 'false' }}"
          tabindex="0"
        >
          {{-- Selected badge --}}
          @if($isSelected)
            <span class="badge selected-badge text-bg-primary position-absolute top-0 end-0 m-2 d-inline-flex align-items-center gap-1" style="z-index: 9999;">
              <i class="bi bi-check2-circle"></i> {{ __('Selected') }}
            </span>
          @endif

          <div class="d-flex align-items-stretch">
            <img
              src="{{ $guest->avatar ? Storage::url('avatars/' . $guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}"
              alt="{{ $guest->name }}"
              class="object-fit-cover rounded-start"
              style="width:108px;height:108px;"
              loading="lazy"
            >

            <div class="card-body d-flex flex-column overflow-hidden">
              <div class="d-flex align-items-start justify-content-between">
                <h5 class="card-title mb-1 text-truncate">{{ $guest->name }}</h5>

                {{-- Edit button (does not trigger pickGuest) --}}
                <button
                  type="button"
                  class="btn btn-sm btn-light border d-inline-flex align-items-center"
                  onclick="event.stopPropagation(); Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: {{ $guest->id }} })"
                  aria-label="{{ __('Edit guest') }}"
                  title="{{ __('Edit guest') }}"
                >
                  <i class="bi bi-pencil-square"></i>
                </button>
              </div>

              <div class="small text-muted">
                <div class="text-truncate"><i class="bi bi-envelope me-1"></i>{{ $guest->email }}</div>
                <div class="text-truncate"><i class="bi bi-phone me-1"></i>{{ $guest->phone }}</div>
                <div class="text-truncate"><i class="bi bi-geo me-1"></i>{{ $guest->address ?? __('N/A') }}</div>
              </div>

              <div class="mt-auto d-flex align-items-center gap-2">
                @php $activeCount = $guest->bookings()->isActive()->count(); @endphp
                @if($activeCount >= 1)
                  <span class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">{{ __('Active') }}</span>
                @endif
                @if($guest->created_at)
                  <span class="badge text-bg-light border">{{ $guest->created_at->diffForHumans() }}</span>
                @endif
              </div>
            </div>
          </div>
        </a>
      </div>
    @empty
      {{-- Empty state --}}
      <div class="col-12">
        <div class="text-center py-5 border border-dashed rounded-3 bg-light-subtle">
          <i class="bi bi-people text-secondary" style="font-size:2.25rem;"></i>
          <p class="text-muted mt-2 mb-3">{{ __('No guests found. Try adjusting your search or add a new guest.') }}</p>
          <button
            type="button"
            class="btn btn-outline-primary"
            onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})"
          >
            <i class="bi bi-person-plus me-1"></i> {{ __('Add Guest') }}
          </button>
        </div>
      </div>
    @endforelse
  </div>
</div>

{{-- Scoped styles for clear selection --}}
<style>
  .guest-card {
    transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    border: 1px solid var(--bs-border-color, #dee2e6);
    overflow: hidden;
  }
  .guest-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.08);
  }
  .guest-card.active-pick {
    border: 2px solid var(--bs-primary) !important;
    box-shadow: 0 0 0 .25rem rgba(var(--bs-primary-rgb), .15) !important;
    position: relative;
  }
  /* Slim left accent bar when selected */
  .guest-card.active-pick::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0; bottom: 0;
    width: 4px;
    background: var(--bs-primary);
  }
  .object-fit-cover { object-fit: cover; }

  /* Selected badge pill */
  .selected-badge {
    padding: .25rem .5rem;
    border-radius: 999px;
    font-weight: 600;
  }
</style>
