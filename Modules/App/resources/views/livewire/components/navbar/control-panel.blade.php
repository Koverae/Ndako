<div>

    <div class="gap-3 px-3 k_control_panel d-flex flex-column gap-lg-1 sticky-top">
        <div class="gap-5 k_control_panel_main d-flex flex-nowrap justify-content-between align-items-lg-start flex-grow-1">
            <!-- Breadcrumbs -->
            <div class="gap-1 k_control_panel_breadcrumbs d-flex align-items-center order-0 h-lg-100">
                <!-- Create Button -->
                @if($this->new)
                <a href="{{ $new }}" wire:navigate class="btn btn-outline-primary k_form_button_create">
                    {{ __('New') }}
                </a>
                @endif
                @if($this->add)
                <a wire:click="add" class="btn btn-outline-primary k_form_button_create">
                    {{ $createButtonLabel }}
                </a>
                @endif

                @php
                    $filteredBreadcrumbs = collect($breadcrumbs)->filter(fn($breadcrumb) =>
                        $breadcrumb['url'] && $breadcrumb['url'] !== url()->current()
                    );
                @endphp

                <div class="min-w-0 gap-2 k_last_breadcrumb_item active align-items-center lh-sm">

                    @if($showBreadcrumbs && $filteredBreadcrumbs->isNotEmpty())
                    <span>
                        @foreach($filteredBreadcrumbs as $breadcrumb)
                            @if(!$loop->first)
                                <span class="mx-1">/</span>
                            @endif

                            @if(!empty($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}" wire:navigate class="fw-bold text-truncate text-decoration-none">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            @else
                                <span class="fw-bold text-truncate text-decoration-none" aria-current="page">
                                    {{ $breadcrumb['label'] }}
                                </span>
                            @endif
                        @endforeach
                    </span>
                    @endif
                    <div class="gap-1 d-flex">
                        <span class="min-w-0 text-truncate " style="height: 19px;">
                            {{ $this->currentPage }}
                        </span>
                        <div class="gap-1 k_cp_action_menus d-flex align-items-center pe-2">

                            <div class="k_dropdown dropdown dropend lh-1 dropdown-no-caret">
                                <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear" wire:loading.remove></i>
                                </a>
                                <div class="k_dropdown_menu dropdown-menu dropdown-menu-end">

                                    @foreach($this->actionButtons() as $action_button)
                                    <x-dynamic-component
                                        :component="$action_button->component"
                                        :value="$action_button"
                                    >
                                    </x-dynamic-component>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if($this->showIndicators)
                        <div class="k_form_status_indicator_buttons d-flex">
                            <span wire:loading.remove wire:click.prevent="saveUpdate()" wire:target="saveUpdate()" class="px-1 py-0 cursor-pointer k_form_button_save btn-light rounded-1 lh-sm">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </span>
                            <span wire:click.prevent="resetForm()" wire:loading.remove class="px-1 py-0 cursor-pointer k_form_button_save btn-light lh-sm">
                                <i class="bi bi-arrow-return-left"></i>
                            </span>
                            <span wire:loading wire:target="saveUpdate()">...</span>
                        </div>
                        @endif
                        @if($this->change)
                        <span class="p-0 ml-2 fs-4">{{ __('Usaved changes') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if(!$this->isForm)
            <!-- Actions / Search Bar -->

            <!-- Actions -->

            {{-- If items selected: Show selected actions --}}
            @if($hasSelection)
            <div id="actions" class="order-2 gap-2 d-none d-lg-inline-flex rounded-2 k_panel_control_actions_search d-flex align-items-center justify-content-between order-lg-1 ">

                <div class="gap-3 d-flex align-items-center">
                    <div class="k_cp_switch_buttons align-items-center">

                        <span class="w-auto gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list">
                            {{ count($selected) }} selected
                            <i wire:click="emptyArray" class="bi bi-x"></i>
                        </span>

                        <!-- Action Buttons -->

                        <!-- Dropdown button -->
                        <div class="btn-group">
                            <span class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list w-100" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fas fa-cog"></i> Actions
                            </span>
                            <ul class="dropdown-menu">
                                @foreach($this->actionDropdowns() as $action)
                                <x-dynamic-component
                                    :component="$action->component"
                                    :value="$action"
                                >
                                </x-dynamic-component>
                                @endforeach
                                <!--<li><hr class="dropdown-divider"></li>-->
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Actions -->

            @else
            <!-- Search Bar -->
            <div class="order-2 gap-2 d-none d-lg-inline-flex rounded-2 k_panel_control_actions_search d-flex align-items-center justify-content-between order-lg-1">
                {{-- Otherwise: Show Search Bar --}}
                <div class="gap-2 d-flex align-items-center">
                    <span class="p-1 border-0 cursor-pointer">
                        <i class="bi bi-search"></i>
                    </span>

                    <div class="gap-2 d-flex">
                        <!-- Filters -->
                        @foreach ($filters as $key => $values)
                            @php
                                $valueList = is_array($values) ? $values : [$values];
                            @endphp

                            <span class="cursor-pointer fs-4" style="background-color: #D8DADD;" wire:click="removeFilter('{{ $key }}')">
                                <i class="p-1 text-white fas fa-filter rounded-2" style="background-color: #52374B;"></i>
                                {{ implode(' > ', array_map('ucfirst', $valueList)) }}
                                <i class="bi bi-x fs-3"></i>
                            </span>
                        @endforeach
                        <!-- Filters -->

                        <!-- Group By -->
                        <span class="cursor-pointer fs-4"style="background-color: #D8DADD;">
                            <i class="p-1 text-white fas fa-layer-group rounded-2" style="background-color: #017E84;"></i>
                            Urban Nest
                            <i class="bi bi-x fs-3"></i>
                        </span>
                        <!-- Group By -->
                    </div>
                    <input type="text" wire:model.live='search' placeholder="Search..." class="w-auto k_searchview">
                    <div class="dropdown k_filter_search align-items-end">
                        <span class="btn dropdown-toggle rounded-0" style="height: 34px;" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                            &nbsp;
                            <!-- Filter icon or text -->
                        </span>
                        <!-- Filter dropdown -->

                        <!-- Dropdown Menu -->
                        <div class="p-3 dropdown-menu" aria-labelledby="dropdownMenu2">
                            <!-- Loop through the filter groups dynamically -->
                            @foreach($filterTypes as $group => $options)
                            <!-- Filter Group: type, status, etc. -->
                            <h6 class="dropdown-header">{{ ucfirst($group) }}</h6>

                            @foreach($options as $option)
                                <div class="gap-2 cursor-pointer d-flex rounded-2" wire:click="toggleFilter('{{ $group }}', '{{ $option }}')"
                                    style="{{ in_array($option, $filters[$group] ?? []) ? 'background-color: #D8DADD; font-weight: bold;' : '' }}">

                                    <!-- Checkmark if selected -->
                                    <i class="p-2 fas fa-check fw-bold {{ in_array($option, $filters[$group] ?? []) ? '' : 'd-none' }}" style="color: #017E84;"></i>

                                    <span class="p-1 form-check-label">
                                        {{ ucfirst($option) }}
                                    </span>
                                </div>
                            @endforeach

                            <hr class="dropdown-divider">
                            @endforeach
                        </div>
                        <!-- Dropdown Menu -->

                    </div>

                </div>
            </div>
            <!-- Search Bar -->
            @endif

            <!-- Actions / Search Bar -->
            @endif

            <!-- Navigations -->
            @if(!$this->isForm)
            <div class="flex-wrap order-3 align-items-end k_control_panel_navigation d-flex flex-md-wrap align-items-center justify-content-end gap-l-1 gap-xl-5 order-lg-2 flex-grow-1">
                <!-- Display panel buttons -->
                <div class="k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group">
                    <!-- Button view -->
                    @foreach($this->switchButtons() as $switchButton)
                    <x-dynamic-component
                        :component="$switchButton->component"
                        :value="$switchButton"
                        {{-- :status="$status" --}}
                    >
                    </x-dynamic-component>
                    @endforeach

                </div>
            </div>
            @endif

        </div>
    </div>
</div>
