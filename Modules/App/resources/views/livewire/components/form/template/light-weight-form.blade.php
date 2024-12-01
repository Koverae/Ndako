<div>
    <div class="k_form_sheet_bg">
        <!-- Notify -->
        <x-notify::notify />
        <form wire:submit.prevent="{{ $this->form() }}">
            @csrf
            <div class="k_form_statusbar position-relative d-flex justify-content-between mb-0 mb-md-2 pb-2 pb-md-0">
                <!-- Action Bar -->
                @if($this->actionBarButtons())
                    <div id="action-bar" class="k_statusbar_buttons d-none d-lg-flex align-items-center align-content-around flex-wrap gap-1">

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
                                :status="'none'"
                            >
                            </x-dynamic-component>
                            @endforeach
                            <!--<li><hr class="dropdown-divider"></li>-->
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Sheet Card -->
            <div class="k_form_sheet position-relative">
                <!-- Capsule -->
                @if(count($this->capsules()) >= 1)
                <div class="k_horizontal_asset" id="k_horizontal_capsule">
                    @foreach($this->capsules() as $capsule)
                    <x-dynamic-component
                        :component="$capsule->component"
                        :value="$capsule"
                    >
                    </x-dynamic-component>
                    @endforeach
                </div>
                @endif
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
            </div>
        </form>
    </div>
</div>
