
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
                    {{-- <livewire:app::components.notification-trigger /> --}}
                <!-- Chat & Notifications End -->

                <!-- User's Avatar -->
                <div class="nav-item dropdown">
                    <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url({{ Storage::url('avatars/' . Auth::guard('admin')->user()->avatar) }})"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('admin.logout')}}">
                            @csrf
                            <span  onclick="event.preventDefault(); this.closest('form').submit();" class="cursor-pointer kover-navlink dropdown-item">
                                {{__('Log Out')}}
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

                    </div>
                    <!-- Navbar Menu -->
                </ul>
            </div>
        </div>
        <!-- Navbar Menu End -->

    </div>

    <!-- Controls Panel -->
    @yield('control-panel')
    <!-- Controls Panel -->

</nav>
