@props(['value'])

<div class="row g-3 justify-content-lg-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
  {{-- Form card --}}
  <div class="col-12 col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white border-0 pb-0">
        <h6 class="mb-1">{{ __('Stay details') }}</h6>
        <p class="text-muted small mb-0">{{ __('Tell us how many people and your dates.') }}</p>
      </div>

      <div class="card-body">
        <div class="row g-3">

          {{-- People --}}
          <div class="col-12 col-md-4">
            <label for="people" class="form-label d-flex align-items-center gap-2">
              {{ __('How many people?') }}
              <span class="badge text-bg-light">{{ __('Required') }}</span>
            </label>
            <input
              type="number"
              min="1"
              step="1"
              inputmode="numeric"
              class="form-control @error('people') is-invalid @enderror"
              id="people"
              wire:model="people"
              aria-describedby="peopleHelp"
              placeholder="1"
            >
            @error('people')
              <div class="invalid-feedback">{{ $message }}</div>
            @else
              <div id="peopleHelp" class="form-text">{{ __('Minimum 1 person.') }}</div>
            @enderror
          </div>

          {{-- Start date --}}
          <div class="col-12 col-md-4">
            <label for="startDate" class="form-label">{{ __('From') }}</label>
            <div class="position-relative">
              <i class="bi bi-calendar-event position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input
                type="date"
                class="form-control ps-5 @error('startDate') is-invalid @enderror"
                id="startDate"
                min="{{ now()->toDateString() }}"
                wire:model="startDate"
                wire:change="calculatePrice"
              >
              @error('startDate')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- End date --}}
          <div class="col-12 col-md-4">
            <label for="endDate" class="form-label">{{ __('Until') }}</label>
            <div class="position-relative">
              <i class="bi bi-calendar-check position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input
                type="date"
                class="form-control ps-5 @error('endDate') is-invalid @enderror"
                id="endDate"
                min="{{ $this->startDate ?? now()->toDateString() }}"
                wire:model="endDate"
                wire:change="calculatePrice"
              >
              @error('endDate')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          {{-- Nights + status --}}
          <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
              <div class="d-flex align-items-center gap-2">
                @php
                    $nights = 0;
                    if ($this->startDate && $this->endDate && $this->startDate <= $this->endDate) {
                        $start = \Carbon\Carbon::parse($this->startDate);
                        $end = \Carbon\Carbon::parse($this->endDate);
                        $nights = $start->diffInDays($end);
                    }
                @endphp
                <span class="badge text-bg-light border">
                  <i class="bi bi-moon-stars me-1"></i>
                  {{ $nights }} {{ Str::plural('night', (int)($nights ?? 0)) }}
                </span>

                @if(($this->startDate && $this->endDate) && ($this->startDate > $this->endDate))
                  <span class="text-danger small">{{ __('End date must be after start date.') }}</span>
                @endif
              </div>

              {{-- Price calc indicator (shown when Livewire recalculates) --}}
              <div class="text-muted small" wire:loading.delay.shortest wire:target="startDate,endDate,people,selectedRoom,calculatePrice">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                {{ __('Updating…') }}
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  {{-- Selected guest card --}}
  @if($this->guest)
    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100 position-relative">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
          <h6 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-person-check text-primary"></i> {{ __('Selected guest') }}
          </h6>
          <span class="badge text-bg-primary-subtle border border-primary-subtle text-primary-emphasis">
            <i class="bi bi-check2-circle me-1"></i>{{ __('Selected') }}
          </span>
        </div>

        <div class="card-body">
          <div class="d-flex align-items-start gap-3">
            <img
              src="{{ $this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}"
              alt="{{ $this->guest->name }}"
              class="rounded-2 object-fit-cover"
              style="width:88px;height:88px;"
              loading="lazy"
            >
            <div class="flex-grow-1">
              <div class="fw-semibold text-truncate">{{ $this->guest->name }}</div>
              <div class="small text-muted text-truncate"><i class="bi bi-envelope me-1"></i>{{ $this->guest->email }}</div>
              <div class="small text-muted text-truncate"><i class="bi bi-phone me-1"></i>{{ $this->guest->phone }}</div>
              <div class="small text-muted text-truncate"><i class="bi bi-geo me-1"></i>{{ $this->guest->street }}</div>
            </div>
          </div>
        </div>

        @php $activeCount = $this->guest->bookings()->isActive()->count(); @endphp
        <div class="card-footer bg-white border-0 d-flex align-items-center justify-content-between">
          @if($activeCount >= 1)
            <span class="badge text-bg-success-subtle border border-success-subtle text-success-emphasis">
              <i class="bi bi-activity me-1"></i>{{ __('Active') }}
            </span>
          @endif
          <a
            href="#"
            class="btn btn-outline-secondary btn-sm"
            onclick="event.preventDefault(); Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: {{ $this->guest->id }} })"
          >
            <i class="bi bi-pencil-square me-1"></i>{{ __('Edit') }}
          </a>
        </div>
      </div>
    </div>
  @endif
</div>

{{-- Tiny helpers --}}
<style>
  .object-fit-cover { object-fit: cover; }
</style>
