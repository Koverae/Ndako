@props([
    'value',
    'data'
])
    <!-- Left Side -->
    <div class="k_inner_group col-md-6 col-lg-6">
        <!-- separator -->
        <div class="g-col-sm-2">

            <div class="mt-4 mb-3 k_horizontal_separator text-uppercase fw-bolder small">
                    {{ $value->label }}
            </div>
        </div>
        <!-- Photo Box -->
        <div class="mb-3 d-flex">
            <div class="gap-2 k-gallery-box">

                <span class="inline-flex bg-gray-200 border rounded k-image-box" onclick="document.getElementById('{{ $this->inputId }}').click();">
                    <img src="{{ asset('assets/images/default/placeholder.png') }}" class="inline-flex rounded image">
                    {{-- <input type="file" wire:model.blur="photo" id="photo" style="display: none;" /> --}}
                    <input type="file" wire:model="newImages" id="{{ $this->inputId }}" multiple style="display: none;">
                </span>

                @foreach($this->existingImages as $index => $image)
                <span class="bg-gray-200 border rounded k-image-box">
                    <img src="{{ $image }}" class="inline-flex rounded image" alt=""title="Tooltip on top">
                    {{-- <img src="{{ Storage::url('avatars/'.$user->avatar.'') }}" class="inline-flex rounded image" alt=""title="Tooltip on top"> --}}
                    <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle">
                            {{-- <i class="bi bi-pencil"></i> --}}
                        </span>
                        <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="removeImage({{ $index }})" wire:target="removeImage({{ $index }})">
                            <i class="bi bi-trash"></i>
                        </span>
                    </div>
                </span>
                @endforeach
            </div>

            <!-- Loading -->
            <div wire:loading wire:target="newImages" class="absolute inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 rounded-lg">
                <div class="text-xs text-white">Uploading...</div>
            </div>
            <div wire:loading wire:target="removeImage" class="absolute inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 rounded-lg">
                <div class="text-xs text-white">Uploading...</div>
            </div>
            <!-- Loading End -->

            @error('newImages.*')
                <div class="mt-1 text-danger">{{ $message }}</div>
            @enderror
        </div>
        <!-- Photo Box -->

        @foreach($this->inputs() as $input)
            @if($input->group == $value->key)
                <x-dynamic-component
                    :component="$input->component"
                    :data="$input->data"
                    :value="$input"
                >
                </x-dynamic-component>
            @endif
        @endforeach

    </div>


