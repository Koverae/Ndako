@section('title', "Dashboards")

    @section('styles')
        <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        body{
            overflow-x: hidden;
            /* overflow-y: hidden; */
        }
          body::-webkit-scrollbar {
              display: none;
          }

          /* Hide scrollbar for IE, Edge, and Firefox */
          body {
              -ms-overflow-style: none;  /* IE and Edge */
              scrollbar-width: none;  /* Firefox */
          }
        </style>
    @endsection

    <div class="p-0 container-fluid">
        <div class="row g-3">
            <!-- Side Bar -->
            <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white border-left d-none d-lg-block col-md-2 app-sidebar bg-view position-relative pe-1 ps-3" style=" z-index: 500;">
            <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">

                <!-- Dashboard -->
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Dashboard') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">

                    <a  href="{{ route('admin.dashboard', ['dash' => 'home']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'home' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('User & Customers KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'revenue']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'revenue' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Revenue & Billing KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'properties']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'properties' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Properties & Units KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'bookings']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'bookings' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Booking & Reservation KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'engagements']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'engagements' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Engagement & Usage') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'locations']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'locations' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Location Insights') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'operational']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'operational' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Operational KPIs') }}
                        </li>
                    </a>

                </ul>
                <!-- Dashboard End -->

              </form>
            </div>
            <!-- Apps List -->
            <div class="p-3 overflow-y-auto bg-white col-12 col-md-12 col-lg-10" style="height: 100vh;">
                @if($dash == 'home')
                    <livewire:admin::dashboards.user-customer-dashboard />
                @elseif($dash == 'revenue')
                    <livewire:admin::dashboards.revenue-billing-dashboard />
                @endif
            </div>
        </div>
    <!-- Mobile Version of Dashboard Module -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="dashboardOffcanvas" aria-labelledby="offcanvasEndLabel">
        <div class="offcanvas-header">
        <h1 class="offcanvas-title h1" id="offcanvasEndLabel">{{ __('Dashboards') }}</h1>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="p-0 offcanvas-body">
            <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white border-left col-md-12 app-sidebar bg-view position-relative pe-1 ps-3" style=" z-index: 500;">
              <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">

                <!-- Dashboard -->
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Dashboard') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">

                    <a  href="{{ route('admin.dashboard', ['dash' => 'home']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'home' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('User & Customers KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'revenue']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'revenue' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Revenue & Billing KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'properties']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'properties' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Properties & Units KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'bookings']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'bookings' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Booking & Reservation KPIs') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'engagements']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'engagements' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Engagement & Usage') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'locations']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'locations' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Location Insights') }}
                        </li>
                    </a>

                    <a  href="{{ route('admin.dashboard', ['dash' => 'operational']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'operational' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Operational KPIs') }}
                        </li>
                    </a>

                </ul>

              </form>
            </div>
        </div>
    </div>
    <!-- Mobile Version of Dashboard Module End -->
    </div>
