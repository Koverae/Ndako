<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <select wire:model.live="period" id="" class="w-auto k-input fs-3">
                    <option value="0">{{ __('Select period') }}</option>
                    <option value="7">{{ __('Last 7 days') }}</option>
                    <option value="30">{{ __('Last 30 days') }}</option>
                    <option value="90">{{ __('Last 90 days') }}</option>
                    <option value="180">{{ __('Last 180 days') }}</option>
                    <option value="365">{{ __('Last 365 days') }}</option>
                </select>
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    <option value="null">{{ __('Property') }}</option>
                    @foreach($properties as $index => $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
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

                <!-- Best Seller -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Best Seller') }}</h3>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0 h3" style="font-size: 40px;">{{ __('Room') }} {{ $bestSellerRoom['room_name'] }}</h3><br>
                            <span class="text-muted">{{ $bestSellerRoom['total_nights'] }} {{ __('nights booked') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Best Seller End -->

                <!-- Best Type -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Best Type') }}</h3>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0 h3" style="font-size: 40px;">{{ $bestSellerType['type_name'] }}</h3><br>
                            <span class="text-muted">{{ $bestSellerType['total_nights'] }} {{ __('nights booked') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Best Type End -->

            </div>

            <div class="gap-7 row">

                <!-- Best Seller By Revenue -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Best Seller By Revenue') }}
                        </div>
                    </div>
                    <div id="best-seller-rooms-chart"></div>

                </div>
                <!-- Best Seller By Revenue End -->

                <!-- Best Seller By Number of Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Best Seller By Number of Bookings') }}
                        </div>
                    </div>

                </div>
                <!-- Best Seller By Number of Bookings End -->

                <!-- Best Selling Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
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
                                <th>{{ __('Nights Sold') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $key => $room)
                            <tr>
                                <td>Room {{ $room['room_name'] }}</td>
                                <td>{{ $room['total_nights'] }}</td>
                                <td>{{ __(format_currency($room['total_revenue'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Rooms End -->

                <!-- Best Selling Room Types -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
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
                                <th>{{ __('Nights') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roomTypes as $key => $type)
                            <tr>
                                <td>{{ $type['type_name'] }}</td>
                                <td>{{ $type['total_nights'] }}</td>
                                <td>{{ __(format_currency($type['total_revenue'])) }}</td>
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
    @section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const roomNames = @json($bestSellerRooms->pluck('room_name'));
            const revenues = @json($bestSellerRooms->pluck('revenue'));
                new ApexCharts(document.getElementById("best-seller-rooms-chart"), {
                    chart: {
                        type: "bar",
                        height: 350,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false,
                        },
                        animations: {
                            enabled: true
                        },
                    },
                    series: [{
                        name: "Revenue",
                        data: revenues,
                    }],
                    xaxis: {
                        categories: roomNames,
                    },
                    yaxis: {
                        title: {
                            text: 'Revenue',
                        }
                    },
                    title: {
                        text: "Best Seller Rooms (Last {{ $period }} Days)",
                        align: 'center',
                    },
                    colors: ['#017E84'],
                    grid: {
                        show: true,
                        strokeDashArray: 4,
                    },
                }).render();
        });
    </script>
    @endsection
</div>
