<div>
    <style>
        .k-file-upload {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            transition: border-color 0.2s ease;
        }
        .k-file-upload:hover {
            border-color: #097274;
        }
        .file-display {
            min-height: 40px;
            border-radius: 4px;
            background: #f8f9fa;
        }
        .file-name {
            font-size: 0.9rem;
            color: #1f2937;
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-primary {
            background: #097274;
            border: none;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: #07595a;
        }
        .btn-outline-danger {
            border-color: #dc2626;
            color: #dc2626;
            padding: 0.2rem 0.5rem;
        }
        .btn-outline-danger:hover {
            background: #dc2626;
            color: #fff;
        }
        .error {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }
        @media (max-width: 576px) {
            .k-file-upload {
                width: 100%;
            }
            .file-name {
                max-width: 150px;
            }
            .file-display {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-break">Ndako</h4>
                <span class="btn-close" wire:click="$dispatch('closeModal')"></span>
            </div>
            <form>
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
                            <div class="gap-3 k_cell k_wrap_input flex-grow-1">
                                <input type="text" wire:model="email" class="k-input" style="padding: 1px 0 0; width: 75%;" id="date_0">

                                <span class="gap-2 btn btn-primary" wire:click="addEmail">
                                    <i class="fas fa-user-plus"></i> {{__('Add')}}
                                </span>
                                @error('email') <span class="error">{{ $message }}</span> @enderror
                            </div>

                            <span class="loader-spin-1" wire:loading wire:target="addEmail"></span>

                            <div class="mt-2 w-75 d-flex">
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
                                    {{ __('Subject') }}
                                </label>
                            </div>
                            <!-- Input Form -->
                            <div class="k_cell k_wrap_input flex-grow-1">
                                <input type="text" wire:model.live="subject" class="k-input" style="padding: 1px 0 0" id="date_0">
                                @error('subject') <span class="error">{{ $message }}</span> @enderror
                                </div>
                        </div>
                        {{-- <div
                            id="body_0"
                            class="koverae-editable-editor koverae-editor k-input"
                            contenteditable="true"
                            wire:ignore
                        >{!! $content !!}</div> --}}
                        <div x-data="{ content: @entangle('content') }">
                            <div class="koverae-editable-editor koverae-editor k-input" x-on:blur="content = $event.target.innerHTML" contenteditable="true">{!! $content !!}</div>
                        </div>

                    </div>

                    <!-- Attachment -->
                    <div class="mb-3 k_inner_group" style="margin-bottom: 25px;">
                        <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                            <label for="file" class="k_form_label">{{ __('Attachment (Optional PDF)') }}:</label>
                        </div>
                        <div class="k_cell k_wrap_input flex-grow-1">
                            <div class="k-file-upload" style="width: 75%;">
                                <input type="file" wire:model="file" class="d-none" id="file" accept=".pdf">
                                <div class="p-2 file-display d-flex align-items-center justify-content-between">
                                    @if ($file || $attachment)
                                        <div class="gap-2 d-flex align-items-center">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 1.6rem;"></i>
                                            <span class="mb-3 file-name text-900">
                                                {{ $file ? $file->getClientOriginalName() : basename($attachment) }}
                                            </span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearFile" title="{{ __('Remove') }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @else
                                        <span class="text-muted">{{ __('No file selected') }}</span>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('file').click()">
                                            {{ __('Choose File') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                            @error('file')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 k_inner_group">
                        <div class="d-flex" style="margin-bottom: 8px;">
                            <!-- Input Label -->
                            <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                                <label class="k_form_label">
                                    {{ __('Email Template') }}
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
