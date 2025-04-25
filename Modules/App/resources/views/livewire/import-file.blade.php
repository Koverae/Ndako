@section('title', "Import File")

<!-- Control Panel -->
@section('control-panel')
<livewire:app::navbar.control-panel.import-panel />
@endsection
<!-- Page Content -->
<section class="w-100">
    <div class="empty k_nocontent_help h-100">
        <img src="{{ asset('assets/images/illustrations/file-icon.svg') }}"style="height: 200px" alt="">
        <p class="empty-title">{{__('Drop or upload a file to import')}}</p>
        <p class="empty-subtitle">{{ __('Excel files are recommended as formatting is automatic. But, you can also use .csv files') }}</p>
        
        <a href="#" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2">
            <i class="fas fa-download"></i> {{ __('Import Template for Units') }}
        </a>
        
        
        <form wire:submit.prevent="import" class="p-2 mb-2">
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
                <input id="file" type="file" wire:model="file" class="mt-1 block w-full" />
                @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
    
            <div class="mb-4">
                <label for="model" class="block text-sm font-medium text-gray-700">Selected Model {{ Str::headline($modelSlug) }}</label>
                <select id="model" wire:model="model" class="mt-1 block w-full">
                    <option></option>
                    <option value="\Modules\Properties\Models\Property\PropertyUnit::class">{{__('Property Units')}}</option>
                    <!-- Add more models as needed -->
                </select>
                @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
    
            <button type="submit" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2 w-100">Import</button>
        </form>
    
        @if (session()->has('message'))
            <div class="mt-4 text-green-600">{{ session('message') }}</div>
        @endif
    
        @if (session()->has('error'))
            <div class="mt-4 text-red-600">{{ session('error') }}</div>
        @endif
        
        <p>Need Help? <a href="#" style="color: #0E6163;">Import FAQ</a></p>
        
    </div>
</section>
<!-- Page Content -->