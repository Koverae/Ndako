@props([
    'value',
    'model',
    'id'
])

@php
    $pos = \Modules\Pos\Models\Pos\Pos::find($id);
@endphp

<div class="mb-1 col-md-6" style="border-left: 4px solid #0E6163">
    <div class="card">
        <div class="p-2 card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a class="text-decoration-none flex-grow-1" wire:navigate href="{{ $this->showRoute($id) }}">
                    <h5 class="m-0 mb-2 card-title"> {{ $model[$value->title] }}</h5>
                </a>

                <span class="badge bg-info text-white">{{ __('Opening Control') }}</span>
                {{-- @if($model[$value->is_open])
                @else
                    <span class="badge bg-secondary">{{ __('Closed') }}</span>
                @endif --}}

                <div class="dropdown ms-2">
                    <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear fs-3"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a wire:navigate href="{{ route('orders.lists') }}" class="dropdown-item">
                            {{ __('Orders') }}
                        </a>
                        <a wire:navigate href="{{route('pos-sessions.lists')}}" class="dropdown-item">
                            {{ __('Sessions') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Displaying POS details -->
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

            <div class="d-flex flex-row justify-content-between text-truncate mb-1">
                <span>{{ __('Balance') }}</span>
                <span>{{ format_currency($lastSession->closing_balance ?? 0) }}</span>
            </div>

            <div class="gap-2 d-flex">
                <a wire:click="openSession('{{ $id }}')" class="mt-2 btn btn-primary cursor-pointer">
                    {{ session()->get("pos_session_id_{$id}") ? __('Continue Session') : __('Open Register') }}
                </a>
            </div>
        </div>
    </div>
</div>
