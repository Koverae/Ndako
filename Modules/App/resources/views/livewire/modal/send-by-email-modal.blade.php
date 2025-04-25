<div>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-break">Koverae</h4>
                <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
            </div>
            <form wire:submit.prevent="sendEmail">
                <div class="modal-body position-relative">

                <div class="k_form_renderer k_form_nosheet k_form_editable d-block">
                    
                    <div class="k_inner_group">

                        <!-- Emails -->
                        <div class="mb-3">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    {{ __('Recipients') }} :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1 gap-3">
                                <input type="text" wire:model="email" class="k-input" style="padding: 1px 0 0; width: 75%;" id="date_0">
                                
                                <span class="btn btn-primary gap-2" wire:click="addEmail">
                                    <i class="fas fa-user-plus"></i> {{__('Add')}}
                                </span>
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            <span class="loader-spin-1" wire:loading wire:target="addEmail"></span>
                            
                            <div class="w-75 d-flex mt-2">
                                @foreach ($recipient_emails as $email)
                                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="background-color: #097274;">
                                    < {{ $email }} >
                                    <i class="bi bi-x cancelled_icon" wire:click="removeEmail('{{ $email }}')" wire:target="removeEmail('{{ $email }}')" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ __('Remove') }}"></i>
                                </a>
                                @endforeach
                                
                            </div>
                        </div>
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    Sujet :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1">
                                <input type="text" wire:model.blur="subject" class="k-input" style="padding: 1px 0 0" id="date_0">
                                @error('subject') <span class="error">{{ $message }}</span> @enderror
                                </div>
                        </div>

                        <div class="note-editable koverae-editable-editor koverae-editor" contenteditable="true" id="body_0" wire:model="content" >
                            {!! $content !!}
                        </div>

                    </div>

                    {{-- <div class="k_inner_group">
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <div x-data="{ fileInput: null }">
                            <label for="fileInput" class="p-2 m-1 border-0 rounded k_select_file_button btn btn-light">
                                <span x-text="fileInput ? fileInput.name + ' (' + fileInput.type + ')' : 'Choose File'"></span>
                                <input type="file" id="fileInput" wire:model="file" x-ref="fileInput" class="d-none" />
                                </label>
                            </div>
                            <div
                                x-data="{ uploading: false, progress: 0 }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                @if ($file)
                                    <img src="{{ $file->temporaryUrl() }}">
                                @endif
                                <!-- File Input -->
                                <input type="file" wire:model="file">

                                <!-- Progress Bar -->
                                <div x-show="uploading">
                                    <progress max="100" x-bind:value="progress"></progress>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="k_inner_group">
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    Modèle d'email :
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1">
                                <select wire:model.blur="template_id" class="k-input" style="padding: 1px 10px 1px 0; width: 372px;" id="model_0">
                                    <option value=""></option>
                                    @foreach($templates as $t)
                                        <option value="{{ $t['id'] }}">{{ $t['name'] }}</option>
                                    @endforeach
                                </select>@error('template_id') <span class="error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                </div>
                </div>
                <div class="p-0 modal-footer">
                    <button wire:click="sendEmail" class="btn btn-primary">Send <i class="bi bi-send-fill"></i></button>
                    <button class="btn btn-secondary" wire:click="$dispatch('closeModal')">{{ __('Discard') }}</button>
                </div>
            </form>
        </div>
</div>
