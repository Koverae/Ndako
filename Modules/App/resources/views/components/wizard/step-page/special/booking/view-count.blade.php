@props([
    'value',
])
<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">
    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">

            <div class="col-md-12">
                <label for="people" class="form-label">
                    How many person?
                </label>
                <input type="text" class="form-control @error('people') is-invalid @enderror"
                    id="
                        people" wire:model="people">
                @error('people')
                    <div class="mt-1 text-danger">
                        {{ $message }}
                    </div>
                @enderror
                <label for="startDate" class="form-label">
                    From
                </label>
                <input type="date" class="form-control @error('startDate') is-invalid @enderror" id="
                        startDate" wire:model="startDate" wire:change="calculatePrice">
                @error('startDate')
                    <div class="mt-1 text-danger">
                        {{ $message }}
                    </div>
                @enderror
                <label for="endDate" class="form-label">
                    Until
                </label>
                <input type="date" class="form-control @error('endDate') is-invalid @enderror" id="
                        endDate" wire:model="endDate" wire:change="calculatePrice" value="{{ old('endDate') }}">
                @error('endDate')
                    <div class="mt-1 text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
    @if($this->guest)
    <div class="shadow-sm col-12 col-md-3 card" style="max-height: 450px;">
        <div class="card-body">
            <img src="{{ $this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}" alt="{{ $this->guest->name }}" class="rounded-1 img img-fluid" height="350px" width="350px">
            <div class="mt-2">
                <span><i class="fas fa-user-md"></i> {{ $this->guest->name }}</span> <br>
                <span><i class="bi bi-envelope"></i> {{ $this->guest->email }}</span> <br>
                <span><i class="bi bi-phone"></i> {{ $this->guest->phone }}</span> <br>
                <span><i class="bi bi-geo"></i> {{ $this->guest->street }}</span> <br>
            </div>
        </div>
    </div>
    @endif
</div>
