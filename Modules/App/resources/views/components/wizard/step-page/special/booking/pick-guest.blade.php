@props([
    'value',
])

<div class="mt-3 container-fluid {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="search-container w-100 w-md-50">
            <input type="search" class="form-control" wire:model.live="search" id="" placeholder="Search guests by name or email...">
            <i class="fas fa-search"></i>
            @if($this->search)
            <div wire:loading wire:target="search">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            @endif
        </div>
        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})" class="gap-2 text-end btn btn-primary">{{ __('Add Guest') }} <i class="fas fa-user-plus"></i></span>
    </div>
    <div class="row">
        @forelse($this->guests as $guest)
        <div class="mb-1 cursor-pointer col-12 col-sm-6 col-md-4" wire:key="guest-{{ $guest->id }}">
            <a class="card @if($this->guest) {{ $this->guest->id == $guest->id ? 'active-pick' : '' }} @endif" wire:click="pickGuest('{{ $guest->id }}')" wire:navigate>
                <div class="d-flex">
                    <img src="{{ $guest->avatar ? Storage::url('avatars/' . $guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="{{ $guest->name }}" class="img img-fluid" height="120px" width="120px">
                    <div class="p-2 card-body" style="flex-grow: 1; overflow: hidden;">
                        <h5 class="mb-2 card-title text-truncate">{{ $guest->name }} <i class="bi bi-pencil-square" onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal', arguments: {{ $guest->id }}})"></i></h5>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-envelope"></i> {{ $guest->email }}</span>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-phone"></i> {{ $guest->phone }}</span>
                        <span class="mb-1 cursor-pointer text-truncate d-block"><i class="bi bi-geo"></i> {{ $guest->email }}</span>
                    </div>
                </div>
                @if($guest->bookings()->isActive()->count() >= 1)
                <span class="badge-active">Active</span>
                @endif
            </a>
        </div>
        @empty
        <div class="mb-1 col-12 text-center py-5">
            <i class="fas fa-users text-gray-400" style="font-size: 2.5rem;"></i>
            <p class="text-gray-600 mt-2">No guests found. Try adjusting your search or add a new guest.</p>
        </div>
        @endforelse
    </div>
</div>
