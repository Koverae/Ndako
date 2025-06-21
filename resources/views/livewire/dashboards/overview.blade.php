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

                @if(!Auth::user()->can('view_reports'))
                <ul class="pt-3" style="margin-left: 10px;">
                    <a  href="{{ route('dashboard', ['dash' => 'home']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'home' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Home') }}
                        </li>
                    </a>
                </ul>
                @endif

                @can('view_reservation_reports')
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Reservations') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">

                    <a  href="{{ route('dashboard', ['dash' => 'reservations']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'reservations' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Reservations') }}
                        </li>
                    </a>
                    <a  href="{{ route('dashboard', ['dash' => 'properties']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink {{ $dash == 'properties' ? 'selected' : '' }} text-decoration-none panel-category">
                        {{ __('Rooms') }}
                        </li>
                    </a>
                </ul>
                @endcan

                @can('view_financial_reports')
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Revenue & Financials') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    <a  href="{{ route('dashboard', ['dash' => 'invoicing']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'invoicing' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                {{ __('Invoicing') }}
                        </li>
                    </a>
                    <a  href="{{ route('dashboard', ['dash' => 'expense']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'expense' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                {{ __('Expenses') }}
                        </li>
                    </a>
                </ul>
                @endcan

                @can('view_property_reports')
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Properties') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    <a  href="{{ route('dashboard', ['dash' => 'property']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'property' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Properties') }}
                        </li>
                    </a>
                    <a  href="{{ route('dashboard', ['dash' => 'tickets']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'tickets' ? 'background-color: #E6F2F3 ;' : '' }} ">
                        {{ __('Maintenance Overview') }}
                        </li>
                    </a>
                </ul>
                @endcan

                @can('view_financial_reports')
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Restaurants') }}</b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    <a  href="{{ route('dashboard', ['dash' => 'orders']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'orders' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                {{ __('Orders') }}
                        </li>
                    </a>
                    <a  href="{{ route('dashboard', ['dash' => 'products']) }}" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'products' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                {{ __('Products') }}
                        </li>
                    </a>
                </ul>
                @endcan

            </form>
          </div>
          <!-- Apps List -->
          <div class="p-3 overflow-y-auto bg-white col-12 col-md-12 col-lg-10" style="height: 100vh;">
            @if($dash == 'home')
            <livewire:settings::dashboards.home-dashboard />
            @elseif($dash == 'reservations')
            <livewire:channelmanager::dashboards.reservation />
            @elseif($dash == 'properties')
            <livewire:channelmanager::dashboards.room />
            @elseif($dash == 'invoicing')
            <livewire:revenuemanager::dashboards.invoicing />
            @elseif($dash == 'expense')
            <livewire:revenuemanager::dashboards.expense />
            @elseif($dash == 'property')
            <livewire:properties::dashboards.property />
            @elseif($dash == 'tickets')
            <livewire:properties::dashboards.ticket />
            @elseif($dash == 'orders')
            <livewire:pos::dashboards.order />
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

                  @if(!Auth::user()->can('view_reports'))
                  <ul class="pt-3" style="margin-left: 10px;">
                      <a  href="{{ route('dashboard', ['dash' => 'home']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'home' ? 'background-color: #E6F2F3 ;' : '' }} ">
                          {{ __('Home') }}
                          </li>
                      </a>
                  </ul>
                  @endif

                  @can('view_reservation_reports')
                  <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Reservations') }}</b></header>
                  <ul class="mb-4" style="margin-left: 10px;">

                      <a  href="{{ route('dashboard', ['dash' => 'reservations']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'reservations' ? 'background-color: #E6F2F3 ;' : '' }} ">
                          {{ __('Reservations') }}
                          </li>
                      </a>
                      <a  href="{{ route('dashboard', ['dash' => 'properties']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink {{ $dash == 'properties' ? 'selected' : '' }} text-decoration-none panel-category">
                          {{ __('Rooms') }}
                          </li>
                      </a>
                  </ul>
                  @endcan

                  @can('view_financial_reports')
                  <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Revenue & Financials') }}</b></header>
                  <ul class="mb-4" style="margin-left: 10px;">
                      <a  href="{{ route('dashboard', ['dash' => 'invoicing']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'invoicing' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                  {{ __('Invoicing') }}
                          </li>
                      </a>
                      <a  href="{{ route('dashboard', ['dash' => 'expense']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'expense' ? 'background-color: #E6F2F3 ;' : '' }} ">
                                  {{ __('Expenses') }}
                          </li>
                      </a>
                  </ul>
                  @endcan

                  @can('view_property_reports')
                  <header class="pt-3 form-label font-weight-bold text-uppercase"> <b>{{ __('Properties') }}</b></header>
                  <ul class="mb-4" style="margin-left: 10px;">
                      <a  href="{{ route('dashboard', ['dash' => 'property']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'property' ? 'background-color: #E6F2F3 ;' : '' }} ">
                          {{ __('Properties') }}
                          </li>
                      </a>
                      <a  href="{{ route('dashboard', ['dash' => 'tickets']) }}" wire:navigate>
                          <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="{{ $dash == 'tickets' ? 'background-color: #E6F2F3 ;' : '' }} ">
                          {{ __('Maintenance Overview') }}
                          </li>
                      </a>
                  </ul>
                  @endcan

              </form>
            </div>
        </div>
    </div>
    <!-- Mobile Version of Dashboard Module End -->
    </div>
