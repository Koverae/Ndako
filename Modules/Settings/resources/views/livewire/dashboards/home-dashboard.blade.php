<div>

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            @if (session()->has('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span>{{ session('message') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif


            <div class="gap-2 mb-3 row" wire:poll.10s>

                <div class="bg-white empty k_nocontent_help h-100">
                    <img src="{{ asset('assets/images/illustrations/errors/503.svg') }}"style="height: 450px" alt="">
                    <p class="empty-title">{{ __('Welcome to Your Dashboard') }}</p>
                    <p class="empty-subtitle">{{ __('Get a quick overview of your insights and reports.') }}</p>
                </div>

                @role('maintenance-staff')

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
                @endrole

                @role('front-desk')
                <!-- Occupancy Rate -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Occupancy Rate') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $occupancyRate }}%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        7% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Occupancy Rate End -->

                <!-- Room Nights Sold -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Room Nights Sold') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $occupiedNights }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        33% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Room Nights Sold End -->

                <!-- Occupied Rooms -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Occupied Rooms') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $occupiedRooms }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        33% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Occupied Rooms End -->

                <!-- Available Room -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Room Nights Available') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $totalNightsAvailable }}</h3>
                    </div>
                    </div>
                </div>
                <!-- Available Room End -->

                <!-- Check-ins Today -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Check-ins Today') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ $checkinsToday }}</h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            </span>
                            <span class="text-end">{{ __('Since last period') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Check-ins Today End -->

                <!-- Check-ins Today -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Check-ins Today">{{ __('Check-outs Today') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ $checkoutsToday }}</h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            </span>
                            <span class="text-end">{{ __('Since last period') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Check-ins Today End -->

                <!-- Guest Today -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Guests Today') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $guestsCurrentlyStaying }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Guest Today End -->

                <!-- Available Rooms -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Available Rooms">{{ __('Available Rooms') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ $checkoutsToday }}</h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg> --}}
                            </span>
                            <span class="text-end">{{ __('Since last period') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Available Rooms End -->
                @endrole

            </div>

            <!-- Maintenance Requests -->
            @role('maintenance-staff')
            <div class="p-0 col-lg-12">
                <div class="shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2">{{ __('Active Requests') }} ({{ $currentTickets->whereIn('status', ['pending', 'in_progress'])->count() }})</h3>
                        </div>
                        <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal'})" class="gap-2 text-end btn btn-primary">{{ __('Add') }} <i class="fas fa-plus-circle"></i></span>
                    </div>
                    <div class="cursor-pointer table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr class="list-tr fs-4">
                                    <th class="fs-5">{{ __('Description') }}</th>
                                    <th class="fs-5">{{ __('Priority') }}</th>
                                    <th class="fs-5">{{ __('Category') }}</th>
                                    <th class="fs-5">{{ __('Room') }}</th>
                                    <th class="fs-5">{{ __('Reported') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($currentTickets->whereIn('status', ['pending', 'in_progress']) as $request)
                                    <tr>
                                        <td>{{ $request->title }}</td>
                                        <td>{{ ucfirst($request->priority) }}</td>
                                        <td>{{ $request->category }}</td>
                                        <td>
                                            <a href="#">{{ $request->room->name }}</a>
                                        </td>
                                        <td>{{ $request->created_at->diffForHumans() }}</td>
                                        <td>
                                            <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal', arguments: {task: {{ $request->id }} }})">
                                                <i class="fas fa-info-circle fs-2" style="color: #095c5e;"></i>
                                            </span>
                                            @if($request->status == 'in_progress')
                                            <span title="{{__('Close the ticket')}}" wire:click="closeTicket('{{ $request->id }}')" wire:confirm="Are you sure you want to close this ticket?">
                                                <i class="fas fa-times-circle fs-2" style="color: #FF0033;"></i>
                                            </span>
                                            @elseif($request->status == 'pending')
                                            <span title="{{__('Open the ticket')}}" wire:click="openTicket('{{ $request->id }}')" wire:confirm="Are you sure you want to open this ticket?">
                                                <i class="fas fa-envelope-open fs-2" style="color: #095c5e;"></i>
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No active maintenance requests') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endrole
            <!-- Maintenance Requests End -->

            <!-- Guests Table -->
            @role('front-desk')
            <div class="p-0 col-lg-12">
                <div class="border shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2">Guests ({{ $bookings->count() }})</h3>
                        </div>
                        <a  wire:navigate href="{{ route('bookings.create') }}" class="gap-2 text-end btn btn-primary">{{ __('Add') }} <i class="fas fa-plus-circle"></i></a>
                    </div>
                    <div class="cursor-pointer table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr class="list-tr fs-4">
                                    <th></th>
                                    <th class="fs-5">{{ __('Name') }}</th>
                                    <th class="fs-5">{{ __('Room') }}</th>
                                    <th class="fs-5" class="text-center">{{ __('Stay') }}</th>
                                    <th class="fs-5">{{ __('Day Left') }}</th>
                                    <th class="fs-5">{{ __('Outstanding Due') }}</th>
                                    <th class="fs-5">{{ __('From') }}</th>
                                    <th class="text-center fs-5">{{ __('Status') }}</th>
                                    <th class="text-center fs-5">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $index => $booking)
                                <tr class="cursor-pointer">
                                    <td>
                                        <img src="{{ $booking->guest->avatar ? Storage::url('avatars/' . $booking->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png') }}"
                                        class="rounded-circle img-thumbnail" width="40px" height="40px"
                                        alt="">
                                    </td>
                                    <td>
                                        <a href="#">{{ $booking->guest->name }}</a>
                                    </td>
                                    <td>
                                        <a href="#">{{ \Modules\Properties\Models\Property\PropertyUnit::find($booking->property_unit_id)->name }}</a>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }} ~ {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                    </td>
                                    @php
                                        $date1 = \Carbon\Carbon::parse($booking->check_in);
                                        $date2 = \Carbon\Carbon::parse($booking->check_out);
                                        $daysDifference = $date1->diffInDays($date2);
                                    @endphp
                                    <td>
                                        {{ $daysDifference }} Day(s)
                                    </td>
                                    <td>
                                        {{ format_currency($booking->due_amount) }}
                                    </td>
                                    <td>
                                        {{ $booking->source ?? __('Direct Booking') }}
                                    </td>
                                    <td>
                                        @if (\Carbon\Carbon::parse($booking->check_in)->isFuture())
                                            <span class="text-white badge bg-warning">Upcoming</span>
                                        @elseif (\Carbon\Carbon::parse($booking->check_out)->isFuture())
                                            <span class="text-white badge bg-success">In Progress</span>
                                        @else
                                            <span class="text-white badge bg-secondary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="text-decoration-none" title="{{ __('View Details') }}" wire:navigate href="{{ route('bookings.show', ['booking' => $booking->id]) }}"><i class="fas fa-info-circle fs-2" style="color: #095c5e;"></i></a>
                                        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: {{ $booking->id }} }})">
                                            <i class="fas fa-user-cog fs-2" style="color: #095c5e;"></i>
                                        </span>

                                        {{-- @if(\Carbon\Carbon::parse($booking->check_out)->isFuture())
                                        <a class="text-decoration-none" title="{{ __('Check-Out') }}" wire:navigate><i class="fas fa-sign-out-alt fs-2" style="color: #095c5e;"></i></a>
                                        @elseif(\Carbon\Carbon::parse($booking->check_in)->isFuture())
                                        <a class="text-decoration-none" title="{{ __('Check-In') }}" wire:navigate><i class="fas fa-calendar-check fs-2" style="color: #095c5e;"></i></a>
                                        @endif --}}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        {{ __('No active bookings.') }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endrole
            <!-- Guests Table -->
        </div>

    </div>
</div>
