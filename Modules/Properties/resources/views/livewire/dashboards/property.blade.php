<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    @foreach($properties as $index => $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
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
                <button wire:click="export" title="view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
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
                        0% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Occupancy Rate End -->

                <!-- Average Daily Rate (ADR) -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Average Daily Rate (ADR)') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ format_currency($adr) }}</h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                            </span>
                            <span class="text-end">{{ __('Since last period') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Average Daily Rate (ADR) End -->

                <!-- Revenue Per Available Room (RevPAR) -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Revenue Per Available Room (RevPAR)">{{ __('RevPAR') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ format_currency($revPar) }}</h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                            </span>
                            <span class="text-end">{{ __('Since last period') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Revenue Per Available Room (RevPAR) End -->

                <!-- Room Nights Sold -->
                <div class="p-2 rounded col-sm-12 col-lg-2 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Room Nights Sold') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $occupiedNights }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
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
                        0% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
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

            </div>

            <div class="gap-7 row">

                <!-- Monthly Occupancy Rates -->
                {{-- <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Monthly Occupancy Rates') }}
                        </div>
                    </div>
                    <!-- separator -->

                </div> --}}
                <!-- Monthly Occupancy Rates End -->

                <!-- Revenue by Room Type -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Revenue by Room Type') }}
                        </div>
                    </div>
                    <div id="bestRoomTypeChart"></div>
                </div>
                <!-- Revenue by Room Type End -->

                <!-- Best Selling Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Best Selling Rooms') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Room') }}</th>
                                <th>{{ __('Room Type') }}</th>
                                <th>{{ __('Nights Sold') }}</th>
                                <th>{{ __('Lost Nights') }}</th>
                                <th>{{ __('Occupancy Rate') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellingRooms as $key => $room)
                            <tr>
                                <td>{{ __('Room') }} {{ $room['room'] }}</td>
                                <td>{{ $room['room_type'] }}</td>
                                <td>{{ $room['nights_sold'] }}</td>
                                <td>{{ $room['nights_canceled'] }}</td>
                                <td>{{ $room['occupancy_rate'] }}</td>
                                <td>{{ $room['revenue'] }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Rooms End -->

                <!-- Best Selling Room Types -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Best Selling Room Types') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Room Type') }}</th>
                                <th>{{ __('Nights Sold') }}</th>
                                <th>{{ __('Lost Nights') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellingRoomTypes as $key => $type)
                            <tr>
                                <td>{{ $type['room_type'] }}</td>
                                <td>{{ $type['nights_sold'] }}</td>
                                <td>{{ $type['nights_canceled'] }}</td>
                                <td>{{ $type['revenue'] }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Room Types End -->

            </div>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: @json($roomTypeChartData['labels'] ?? []), // Prevent errors if empty
                series: @json($roomTypeChartData['series'] ?? []), // Prevent errors if empty
                colors: [
                    '#017E84', '#72374B', '#FEB019', '#FF4560', '#775DD0',
                    '#00E396', '#008FFB', '#D7263D', '#F86624', '#A633FF',
                    '#66DA26', '#E91E63', '#2B908F', '#F9A3A4', '#D9A404'
                ], // Adjust colors as needed
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            if (chartOptions.series.length > 0) {
                const bestRoomTypeChart = new ApexCharts(document.querySelector('#bestRoomTypeChart'), chartOptions);
                bestRoomTypeChart.render();
            }
        });
    </script>

</div>
