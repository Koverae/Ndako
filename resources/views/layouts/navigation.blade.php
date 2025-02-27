
<nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo -->
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="">
                <img src="{{asset('assets/images/logo/logo-black.png')}}" alt="Ndako Logo" class="navbar-brand-image">
            </a>
        </h1>
        <!-- Logo End -->

        <!-- Navbar Buttons -->
        <div class="flex-row navbar-nav order-md-last">
            <div class="d-md-flex d-flex">
                <!-- Translate -->
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="px-0 nav-link" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="bi bi-translate" style="font-size: 16px;"></i>
                    </a>
                </div>
                <!-- Translate End -->
                <!-- Chat & Notifications -->
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a class="px-0 nav-link" data-bs-toggle="offcanvas" href="#notificationOffcanvas" role="button" aria-controls="offcanvasEnd">
                        <i class="bi bi-chat" style="font-size: 16px;"></i>
                        <span class="badge bg-green"></span>
                    </a>
                </div>
                <!-- Chat & Notifications End -->

                <!-- User's Avatar -->
                <div class="nav-item dropdown">
                    <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . auth()->user()->avatar) }})"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="#" class="dropdown-item kover-navlink">Documentation</a>
                        <a href="#" class="dropdown-item kover-navlink divider">Support</a>
                        <a href="#" class="dropdown-item kover-navlink">Dark Mode</a>
                        <hr class="dropdown-divider">
                        <a href="{{ route('settings.users.show', ['user' => auth()->user()->id]) }}" class="dropdown-item kover-navlink">My Profile</a>
                        @can('manage_kover_subscription')
                        <a href="#" class="dropdown-item kover-navlink">My Databases</a>
                        <a href="#" class="dropdown-item kover-navlink">My Subscription</a>
                        @endcan
                        <hr class="dropdown-divider">
                        @can('install_pwa')
                        <a href="#" class="dropdown-item kover-navlink">Install the App</a>
                        @endcan
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout')}}">
                            @csrf
                            <span  onclick="event.preventDefault(); this.closest('form').submit();" class="cursor-pointer kover-navlink dropdown-item">
                                Log Out
                            </span>
                        </form>
                        <!-- Authentication End -->
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
                    <!-- Navbar Menu -->
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

                        <li class="nav-item" data-turbolinks>
                            <a class="nav-link kover-navlink" href="{{ route('dashboard') }}" style="margin-right: 5px;">
                              <span class="nav-link-title">
                                  {{ __('Dashboard') }}
                              </span>
                            </a>
                        </li>

                        @can('manage_properties')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                              <span class="nav-link-title">
                                  {{ __('Properties') }}
                              </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <!-- Left Side -->
                                    <div class="dropdown-menu-column">
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('properties.lists') }}">
                                            {{ __('Properties') }}
                                        </a>
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('properties.units.lists') }}">
                                            {{ __('Units') }}
                                        </a>
                                        @can('view_maintenance_tasks')
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('tasks.lists') }}">
                                            {{ __('Maintenance Requests') }}
                                        </a>
                                        @endcan

                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan

                        @can('view_rooms')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                              <span class="nav-link-title">
                                  {{ __('Rooms') }}
                              </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <!-- Left Side -->
                                    <div class="dropdown-menu-column">
                                        @can('view_rooms')
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('properties.units.lists') }}">
                                            {{ __('Rooms') }}
                                        </a>
                                        @endcan
                                        @can('view_maintenance_tasks')
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('tasks.lists') }}">
                                            {{ __('Maintenance Requests') }}
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan

                        @can('manage_reservations')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                              <span class="nav-link-title">
                                  {{ __('Reservations') }}
                              </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <!-- Left Side -->
                                    <div class="dropdown-menu-column">
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('bookings.lists') }}">
                                            {{ __('Reservations') }}
                                        </a>
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('bookings.payments.lists') }}">
                                            {{ __('Payments') }}
                                        </a>
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('guests.lists') }}">
                                            {{ __('Guests') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan

                        @can('access_settings')
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                              <span class="nav-link-title">
                                  {{ __('Configuration') }}
                              </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <!-- Left Side -->
                                    <div class="dropdown-menu-column">
                                        @can('access_settings')
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('settings.general', ['view' => 'general']) }}">
                                            {{ __('Settings') }}
                                        </a>
                                        @endcan
                                        @can('manage_staff')
                                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('settings.users') }}">
                                            {{ __('Users') }}
                                        </a>
                                        @endcan
                                        @can('manage_roles')
                                        <a class=" kover-navlink dropdown-item" href="{{ route('roles.lists') }}" wire:navigate>
                                            {{ __('Roles & Permissions') }}
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endcan

                    </div>
                    <!-- Navbar Menu -->
                </ul>
            </div>
        </div>
        <!-- Navbar Menu End -->

    </div>

    @if(!auth()->user()->onboarding_completed || auth()->user()->onboarding_step < 6)
    <div class="alert alert-warning {{ Route::currentRouteName() == 'onboarding' ? 'd-none' : '' }} d-flex align-items-center justify-content-between p-3 fs-5 sticky-top shadow-sm alert-dismissible fade show" role="alert">
        <span class="fs-3"><i class="bi bi-exclamation-circle me-2"></i> Get the most out of Ndako! Let's complete your setup</span>
        <div>
            <a href="{{ route('onboarding') }}" class="btn btn-sm rounded btn-primary me-2 fs-3">Start Now</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- Controls Panel -->
    @yield('control-panel')
    <!-- Controls Panel -->

</nav>
