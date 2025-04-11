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

        <div class="row align-items-start">
            @if($this->tenant)
            <div class="p-2 k_kanban_view">

                <div class="flex-wrap k_kanban_renderer align-items-start d-flex justify-content-start">

                    <!-- Property Overview -->
                    <div class="mb-1 k_kanban_card">
                        <div class="gap-3 k_kanban_card_content d-flex">
                            <img class="rounded cursor-pointer k_kanban_image k_image_62_cover"
                                style="height: 100px; width: 100px;"
                                src="{{ asset('assets/images/default/property.jpeg') }}">
                            <div class="k_kanban_details">
                                <div class="cursor-pointer k_kanban_record_title">
                                    <div class="gap-3 d-flex">
                                        <h2 class="h2">{{ $this->tenant->lease->unit->property->name }} <i class="bi bi-pencil-square"></i></h2><span class="p-1">{{ $this->tenant->lease->code }}</span>
                                    </div>
                                    <span>{{ $this->tenant->lease->unit->name }} ~ {{ $this->tenant->lease->unit->unitType->name }}</span>
                                    <span class="mb-1 text-muted d-block">Monthly Rent:  <strong>{{ format_currency($this->tenant->lease->rent_amount) }}</strong> </span>
                                </div>
                                @php
                                    $start = \Carbon\Carbon::parse($this->tenant->lease->start_date);
                                    $end = \Carbon\Carbon::parse($this->tenant->lease->end_date);
                                    $duration = $start->diffInMonths($end);
                                @endphp
                                <div class="text-muted">
                                    <span class="mb-1 text-muted">Move-in Date: <strong>{{ \Carbon\Carbon::parse($this->tenant->lease->start_date)->format('M j, Y') }}</strong></span><br>
                                    <span class="mb-1 text-muted">Lease Duration: <strong>{{ $duration }} {{ Str::plural('Month', $this->duration) }}</strong></span><br>
                                    <span class="mb-1 text-muted">Amount Due: <strong>{{ format_currency($this->tenant->lease->invoices()->isCurrentInvoice()->first()->due_amount) }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Lease Details -->
                    <div class="mb-1 k_kanban_card">
                        <div class="p-1 k_kanban_card_content">
                            <h6 class="mb-2 h3">Current Lease Information</h6>
                            @php
                                $overdueRent = $this->tenant->lease->invoices()->isOverdue()->isPosted()->get();
                            @endphp
                            <ul class="mb-0 list-unstyled">
                                <li><strong>Next Rent Due:</strong> {{ \Carbon\Carbon::parse($this->tenant->lease->invoices()->where('code', formatDateToCode(now()))->first()->due_date)->format('M j, Y') }}</li>
                                <li><strong>Rent Amount:</strong> {{ format_currency($this->tenant->lease->rent_amount) }} / Month</li>
                                <li><strong>Unpaid Rent:</strong> {{ format_currency($this->tenant->lease->invoices()->isOverdue()->isPosted()->sum('due_amount')) }}</li>
                                <li><strong>Security Deposit:</strong> {{ format_currency($this->tenant->lease->deposit_amount) }} (Refundable)</li>
                                <li><strong>Lease Status:</strong> {{ ucfirst($this->tenant->lease->status) }}</li>
                                {{-- <li><strong>Preferred Payment:</strong> M-Pesa (Paybill: 123456, Acc: 103)</li> --}}
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            @else
            <div class="p-2 k_kanban_view">

                <!-- Property -->
                <div class="d-flex" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                            {{ __('Property') }}
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1 ">

                        <select wire:model.live="property" id="property" class="k-input">
                            <option value="">{{__('---- Select -----')}}</option>
                            @foreach($this->propertiesOptions as $key => $text)
                            <option value="{{$key}}">{{ $text }}</option>
                            @endforeach
                        </select>
                        @error('property') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Unit -->
                <div class="d-flex" style="margin-bottom: 8px;">
                    <!-- Input Label -->
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">
                            {{ __('Unit') }}
                        </label>
                    </div>
                    <!-- Input Form -->
                    <div class="k_cell k_wrap_input flex-grow-1 ">

                        <select wire:model.live="unit" id="unit" class="k-input">
                            <option value="">{{__('---- Select -----')}}</option>
                            @foreach ($this->unitsOptions as $key => $text)
                            <option value="{{ $key }}">{{ $text }}</option>
                            @endforeach

                        </select>
                        @error('unit') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Monthly Rent -->
                <div class="mb-2 d-flex gap-2">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">{{ __('Monthly Rent') }}</label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="number" class="k-input" wire:model="monthlyRent">
                        @error('monthlyRent') <span class="text-danger">{{ $message }}</span> @enderror
                        <br>
                    </div>
                    <label for="is-linked" class="d-block align-items-center cursor-pointer">
                        <input type="checkbox" wire:model="isLinked" id="is-linked" class="form-check-input koverae-checkbox">
                        Link rent to unit price
                    </label>
                </div>

                <!-- Deposit Amount -->
                <div class="mb-2 d-flex gap-2">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">{{ __('Deposit Amount') }}</label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="number" class="k-input" wire:model.live="depositAmount">
                        @error('depositAmount') <span class="text-danger">{{ $message }}</span> @enderror
                        <br>
                    </div>
                </div>

                <!-- Lease Term -->
                {{-- <div class="mb-2 d-flex">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">{{ __('Lease Term') }}</label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <select wire:model.live="leaseTerm" id="" class="k-input">
                            <option value="">---------Select----------</option>
                            @foreach ($this->leaseTermsOptions as $key => $text)
                            <option value="{{ $key }}">{{ $text }}</option>
                            @endforeach
                        </select>
                        @error('leaseTerm') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div> --}}

                <!-- Start Date -->
                <div class="mb-2 d-flex">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">{{ __('Start Date') }}</label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="date" class="k-input" wire:model.live="startDate">
                        @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- End Date -->
                <div class="mb-2 d-flex">
                    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
                        <label class="k_form_label">{{ __('End Date') }}</label>
                    </div>
                    <div class="k_cell k_wrap_input flex-grow-1">
                        <input type="date" class="k-input" wire:model.live="endDate">
                        @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <span class="{{ $this->unit ? '' : 'd-none' }}">
                    {{ __("Your lease will last for {$this->duration} " . Str::plural('month', $this->duration)) }}
                    ({{ \Carbon\Carbon::parse($this->startDate)->format("M j, Y") ."~". \Carbon\Carbon::parse($this->endDate)->format("M j, Y") }})
                </span>
            </div>
            @endif



            {{-- <div class="mb-2 k_x2m_control_panel d-empty-none">
                <button class="btn btn-secondary" style="background-color: #0E6163;" onclick="Livewire.dispatch('openModal', {component: 'contact::modal.add-guest-modal'} )">
                    {{ __('Add Address / Contact') }}
                </button>
            </div> --}}
        </div>
    </div>
