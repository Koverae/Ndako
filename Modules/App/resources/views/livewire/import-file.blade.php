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
        
        <form wire:submit.prevent="import" class="p-2 mb-2 mt-3">

            <div class="file-upload-container" id="file-upload-container">
                <label for="file-upload" class="file-upload-label">
                    <i class="fas fa-cloud-upload-alt"></i> Click or drag to upload
                </label>
                <input type="file" wire:model="file" id="file-upload" class="file-upload-input">
                <div id="file-name" class="file-name"></div>
            </div>
            <br />
            @error('file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            
            <div wire:loading wire:target="file" class="text-sm text-gray-500">
                Previewing data...
            </div>

            @if (!empty($previewData))
                <div class="overflow-auto mt-4">
                    <h3 class="text-lg font-semibold mb-2">{{ __('Data Preview') }}</h3>
                    <table class="min-w-full border border-gray-300 text-sm">
                        <thead>
                            <tr>
                                @foreach(array_keys($previewData[0]) as $header)
                                    <th class="px-2 py-1 border">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($previewData as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td class="px-2 py-1 border">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
    
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
    <script>
        // Handle file selection
        document.getElementById('file-upload').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });

        // Handle drag events
        const fileUploadContainer = document.getElementById('file-upload-container');

        fileUploadContainer.addEventListener('dragover', function(event) {
            event.preventDefault();  // Necessary to allow dropping
            fileUploadContainer.style.backgroundColor = '#e9f1ff';  // Change color while dragging
            fileUploadContainer.style.borderColor = '#E6F2F3';
        });

        fileUploadContainer.addEventListener('dragleave', function(event) {
            event.preventDefault();
            fileUploadContainer.style.backgroundColor = '#f7f7f7';  // Reset color when dragging leaves
            fileUploadContainer.style.borderColor = '#0E6163';
        });

        fileUploadContainer.addEventListener('drop', function(event) {
            event.preventDefault();  // Prevent default behavior (e.g., opening the file)
            fileUploadContainer.style.backgroundColor = '#d1e7ff';  // Color change on drop
            fileUploadContainer.style.borderColor = '#E6F2F3';

            const files = event.dataTransfer.files;
            if (files.length > 0) {
                // Set the file input's files to the dropped files
                document.getElementById('file-upload').files = files;
                document.getElementById('file-name').textContent = files[0].name;  // Show the file name
            }
        });

        // Handle click (label click still works as before)
        document.getElementById('file-upload').addEventListener('click', function() {
            fileUploadContainer.style.backgroundColor = '#f7f7f7';  // Reset color when clicking
            fileUploadContainer.style.borderColor = '#0E6163';
        });

    </script>