@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Who’s on Your Team? 👥</h2>
            <p>Let’s make work easier, invite your staff or colleagues and start managing together.</p>

        </div>
        <div class="card-body">
            <form wire:submit.prevent="inviteMembers">
                @csrf
                <div class="row">
                
                    <div class="mb-4 col-lg-6 col-md-12">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" wire:model="memberEmail" class="form-control rounded p-2 w-full">
                        @error('memberEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                
                    <div class="mb-4 col-lg-6 col-md-6">
                        <label class="block text-sm font-medium">Role</label>
                        <select wire:model="memberRole" id="memberRole" class="form-control">
                            <option value="">--- Choose ---</option>
                            @foreach ($this->roles as $value => $text)
                            <option value="{{ $value }}">{{ $text }}</option>
                            @endforeach
                        </select>
                        @error('memberRole') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            
                <span wire:click="addMember" class="btn btn-primary">Add Member</span>
                
            
                <div class="row {{ $this->teamMembers ? '' : 'd-none' }}">
                    <h3 class="form-label h3">
                        {{ __('Team Members') }}
                    </h3>
                    @foreach($this->teamMembers as $index => $member)
                        <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" style="min-height: 100px;">
                            <div class="p-2 border rounded" style="min-height: 100px;">
                                <div class="d-flex justify-content-between">
                                    <h3 class="h3">{{ $member['email'] }}</h3>
                                    <span class="text-end" wire:click="removeMember({{ $index }})"><i class="fas fa-trash"></i></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span> {{ $member['role'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
    

                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <span class="btn cancel" wire:click="goToNextStep">{{ __('Skip') }}</span>
                        <button type="submit" class="btn btn-primary go-next" {{ $this->currentStep == count($this->steps()) - 1 ? 'disabled' : '' }}>
                            <span wire:loading.remove>Continue</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>
                
            </form>

        </div>
    </div>
</div>