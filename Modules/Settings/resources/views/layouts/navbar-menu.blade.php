<div>
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
    
        <li class="nav-item">
            <a class="nav-link kover-navlink dropdown" wire:navigate href="{{ Route::subdomainRoute('settings.general') }}" style="margin-right: 5px;">
              <span class="nav-link-title">
                  Genral Settings
              </span>
            </a>
        </li>
    
        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  Users & Enterprises
              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="{{ Route::subdomainRoute('settings.users') }}">
                            Users
                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="#">
                            Enterprises
                        </a>
    
                    </div>
                </div>
            </div>
        </li>
    </div>
</div>