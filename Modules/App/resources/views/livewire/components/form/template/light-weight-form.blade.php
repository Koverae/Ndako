<div>
    <div class="k-form-sheet-bg">
        <!-- Notify -->
        {{-- <x-notify::notify /> --}}
        <form wire:submit.prevent="{{ $this->form() }}">
            @csrf
            <div class="pb-2 mb-0 k-form-statusbar position-relative d-flex justify-content-between mb-md-2 pb-md-0">
                <!-- Action Bar -->
                @if($this->actionBarButtons())
                    <div id="action-bar" class="flex-wrap gap-1 k-statusbar-buttons d-none d-lg-flex align-items-center align-content-around">

                        @foreach($this->actionBarButtons() as $action)
                        <x-dynamic-component
                            :component="$action->component"
                            :value="$action"
                            :status="'none'"
                        >
                        </x-dynamic-component>
                        @endforeach

                    </div>
                    <!-- Dropdown button -->
                    <div class="btn-group d-lg-none">
                        <span class="btn btn-dark buttons dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </span>
                        <ul class="dropdown-menu">
                            @foreach($this->actionBarButtons() as $action)
                            <x-dynamic-component
                                :component="$action->component"
                                :value="$action"
                                :status="$status"
                            >
                            </x-dynamic-component>
                            @endforeach
                            <!--<li><hr class="dropdown-divider"></li>-->
                        </ul>
                    </div>
                @endif

                <!-- Status Bar -->
                @if($this->statusBarButtons())
                    <div id="status-bar" class="gap-1 k-statusbar-buttons-arrow d-none d-md-flex align-items-center align-content-around ">

                        @foreach($this->statusBarButtons() as $status_button)
                        <x-dynamic-component
                            :component="$status_button->component"
                            :value="$status_button"
                            :status="$status"
                        >
                        </x-dynamic-component>
                        @endforeach
                    </div>
                    <div id="status-bar" class="gap-1 k-statusbar-buttons-arrow d-flex d-md-none align-items-center align-content-around ">

                        @foreach($this->statusBarButtons() as $status_button)
                            @if($this->status == $status_button->primary)
                            <x-dynamic-component
                                :component="$status_button->component"
                                :value="$status_button"
                                :status="$status"
                            >
                            </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sheet Card -->
            <div class="k-form-sheet position-relative">
                <!-- Capsule -->
                @if(count($this->capsules()) >= 1)
                <div class="gap-1 overflow-x-auto overflow-y-hidden k-horizontal-asset mb-md-3" id="k-horizontal-capsule">
                    @foreach($this->capsules() as $capsule)
                    <x-dynamic-component
                        :component="$capsule->component"
                        :value="$capsule"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
                @endif
                <!-- Capsule -->

                <!-- title-->
                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    <div class="ke-title mw-75 pe-2 ps-0">
                        <!-- title-->
                        @if(isset($this->reference) && $this->reference)
                        <!-- Name -->
                        <h1 class="flex-row mb-2 d-flex align-items-center" style="font-size: 35px; font-weight: 600;">
                            {{ $this->reference }}
                        </h1>
                        @endif
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'top-title' && $input->tab == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                    <!-- Avatar -->
                    @if($this->hasPhoto)
                    <div class="p-0 m-0 k_employee_avatar">
                        <!-- Image Uploader -->
                        @if($this->photo != null)
                        <img src="{{ $this->photo->temporaryUrl() }}" alt="image" class="img img-fluid">
                        @else
                        <img src="{{ $this->image_path ? Storage::url('avatars/' . $this->image_path) . '?v=' . time() : asset('assets/images/default/'.$default_img.'.png') }}" alt="image" class="img img-fluid">
                        @endif
                        <!-- <small class="k_button_icon">
                            <i class="align-middle bi bi-circle text-success"></i>
                        </small>-->
                        <!-- Image selector -->
                        <div class="bottom-0 select-file d-flex position-absolute justify-content-between w100">
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" onclick="document.getElementById('photo').click();">
                                <i class="bi bi-pencil"></i>
                                <input type="file" wire:model.blur="photo" id="photo" style="display: none;" />
                            </span>
                            @if($this->photo || $this->image_path)
                            <span class="p-1 m-1 border-0 k_select_file_button btn btn-light rounded-circle" wire:click="$cancelUpload('photo')" wire:target="$cancelUpload('photo')">
                                <i class="bi bi-trash"></i>
                            </span>
                            @endif
                        </div>
                        @error('photo') <span class="error">{{ $message }}</span> @enderror
                    </div>
                    @endif
                    <!-- Avatar -->
                </div>

                <div class="row align-items-start">

                    <!-- Left Side -->
                    <div class="k_inner_group col-lg-6">
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'left' && $input->tab == 'none' && $input->group == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>

                    <!-- Right Side -->
                    <div class="k_inner_group col-lg-6">
                        @foreach($this->inputs() as $input)
                            @if($input->position == 'right' && $input->tab == 'none' && $input->group == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="row align-items-start">
                    @foreach($this->groups() as $group)
                    <x-dynamic-component
                        :component="$group->component"
                        :value="$group"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>

                <!-- Note and total part -->
                <div class="k_group row align-items-start mt-md-0">

                    <div class="k_inner_group col-lg-9">
                        <div class="flex-grow-0 k_cell flex-sm-grow-0">
                            <div class="note-editable" id="note_1">
                                <textarea wire:model="term" id="term" style="width: 75%; padding-left: 5px; padding-top:10px;" id="" cols="30" rows="5" class="k-input textearea" placeholder="Termes & conditions">

                                </textarea>
                            </div>
                        </div>
                    </div>
                    @if($this->nights >= 1)
                    <div class="overflow-y-auto k_inner_group k_subtotal_footer col-lg-3 right h-100">
                        <!-- Taxes -->
                        <div>
                            <label for="" class="k_text_label k_tax_total_label fs-3">{{ __('Room Price(per night)') }}:</label>
                            <br>
                            <span class="fs-4">(+) {{ format_currency($roomPrice) }} * {{ $nights }} {{ __('Days') }}</span>
                        </div>

                        <!-- Total -->
                        <div class="mt-2">
                            <label for="" class="k_text_label k_tax_total_label fs-2"><b>{{ __('Total') }}:</b></label>
                            <span class="fs-2">(=) {{ format_currency($totalAmount) }}</span>
                        </div>
                    </div>
                    @endif
                    @if($isInvoice)
                    <div class="overflow-y-auto k_inner_group k_subtotal_footer col-lg-3 right h-100">
                        <!-- SubTotal -->
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <label for="" class="k_text_label k_tax_total_label fs-4">{{ __('Untaxed Amount') }}:</label>
                            <span class="fs-4 text-end">{{ format_currency($totalAmount) }}</span>
                        </div>
                        <!-- SubTotal -->

                        <!-- Taxes -->
                        <div class="mb-1 d-flex justify-content-between align-items-center">
                            <label for="" class="k_text_label k_tax_total_label fs-4">{{ __('VAT 16%') }}:</label>
                            <span class="fs-4 text-end">{{ format_currency(($totalAmount * 16)/100) }}</span>
                        </div>
                        <!-- Taxes -->

                        <!-- Total -->
                        <div class="mt-2 mb-4 d-flex justify-content-between align-items-center">
                            <label for="" class="k_text_label k_tax_total_label fs-2"><b>{{ __('Total') }}:</b></label>
                            <span class="fs-2 text-end">(=) {{ format_currency($totalAmount) }}</span>
                        </div>
                        <!-- Total -->

                        <!-- Due Amount -->
                        <div class="mb-1">

                            @if($this->invoice->payments()->count() >= 1)
                            @foreach($this->invoice->payments as $payment)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="bi bi-question-circle"></i> {{ __('Paid on ' . \Carbon\Carbon::parse($payment->date)->format('m/d/Y')) }}
                                </span>
                                <span class="text-end">{{ format_currency($payment->amount) }}</span>
                            </div>
                            @endforeach
                            @endif

                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                <label for="" class="k_text_label k_tax_total_label fs-4 text-muted">{{ __('Amount Due') }}:</label>
                                <span class="fs-2 text-end border-top">{{ format_currency($dueAmount) }}</span>
                            </div>
                        </div>
                        <!-- Due Amount -->
                    </div>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>
