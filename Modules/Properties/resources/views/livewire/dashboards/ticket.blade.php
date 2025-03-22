<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-flex">
                <select wire:model.live="period" id="" class="w-auto k-input fs-3">
                    <option value="1">{{ __('Select period') }}</option>
                    <option value="1">{{ __('Today') }}</option>
                    <option value="2">{{ __('Yesterday') }}</option>
                    <option value="7">{{ __('Last 7 days') }}</option>
                    <option value="30">{{ __('Last 30 days') }}</option>
                    <option value="90">{{ __('Last 90 days') }}</option>
                    <option value="180">{{ __('Last 180 days') }}</option>
                    <option value="365">{{ __('Last 365 days') }}</option>
                </select>
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Agent') }}</option>
                    @foreach(current_company()->users() as $index => $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Display panel buttons -->
            <div class="k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Button view -->
                <button title=" view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
                    <i class="bi bi-share"></i> {{__('Share')}}
                </button>
                <!-- Button view -->
            </div>
        </div>
    </div>
    <!-- Controls Panel End -->

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <div class="gap-2 mb-3 row">

                <!-- Total Open Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Total Open Tickets') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $ticketsThisDay }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Total Open Tickets End -->

                <!-- Tickets Assigned -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Tickets Assigned') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $ticketsAssigned }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Tickets Assigned End -->

                <!-- Ongoing Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Ongoing Tickets') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $ongoingTickets }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Ongoing Tickets End -->

                <!-- Closed Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Closed Tickets') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $ticketsClosed }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Closed Tickets End -->


            </div>


            <div class="gap-7 row">

                <!-- Top Tickets Categories -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Tickets Categories') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Total Tickets') }}</th>
                                <th>{{ __('Closed Tickets') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ticketsByCategory as $category => $data)
                            <tr>
                                <td>{{ $category }}</td>
                                <td>{{ $data['total'] }}</td>
                                <td>{{ $data['closed'] }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <span>{{ __('No data available') }}</span>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Tickets Categories End -->

                <!-- Top Tickets -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Tickets') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Room') }}</th>
                                <th>{{ __('Ticket Category') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ticketsByRoom as $ticket)
                            <tr>
                                <td>{{ $ticket['room_name'] }}</td>
                                <td>{{ $ticket['category'] }}</td>
                                <td>{{ ucfirst($ticket['status']) }}</td>
                            </tr>
                            @empty
                                <tr>
                                    <span>{{ __('No data available') }}</span>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Tickets End -->

            </div>
        </div>
    </div>
</div>

