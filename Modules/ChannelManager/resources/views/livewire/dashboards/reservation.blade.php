<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    @foreach($properties as $index => $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
                {{-- <select wire:model.live="type" id="" class="w-auto k-input fs-3">
                    @foreach($unitTypes as $index => $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select> --}}
                <select wire:model.live="bookingSource" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Source') }}</option>
                    <option value="direct_booking">{{ __('Direct Booking') }}</option>
                    <option value="ota">{{ __('Online Travel Agency') }}</option>
                    <option value="website">{{ __('Website') }}</option>
                </select>
            </div>

            <!-- Display panel buttons -->
            <div class="k_cp_switch_buttons gap-2 d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Open Dashboard -->
                <a title="view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash" data-bs-toggle="offcanvas" href="#dashboardOffcanvas" role="button" aria-controls="offcanvasEnd">
                    <i class="fas fa-hand-point-right"></i> {{__('Dashboards')}}
                </a>
                <!-- Open Dashboard -->

                <!-- Button view -->
                <button wire:click="export" title="export" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
                    <i class="fas fa-file-export"></i> {{__('Export')}}
                </button>
                <!-- Button view -->
            </div>

        </div>
    </div>
    <!-- Controls Panel End -->


    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            @if (session()->has('message'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="alert alert-success"
            >
                {{ session('message') }}
            </div>
            @endif
            
            @if (session()->has('error'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3000)"
                    x-show="show"
                    x-transition
                    class="alert alert-danger"
                >
                    {{ session('error') }}
                </div>
            @endif

            <div class="gap-2 mb-3 row">

                <!-- Reservation -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Bookings') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $bookings->count() }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $bookingRateChange >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                            {{ $bookingRateChange }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Reservation End -->

                <!-- Revenue -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Revenue') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ format_currency($revenue) }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $revenueChange >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                        {{ $revenueChange }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Revenue End -->

                <!-- Average Reservation -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Average Bookings') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ format_currency($avgRevenue) }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $averageRevenueChange >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                        {{ $averageRevenueChange }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Average Reservation End -->

                <!-- Cancelation Rate -->
                <div class="p-2 rounded col-sm-12 col-lg-2 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Cancelation Rate') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $cancellationRate }}%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $cancellationRate >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                        {{ $cancellationRateChange }}% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Cancelation Rate End -->

            </div>

            <div class="gap-7 row">

                <!-- Monthly Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Monthly Bookings') }}
                        </div>
                    </div>
                    <div id="total-booking-chart" wire:ignore></div>
                </div>
                <!-- Monthly Bookings End -->

                <!-- Top Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Bookings') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Guest') }}</th>
                                <th>{{ __('Room') }}</th>
                                <th>{{ __('Agent') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $key => $booking)
                            <tr>
                                <td>{{ $booking->guest->name }}</td>
                                <td>{{ __('Room') }} {{ $booking->unit->name }}</td>
                                <td>{{ $booking->agent->name ?? 'N/A' }}</td>
                                <td>{{ __(format_currency($booking->total_amount)) }}</td>
                            </tr>
                            @empty
                            <tr>
                                {{ __('No data available') }}
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Bookings End -->

                <!-- Top Canceled Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Canceled Bookings') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Guest') }}</th>
                                <th>{{ __('Room') }}</th>
                                <th>{{ __('Agent') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($canceledBookings as $key => $booking)
                            <tr>
                                <td>{{ $booking->guest->name }}</td>
                                <td>{{ __('Room') }} {{ $booking->unit->name }}</td>
                                <td>{{ $booking->agent->name ?? 'N/A' }}</td>
                                <td>{{ __(format_currency($booking->total_amount)) }}</td>
                            </tr>
                            @empty
                            <tr>

                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Canceled Bookings End -->

                <!-- Top Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Rooms') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Room') }}</th>
                                <th>{{ __('Booking') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $key => $room)
                            <tr>
                                <td>Room {{ $room->name }}</td>
                                <td>{{ $room->bookings_count }}</td>
                                <td>{{ __(format_currency($room->bookings_sum_total_amount)) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Rooms End -->

                <!-- Top Guests -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Guests') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Guest') }}</th>
                                <th>{{ __('Bookings') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guestBooks as $key => $guest)
                            <tr>
                                <td>{{ $guest->name }}</td>
                                <td>{{ $guest->bookings_count }}</td>
                                <td>{{ format_currency($guest->bookings_sum_total_amount) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Guests End -->

                <!-- Top Room Type -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Room Type') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Bookings') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roomTypes as $key => $type)
                            <tr>
                                <td>{{ $type['name'] }}</td>
                                <td>{{ $type['total_bookings'] }}</td>
                                <td>{{ __(format_currency($type['total_revenue'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Room Type End -->

                <!-- Top Channels -->
                {{-- <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Channels') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Channel') }}</th>
                                <th>{{ __('Bookings') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Direct Booking</td>
                                <td>234</td>
                                <td>{{ __(format_currency(134700)) }}</td>
                            </tr>
                            <tr>
                                <td>Website</td>
                                <td>74</td>
                                <td>{{ __(format_currency(34700)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> --}}
                <!-- Top Channels End -->

            </div>

        </div>

    </div>
    <script>

        document.addEventListener('livewire:navigated', function () {
                const monthlyBookingsData = @json($monthlyBookings);
                const labels = monthlyBookingsData.map(item => item.month); /*Month names for x-axis*/
                // const data = monthlyBookingsData.map(item => item.revenue); /* Revenue data for y-axis*/
                const bookings = monthlyBookingsData.map(item => item.revenue); /* Revenue data for y-axis*/
                const canceled = monthlyBookingsData.map(item => item.cancel); /* Revenue data for y-axis*/

                new ApexCharts(document.getElementById('total-booking-chart'), {
                    chart: {
                        type: "bar",
                        fontFamily: 'inherit',
                        height: 340,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false,
                        },
                        animations: {
                            enabled: true
                        },
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '40%',
                        }
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    fill: {
                        opacity: 1,
                    },
                    series: [
                        {
                            name: "Revenue",
                            data: bookings,
                        },
                        {
                            name: "Canceled Bookings",
                            data: canceled,
                        }
                    ],
                    tooltip: {
                        theme: 'dark'
                    },
                    grid: {
                        padding: {
                            top: -20,
                            right: 0,
                            left: -4,
                            bottom: -4
                        },
                        strokeDashArray: 4,
                    },
                    xaxis: {
                        labels: {
                            padding: 0,
                        },
                        tooltip: {
                            enabled: false
                        },
                        axisBorder: {
                            show: false,
                        },
                        type: 'category', /*Use 'category' for month labels on the x-axis*/
                        categories: labels, /*Month names as x-axis labels*/
                    },
                    yaxis: {
                        title: {
                            text: '{{ __('Revenue') }}', // Add y-axis label "Revenue"
                        },
                        labels: {
                            padding: 25
                        },
                    },
                    colors: ["#017E84", '#72374B'],
                    legend: {
                        show: true,
                    },
                }).render();
        });
    </script>
</div>

