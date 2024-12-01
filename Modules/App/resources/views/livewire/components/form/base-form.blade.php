<div>
    <div class="h-auto k_form_sheet_bg">
        <!-- Status bar -->
        <div class="pb-2 mb-0 k_form_statusbar position-relative d-flex justify-content-between mb-md-2 mt-md-2 pb-md-0">

            <!-- Action Bar -->
            @if($this->actionBarButtons())
            <div id="action-bar" class="flex-wrap gap-1 k_statusbar_buttons d-none d-lg-flex align-items-center align-content-around">

                @foreach($this->actionBarButtons() as $action)
                <x-dynamic-component
                    :component="$action->component"
                    :value="$action"
                    :status="$status"
                >
                </x-dynamic-component>
                @endforeach

            </div>
            <!-- Dropdown button -->
            <div class="btn-group d-lg-none">
                <button type="button" class="btn buttons dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Action
                </button>
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
                <div id="status-bar" class="k_statusbar_buttons_arrow d-none d-md-flex align-items-center align-content-around ">

                    @foreach($this->statusBarButtons() as $status_button)
                    <x-dynamic-component
                        :component="$status_button->component"
                        :value="$status_button"
                        :status="$status"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
                <div id="status-bar" class="k_statusbar_buttons_arrow d-flex d-md-none align-items-center align-content-around ">

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
        <form wire:submit.prevent="{{ $this->form() }}">
            @csrf
            <!-- Sheet Card -->
            <div class="k_form_sheet position-relative">

                @include('sales::livewire.sale.invoice.partials.payment-status-ribbon')

                <div class="m-0 mb-2 row justify-content-between position-relative w-100">
                    {{-- <span class="k_form_label">
                        Demande de prix
                    </span> --}}
                    @if($this->capsules())
                    <div class="k_horizontal_asset" id="k_horizontal_capsule">
                        @foreach($this->capsules() as $capsule)
                        <x-dynamic-component
                            :component="$capsule->component"
                            :value="$capsule"
                            :status="$status"
                        >
                        </x-dynamic-component>
                        @endforeach
                    </div>
                    @endif
                    <!-- title-->
                    @if(isset($this->reference) && $this->reference)
                    <div class="ke_title mw-75 pe-2 ps-0" id="new-title">
                        <!-- Name -->
                        <h1 class="flex-row d-flex align-items-center">
                            {{ $this->reference }}
                        </h1>
                    </div>
                    @endif

                    @if (session()->has('message'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <span>{{ session('message') }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Top Form -->
                <div class="row">
                    <!-- Left Side -->
                    <div class="k_inner_group col-md-6 col-lg-6">

                        @foreach($this->inputs() as $input)
                            @if($input->position == 'left' && $input->tab == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach

                    </div>
                    <!-- Right Side -->
                    <div class="k_inner_group col-md-6 col-lg-6">

                        @foreach($this->inputs() as $input)
                            @if($input->position == 'right' && $input->tab == 'none')
                                <x-dynamic-component
                                    :component="$input->component"
                                    :value="$input"
                                    {{-- :date="$this->date" --}}
                                >
                                </x-dynamic-component>
                            @endif
                        @endforeach
                    </div>
                </div>

                @if($this->tabs())
                <div class="k_notebokk_headers">
                    <!-- Tab Link -->
                    <ul class="flex-row nav nav-tabs flex-nowrap" data-bs-toggle="tabs">
                        @foreach ($this->tabs() as $tab)
                        <li class="nav-item">
                            <a class="nav-link {{ $tab->key == 'order' || $tab->key == 'purchase' || $tab->key == 'invoice' ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $tab->key }}">{{ $tab->label }}</a>
                        </li>
                        @endforeach
                    </ul>

                </div>
                @endif
                <!-- Tabs content -->

                @foreach ($this->tabs() as $tab)
                <x-dynamic-component
                    :component="$tab->component"
                    :value="$tab"
                    :cartInstance="'{{ $cartInstance }}'"
                >
                </x-dynamic-component>
                @endforeach

            </div>

        </form>

    </div>
    <!-- Loading -->
    <div class="pb-1 cursor-pointer k-loading" wire:loading>
        <p>En cours de chargement ...</p>
    </div>
</div>
