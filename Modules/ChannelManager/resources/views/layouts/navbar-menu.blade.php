<div>
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

        <li class="nav-item">
            <a class="nav-link kover-navlink dropdown" wire:navigate href="{{ Route::subdomainRoute('channels.index') }}" style="margin-right: 5px;">
              <span class="nav-link-title">
                  {{ __('Overview') }}
              </span>
            </a>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  {{ __('Channels') }}
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('channels.lists') }}">
                            {{ __('Manage Channel') }}
                        </a>
                        {{-- <a class="kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('settings.companies.index') }}">
                            {{ __('Sync Logs') }}
                        </a> --}}

                    </div>
                </div>
            </div>
        </li>

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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('properties.lists') }}">
                            {{ __('Properties') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('properties.units.lists') }}">
                            {{ __('Units') }}
                        </a>

                    </div>
                </div>
            </div>
        </li>

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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('bookings.lists') }}">
                            {{ __('Reservations') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('bookings.lists') }}">
                            {{ __('Payments') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('guests.lists') }}">
                            {{ __('Guests') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('bookings.lists') }}">
                            {{ __('Sync Reservations') }}
                        </a>

                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  {{ __('Rates & Availability') }}
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('properties.lists') }}">
                            {{ __('Manage Rates') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('properties.units.lists') }}">
                            {{ __('Sync Rates') }}
                        </a>

                    </div>
                </div>
            </div>
        </li>

        {{-- <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  {{ __('Reports') }}
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('settings.users') }}">
                            {{ __('Channel Perfomance') }}
                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('settings.companies.index') }}">
                            {{ __('Reservation Insights') }}
                        </a>

                    </div>
                </div>
            </div>
        </li> --}}

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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('settings.general', ['view' => 'channel-manager']) }}">
                            {{ __('Settings') }}
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </div>
</div>
