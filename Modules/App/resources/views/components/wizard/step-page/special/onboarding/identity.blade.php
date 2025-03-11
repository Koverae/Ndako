@props([
    'value',
])

<div class="row gap-1 justify-content-md-center {{ $this->currentStep == $value->step ? '' : 'd-none' }}">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Identity Verification 🔒</h2>
            <p>Please upload a valid government-issued ID and an optional selfie for verification.</p>

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="card-body">

            <form wire:submit.prevent="submitIdentity">
                <div class="mb-3">
                    <label for="document_type" class="form-label">Select Document Type</label>
                    <select class="form-select" wire:model="document_type" required>
                        <option value="">-- Choose --</option>
                        <option value="id-card">National ID Card</option>
                        <option value="passport">Passport</option>
                        <option value="driver-license">Driver's License</option>
                    </select>
                    @error('document_type') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Document</label>
                    <input type="file" class="form-control" wire:model="document" required>
                    @error('document') <span class="text-danger">{{ $message }}</span> @enderror
                    <!-- Document Preview -->
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Selfie (Optional)</label>
                    <input type="file" class="form-control" wire:model="selfie">
                    @error('selfie') <span class="text-danger">{{ $message }}</span> @enderror

                    <!-- Selfie Preview -->
                    @if ($this->selfiePreview)
                        <div class="mt-3">
                            <p class="fw-bold">Selfie Preview:</p>
                            <img src="{{ $this->selfiePreview }}" alt="Selfie Preview" class="img-fluid shadow-sm rounded" style="max-height: 150px; width: 150px;">
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" {{ $this->currentStep == 0 ? 'disabled' : '' }}><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <span class="btn cancel" wire:click="goToNextStep">{{ __('Skip') }}</span>

                        <button type="submit" class="btn btn-primary go-next" {{ $this->currentStep == count($this->steps()) - 1 ? 'disabled' : '' }}>
                            <span wire:loading.remove class="uppercase">Go Next</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
