<nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo -->
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="">
                <img src="{{ asset('assets/images/logo/logo-black.png') }}" alt="Ndako Logo" class="navbar-brand-image">
            </a>
        </h1>
        <!-- Logo End -->

        <!-- Navbar Buttons -->
        <div class="flex-row navbar-nav order-md-last">
            <div class="d-md-flex d-flex">
                <!-- Translate -->
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="px-0 nav-link" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-placement="bottom">
                        <i class="bi bi-translate" style="font-size: 16px;"></i>
                    </a>
                </div>
                <!-- Translate End -->

                <!-- Shortcuts (role-specific) -->
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Shortcuts') }}">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span class="d-none d-xl-inline fw-semibold">{{ __('Shortcuts') }}</span>
                        <i class="bi bi-chevron-down small opacity-75"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-2" style="min-width: 280px;">

                        {{-- Owner / Manager --}}
                        @hasanyrole('owner|manager')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Owner / Manager') }}</h6>
                            @can('access_settings')
                                <a href="{{ route('settings.general', ['view' => 'general']) }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-gear me-2"></i>{{ __('Settings') }}
                                </a>
                            @endcan
                            @can('manage_staff')
                                <a href="{{ route('settings.users') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-people me-2"></i>{{ __('Users') }}
                                </a>
                            @endcan
                            @can('manage_roles')
                                <a href="{{ route('roles.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-shield-lock me-2"></i>{{ __('Roles & Permissions') }}
                                </a>
                            @endcan
                            @can('manage_properties')
                                <a href="{{ route('properties.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-buildings me-2"></i>{{ __('Properties') }}
                                </a>
                            @endcan
                            @can('manage_expenses')
                                <a href="{{ route('expenses.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-receipt me-2"></i>{{ __('Expenses') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                        @endhasanyrole

                        {{-- Front Office --}}
                        @role('front-office')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Front Office') }}</h6>
                            @canany(['view_reservations','create_reservations','modify_reservations'])
                                <a href="{{ route('bookings.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-calendar2-check me-2"></i>{{ __('Reservations') }}
                                </a>
                            @endcanany
                            @can('view_rooms')
                                <a href="{{ route('properties.units.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-door-open me-2"></i>{{ __('Room Board') }}
                                </a>
                            @endcan
                            @can('manage_guests')
                                <a href="{{ route('guests.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-person-lines-fill me-2"></i>{{ __('Guests') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                        @endrole

                        {{-- Reservations --}}
                        @role('reservations')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Reservations') }}</h6>
                            <a href="{{ route('bookings.lists') }}" class="dropdown-item kover-navlink">
                                <i class="bi bi-calendar-event me-2"></i>{{ __('All Reservations') }}
                            </a>
                            @can('view_reservation_payments')
                                <a href="{{ route('bookings.payments.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-credit-card me-2"></i>{{ __('Payments') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                        @endrole

                        {{-- Housekeeping --}}
                        @role('housekeeping')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Housekeeping') }}</h6>
                            @can('view_housekeeping_board')
                                <a href="{{ route('tasks.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bucket me-2"></i>{{ __('HK Board & Tasks') }}
                                </a>
                            @elsecan('view_maintenance_tasks')
                                <a href="{{ route('tasks.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bucket me-2"></i>{{ __('Tasks') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                        @endrole

                        {{-- Maintenance --}}
                        @role('maintenance')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Maintenance') }}</h6>
                            <a href="{{ route('tasks.lists') }}" class="dropdown-item kover-navlink">
                                <i class="bi bi-tools me-2"></i>{{ __('Work Orders') }}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endrole

                        {{-- Accounting --}}
                        @role('accounting')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('Accounting') }}</h6>
                            @can('manage_invoices')
                                <a href="{{ route('expenses.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-receipt me-2"></i>{{ __('Expenses & Invoices') }}
                                </a>
                            @endcan
                            @can('view_reservation_payments')
                                <a href="{{ route('bookings.payments.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-cash-coin me-2"></i>{{ __('Reservation Payments') }}
                                </a>
                            @endcan
                            @can('view_pos_payments')
                                <a href="{{ route('order-payments.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-wallet2 me-2"></i>{{ __('POS Payments') }}
                                </a>
                            @endcan
                            @can('view_pos_sessions')
                                <a href="{{ route('pos-sessions.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-clock-history me-2"></i>{{ __('Cash Sessions') }}
                                </a>
                            @endcan
                            <div class="dropdown-divider"></div>
                        @endrole

                        {{-- Cashier --}}
                        @role('cashier')
                            <h6 class="dropdown-header text-uppercase text-muted small">{{ __('POS') }}</h6>
                            <a href="{{ route('pos.overview') }}" class="dropdown-item kover-navlink">
                                <i class="bi bi-grid-1x2 me-2"></i>{{ __('POS Overview') }}
                            </a>
                            @can('manage_pos_orders')
                                <a href="{{ route('orders.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bag-check me-2"></i>{{ __('Orders') }}
                                </a>
                            @endcan
                            @can('view_pos_sessions')
                                <a href="{{ route('pos-sessions.lists') }}" class="dropdown-item kover-navlink">
                                    <i class="bi bi-clock-history me-2"></i>{{ __('Sessions') }}
                                </a>
                            @endcan
                        @endrole

                        {{-- Extra injection point --}}
                        @if(trim($__env->yieldContent('shortcuts_extra')))
                            <div class="dropdown-divider"></div>
                            @yield('shortcuts_extra')
                        @endif
                    </div>
                </div>
                <!-- /Shortcuts -->

                {{-- SWITCH COMPANY --}}
                @php
                    $userCompanies = current_company()->team->companies()->orderBy('companies.name')->get();
                    $currentCompany = current_company();
                @endphp

                @if($userCompanies->count() > 0)
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Switch company') }}">
                        @if($currentCompany?->avatar)
                            <span class="avatar avatar-sm rounded" style="background-image:url({{ Storage::url("/avatars/{$currentCompany->avatar}") }})"></span>
                        @else
                            <span class="avatar avatar-sm rounded" style="background-image:url({{ asset('assets/images/default/placeholder.png') }})"></span>
                        @endif
                        <span class="d-none d-sm-inline fw-semibold text-truncate" style="max-width: 140px;">
                            {{ \Illuminate\Support\Str::limit($currentCompany?->name, 24) }}
                        </span>
                        <i class="bi bi-chevron-down small opacity-75"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-3" style="min-width: 320px;">
                        <div class="mb-2 position-relative">
                            <input type="search" class="form-control form-control-sm" placeholder="{{ __('Search companies…') }}" id="companySearch">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                        </div>

                        <ul class="list-unstyled mb-2" id="companyList">
                            @foreach($userCompanies as $company)
                                <li class="mb-1">
                                    <form method="POST" action="{{ route('company.switch') }}" class="m-0 p-0">
                                        @csrf
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded {{ $currentCompany && $currentCompany->id === $company->id ? 'active' : '' }}">
                                            @if($company->avatar)
                                                <span class="avatar avatar-sm rounded" style="background-image:url({{ Storage::url("/avatars/{$company->avatar}") }})"></span>
                                            @else
                                                <span class="avatar avatar-sm rounded" style="background-image:url({{ asset('assets/images/default/placeholder.png') }})"></span>
                                            @endif
                                            <div class="flex-grow-1 text-start">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-semibold">{{ $company->name }}</span>
                                                    @if($currentCompany && $currentCompany->id === $company->id)
                                                        <span class="badge bg-primary-subtle text-white">{{ __('Current') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>

                        @can('access_companies')
                        <div class="d-grid pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> {{ __('Create new company') }}
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>
                @endif
                {{-- /SWITCH COMPANY --}}

                <!-- Chat & Notifications -->
                <livewire:app::components.notification-trigger />
                <!-- Chat & Notifications End -->

                <!-- User's Avatar -->
                <div class="nav-item dropdown">
                    <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="{{ route('settings.users.show', ['user' => auth()->user()->id]) }}" class="dropdown-item kover-navlink">My Profile</a>
                        @can('manage_kover_subscription')
                        <a href="{{ route('settings.general') . '#subs' }}" class="dropdown-item kover-navlink">My Subscription</a>
                        @endcan
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <span onclick="event.preventDefault(); this.closest('form').submit();" class="cursor-pointer kover-navlink dropdown-item">
                                {{ __('Log Out') }}
                            </span>
                        </form>
                    </div>
                </div>
                <!-- User's Avatar End -->
            </div>
        </div>
        <!-- Navbar Buttons End -->

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

                        <!-- Dashboard -->
                        <li class="nav-item" data-turbolinks>
                            @can('view_dashboard')
                            <a class="nav-link kover-navlink" href="{{ route('dashboard') }}" style="margin-right: 5px;">
                                <span class="nav-link-title">{{ __('Dashboard') }}</span>
                            </a>
                            @endcan
                        </li>

                        <!-- Properties (Owner/Manager only; no Rooms/Maintenance here to avoid duplication) -->
                        @hasanyrole('owner|manager')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Properties') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        @can('manage_properties')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('properties.lists') }}">
                                            {{ __('Properties') }}
                                        </a>
                                        @endcan
                                        {{-- Intentionally no Rooms or Maintenance links here --}}
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endhasanyrole

                        <!-- Rooms (single hub for Rooms/HK/Maintenance) -->
                        @if(auth()->user()->hasAnyRole(['owner','manager','front-office','housekeeping','maintenance']))
                        @canany(['view_rooms','view_housekeeping_board','view_maintenance_tasks'])
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Rooms') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        @can('view_rooms')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('properties.units.lists') }}">
                                            {{ __('Rooms') }}
                                        </a>
                                        @endcan

                                        @can('view_housekeeping_board')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('tasks.lists') }}">
                                            {{ __('Housekeeping Board') }}
                                        </a>
                                        @endcan

                                        @can('view_maintenance_tasks')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('tasks.lists') }}">
                                            {{ __('Maintenance Requests') }}
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcanany
                        @endif

                        <!-- Reservations (Owner/Manager/FO/Reservations) -->
                        @hasanyrole('owner|manager|front-office|reservations')
                        @canany(['view_reservations','create_reservations','modify_reservations','cancel_reservations','manage_reservations'])
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Reservations') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('bookings.lists') }}">
                                            {{ __('Reservations') }}
                                        </a>
                                        @can('view_reservation_payments')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('bookings.payments.lists') }}">
                                            {{ __('Payments') }}
                                        </a>
                                        @endcan
                                        @can('manage_guests')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('guests.lists') }}">
                                            {{ __('Guests') }}
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcanany
                        @endhasanyrole

                        <!-- Restaurants / POS (Owner/Manager/Cashier/Accounting) -->
                        @hasanyrole('owner|manager|cashier|accounting')
                        @can('access_pos')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Restaurants') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('pos.overview') }}">
                                            {{ __('Overview') }}
                                        </a>
                                        @can('manage_pos_products')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('product-categories.lists') }}">
                                            {{ __('Product Categories') }}
                                        </a>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('products.lists') }}">
                                            {{ __('Products') }}
                                        </a>
                                        @endcan
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('pos-floors.lists') }}">
                                            {{ __('Floor Plans') }}
                                        </a>
                                    </div>
                                    <div class="dropdown-menu-column">
                                        @can('manage_pos_orders')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('orders.lists') }}">
                                            {{ __('Orders') }}
                                        </a>
                                        @endcan
                                        @can('view_pos_sessions')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('pos-sessions.lists') }}">
                                            {{ __('Sessions') }}
                                        </a>
                                        @endcan
                                        @can('view_pos_payments')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('order-payments.lists') }}">
                                            {{ __('Payments') }}
                                        </a>
                                        @endcan
                                        {{-- No Guests link here to avoid duplication with Reservations --}}
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan
                        @endhasanyrole

                        <!-- Expenses (Accounting/Manager/Owner via permission) -->
                        @can('manage_expenses')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Expenses') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('expenses.categories.lists') }}">
                                            {{ __('Expense Categories') }}
                                        </a>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('expenses.lists') }}">
                                            {{ __('Expenses') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan

                        <!-- Configuration (Owner/Manager only) -->
                        @hasanyrole('owner|manager')
                        @can('access_settings')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title">{{ __('Configuration') }}</span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        @can('access_settings')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('settings.general', ['view' => 'general']) }}">
                                            {{ __('Settings') }}
                                        </a>
                                        @endcan
                                        @can('manage_staff')
                                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('settings.users') }}">
                                            {{ __('Users') }}
                                        </a>
                                        @endcan
                                        @can('manage_roles')
                                        <a class="kover-navlink dropdown-item" href="{{ route('roles.lists') }}" wire:navigate>
                                            {{ __('Roles & Permissions') }}
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan
                        @endhasanyrole

                    </div>
                </ul>
            </div>
        </div>
        <!-- Navbar Menu End -->

    </div>

    {{-- Onboarding Banner --}}
    @if(!current_company()->is_onboarded && auth()->user()->hasAnyRole(['owner','manager']))
    <div class="alert alert-primary border-0 text-primary-emphasis rounded-3 shadow-sm sticky-top {{ Route::currentRouteName() == 'onboarding' ? 'd-none' : '' }} d-flex align-items-center justify-content-between py-3 px-4 mb-3" role="alert">
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill text-bg-primary d-inline-flex align-items-center justify-content-center me-3" style="width:2.25rem;height:2.25rem;">
            <i class="bi bi-rocket-takeoff"></i>
            </span>
            <div>
            <div class="fw-semibold">{{ __('Get the most out of Ndako! Let’s complete your setup') }}</div>
            <small class="text-secondary d-none d-sm-inline">{{ __('Finish company, property, rooms & taxes in a few steps.') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('onboarding') }}" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-magic me-1"></i>{{ __('Continue setup') }}
            </a>
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-primary" data-bs-dismiss="alert" aria-label="Close">
            {{ __('Dismiss') }}
            </button>
        </div>
    </div>
    @endif
    
    {{-- Trial banner --}}
    @if(current_company()->team->subscription('main')->isOnTrial())
    @php
        $daysLeft   = (int) getRemainingTrialDays();
        $trialDays  = 14;
        $pct        = max(0, min(100, (($trialDays - $daysLeft) / max(1,$trialDays)) * 100));
        $trialEndsAt = optional(current_company()->team->subscription('main'))->trial_ends_at;
    @endphp
    <div class="alert alert-warning bg-warning-subtle border-0 text-warning-emphasis rounded-3 shadow-sm mt-2" role="alert">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">

        <!-- Left: icon + text -->
        <div class="d-flex w-100 w-md-auto">
        <span class="badge rounded-pill text-bg-warning d-inline-flex align-items-center justify-content-center me-2 me-md-3 flex-shrink-0" style="width:2.25rem;height:2.25rem;">
            <i class="bi bi-hourglass-split"></i>
        </span>

        <div class="text-wrap">
            <div class="fw-semibold mb-1">{{ __('Your trial is ending soon') }}</div>
            <div class="small">
            {!! __('Your trial will expire in <b>:days</b>.', ['days' => getRemainingTrialDays()]) !!}
            @if($trialEndsAt)
                <span class="ms-1 d-inline-block">{{ __('Ends on') }} <b>{{ \Carbon\Carbon::parse($trialEndsAt)->toFormattedDateString() }}</b></span>
            @endif
            <span class="ms-1 d-inline-block">{{ __('Upgrade now to keep everything running smoothly.') }}</span>
            </div>

            <div class="progress mt-2" style="height:6px;">
            <div class="progress-bar progress-bar-striped" role="progressbar"
                style="width: {{ $pct }}%;" aria-valuenow="{{ (int)$pct }}" aria-valuemin="0" aria-valuemax="100">
            </div>
            </div>
        </div>
        </div>

        <!-- Right: actions -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 ms-0 ms-md-3 w-100 w-md-auto">
        <a href="{{ route('subscribe') }}" class="btn btn-warning btn-sm fw-semibold w-100 w-sm-auto">
            <i class="bi bi-stars me-1"></i>{{ __('Upgrade now') }}
        </a>
        <button type="button" class="btn btn-link btn-sm text-warning text-decoration-none w-100 w-sm-auto text-start text-sm-center" data-bs-dismiss="alert">
            {{ __('Remind me later') }}
        </button>
        </div>

    </div>
    </div>
    @endif



    <!-- Controls Panel -->
    @yield('control-panel')
    <!-- Controls Panel -->
</nav>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('companySearch');
    if (!input) return;
    const items = document.querySelectorAll('#companyList li');
    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        items.forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
@endpush
