<div>
    <div class="k_form_sheet_bg">
        <!-- Notify -->
        <x-notify::notify />
        <!-- Status bar -->
        @if(count($this->actionBarButtons()) >= 1 || count($this->statusBarButtons()) >= 1)
        <div class="k_form_statusbar position-relative d-flex justify-content-between mb-0 mb-md-2 pb-2 pb-md-0">


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

            <!-- Status Bar -->
            @if($this->statusBarButtons())
            <div id="status-bar" class="k_statusbar_buttons_arrow d-flex align-items-center align-content-around ">

                @foreach($this->statusBarButtons() as $status_button)
                <x-dynamic-component
                    :component="$status_button->component"
                    :value="$status_button"
                    :status="$status"
                >
                </x-dynamic-component>
                @endforeach
            </div>
            @endif

        </div>
        @endif
        <form wire:submit.prevent="{{ $this->form() }}">
            @csrf
            <!-- Sheet Card -->
            <div class="k_form_sheet position-relative">
                <!-- title-->
                <div class="row justify-content-between position-relative w-100 m-0 mb-2">
                    <div class="ke_title mw-75 pe-2 ps-0">
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
                </div>

                <!-- Right Side -->
                @foreach($this->groups() as $group)
                    @if($group->tab == 'none' || $group->tab = null)
                        <x-dynamic-component
                            :component="$group->component"
                            :value="$group"
                        >
                        </x-dynamic-component>
                    @endif
                @endforeach

                @if($this->tabs())
                <div class="k_notebokk_headers">
                    <!-- Tab Link -->
                    <ul class="nav nav-tabs flex-row flex-nowrap" data-bs-toggle="tabs">
                        @foreach ($this->tabs() as $tab)
                        <li class="nav-item">
                            <a class="nav-link {{ $tab->key == 'order' || $tab->key == 'purchase' || $tab->key == 'general' ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $tab->key }}">{{ $tab->label }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Tab Content -->
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
    <div class="k-loading cursor-pointer pb-1" wire:loading>
        <p>En cours de chargement ...</p>
    </div>

</div>
