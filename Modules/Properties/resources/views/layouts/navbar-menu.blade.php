<div>
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
    
        <li class="nav-item">
            <a class="nav-link kover-navlink dropdown" wire:navigate href="{{ route('properties.index') }}" style="margin-right: 5px;">
              <span class="nav-link-title">
                  {{ __('Overview') }}
              </span>
            </a>
        </li>
    
        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  {{ __('Operations') }}
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('settings.users') }}">
                            {{ __('Users') }}
                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('settings.companies.index') }}">
                            {{ __('Enterprises') }}
                        </a>
    
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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('properties.lists') }}">
                            {{ __('Properties') }}
                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('properties.units.lists') }}">
                            {{ __('Units') }}
                        </a>
    
                    </div>
                </div>
            </div>
        </li>
    
        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  {{ __('Reporting') }}
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('settings.users') }}">
                            {{ __('Users') }}
                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('settings.companies.index') }}">
                            {{ __('Enterprises') }}
                        </a>
    
                    </div>
                </div>
            </div>
        </li>
    
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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ route('settings.general', ['view' => 'properties']) }}">
                            {{ __('Settings') }}
                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="{{ route('settings.companies.index') }}">
                            {{ __('Enterprises') }}
                        </a>
    
                    </div>
                </div>
            </div>
        </li>
    </div>
</div>