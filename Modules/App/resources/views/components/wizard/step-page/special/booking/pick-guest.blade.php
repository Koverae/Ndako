@props([
    'value',
])
@php
    $users = \App\Models\User::isCompany(current_company()->id)->get();
@endphp

<div class="mt-3 container-fluid {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <input type="search" class="w-50 form-control" wire:model.live="search" id="" placeholder="Search guests by name or email...">
        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.add-guest-modal'})" class="gap-2 text-end btn btn-primary">{{ __('Add Guest') }} <i class="fas fa-user-plus"></i></span>
    </div>
    <div class="row">
        @forelse($this->guests as $guest)
        <div class="mb-1 cursor-pointer col-sm-3" wire:click="pickGuest('{{ $guest->id }}')">
            <a class="card @if($this->guest) {{ $this->guest->id == $guest->id ? 'active-pick' : '' }} @endif" wire:navigate>
                <div class="d-flex">
                    <img src="{{ $guest->avatar ? Storage::url('avatars/' . $guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="{{ $guest->name }}" class="img img-fluid" height="120px" width="120px">
                    <div class="p-2 card-body text-truncate">
                        <h5 class="mb-2 card-title">{{ $guest->name }}</h5>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-envelope"></i> {{ $guest->email }}</span> <br>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-phone"></i> {{ $guest->phone }}</span> <br>
                        <span class="mb-1 cursor-pointer text-truncate w-100"><i class="bi bi-geo"></i> {{ $guest->email }}</span> <br>
                        {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                    </div>
                </div>
            </a>
        </div>
        @empty
            <div class="mb-1 col-sm-12" style="height: 400px;">
                No data...
            </div>
        @endforelse
    </div>
</div>
