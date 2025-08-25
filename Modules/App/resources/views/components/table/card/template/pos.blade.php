@props([
    'value',
    'model',
    'id'
])

@php
    use Illuminate\Support\Facades\Auth;

    $pos = \Modules\Pos\Models\Pos\Pos::find($id);
    $user = Auth::user();

    // Permission flags (Spatie)
    $canKitchenKDS = $user?->can('view_kds');
    $canBarKDS     = $user?->can('view_bar_kds');
    $canAccessPOS     = $user?->can('access_pos');         // open POS/register
    $canViewFloor     = $user?->can('view_floor');         // tables/floor plan
    $canViewSessions  = $user?->can('view_pos_sessions');  // sessions listing
    $canOpenSession   = $user?->can('open_pos_session');   // open drawer/session
@endphp

<div class="mb-1 col-md-6" style="border-left: 4px solid #0E6163">
  <div class="card">
    <div class="p-2 card-body">
      <div class="d-flex justify-content-between align-items-center">
        <a class="text-decoration-none flex-grow-1" wire:navigate href="{{ $this->showRoute($id) }}">
          <h5 class="m-0 mb-2 card-title">{{ $model[$value->title] }}</h5>
        </a>

        <span class="badge bg-info text-white">{{ __('Opening Control') }}</span>

        <div class="dropdown ms-2">
          <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{{ __('More actions') }}">
            <i class="bi bi-gear fs-3"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end">
            <a wire:navigate href="{{ route('orders.lists') }}" class="dropdown-item">
              {{ __('Orders') }}
            </a>
            <a wire:navigate href="{{ route('pos-sessions.lists') }}" class="dropdown-item">
              {{ __('Sessions') }}
            </a>

            @if($canKitchenKDS || $canBarKDS)
              <div class="dropdown-divider"></div>
              @can('view_kds')
                <a
                  wire:navigate
                  href="{{ route('pos.kds', ['station' => 'kitchen', 'posId' => $id]) }}"
                  class="dropdown-item d-flex align-items-center gap-2"
                >
                  <i class="bi bi-layout-three-columns"></i> {{ __('Kitchen Display') }}
                </a>
                {{-- Optional pass/expo view if you use it under same permission --}}
                <a
                  wire:navigate
                  href="{{ route('pos.kds', ['station' => 'pass', 'posId' => $id]) }}"
                  class="dropdown-item d-flex align-items-center gap-2"
                >
                  <i class="bi bi-arrow-left-right"></i> {{ __('Pass / Expo Display') }}
                </a>
              @endcan

              @can('view_bar_kds')
                <a
                  wire:navigate
                  href="{{ route('pos.kds', ['station' => 'bar', 'posId' => $id]) }}"
                  class="dropdown-item d-flex align-items-center gap-2"
                >
                  <i class="bi bi-cup-straw"></i> {{ __('Bar Display') }}
                </a>
              @endcan
            @endif
          </div>
        </div>
      </div>

      <!-- POS details -->
      <div class="d-flex flex-row justify-content-between text-truncate mb-1">
        <span>{{ __('Close') }}</span>
        @php
          $lastSession = $pos->sessions()
              ->where('status', '<>', 'cancelled')
              ->latest()
              ->first();
        @endphp
        <span>{{ $lastSession ? \Carbon\Carbon::parse($lastSession->closing_date)->format('m/d/Y') : 'N/A' }}</span>
      </div>

      <div class="d-flex flex-row justify-content-between text-truncate mb-2">
        <span>{{ __('Closing Balance') }}</span>
        <span>{{ format_currency($lastSession->closing_balance ?? 0) }}</span>
      </div>

      {{-- Quick actions (KDS chips shown only if authorized) --}}
      @if($canKitchenKDS || $canBarKDS)
        <div class="d-flex flex-wrap gap-2 mb-2">
          @can('view_kds')
            <a
              wire:navigate
              href="{{ route('pos.kds', ['station' => 'kitchen', 'posId' => $id]) }}"
              class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2"
            >
              <i class="bi bi-layout-three-columns"></i> {{ __('Kitchen KDS') }}
            </a>
            {{-- Optional pass/expo --}}
            <a
              wire:navigate
              href="{{ route('pos.kds', ['station' => 'pass', 'posId' => $id]) }}"
              class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2"
            >
              <i class="bi bi-arrow-left-right"></i> {{ __('Pass / Expo') }}
            </a>
          @endcan

          @can('view_bar_kds')
            <a
              wire:navigate
              href="{{ route('pos.kds', ['station' => 'bar', 'posId' => $id]) }}"
              class="btn btn-outline-dark btn-sm d-inline-flex align-items-center gap-2"
            >
              <i class="bi bi-cup-straw"></i> {{ __('Bar KDS') }}
            </a>
          @endcan
        </div>
      @endif

      {{-- Open Register / Continue Selling (show only to POS-capable users) --}}
      @if($canAccessPOS || $canOpenSession)
        <div class="gap-2 d-flex">
          @php
            $label = (session()->has("pos_session_id_{$pos->id}") || $pos->active_session_id) ? 'Continue Selling' : 'Open Register';
          @endphp
          <a wire:click="openSession('{{ $id }}')" class="mt-2 btn btn-primary cursor-pointer">
            {{ $label }}
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
