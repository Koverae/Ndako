@section('title', "Dashboards")

    @section('styles')
        <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        body{
            overflow-x: hidden;
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
            @elseif($dash == 'property')
            <livewire:properties::dashboards.property />
            @endif
          </div>
        </div>
    </div>
