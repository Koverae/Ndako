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
            </div>

            <!-- Display panel buttons -->
            <div class="gap-2 k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group text-end">

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

                <!-- Best Seller -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Best Seller') }}</h3>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0 h3" style="font-size: 40px;">{{ $bestSellerRoom['room_name'] ?? 'N/A' }}</h3><br>
                            <span class="text-muted">{{ $bestSellerRoom['total_nights'] ?? 0 }} {{ __('nights booked') }}</span>
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
                            <h3 class="mb-0 h3" style="font-size: 40px;">{{ $bestSellerType['type_name'] ?? 'N/A' }}</h3><br>
                            <span class="text-muted">{{ $bestSellerType['total_nights'] ?? 0 }} {{ __('nights booked') }}</span>
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
                    <div id="best-seller-rooms-chart" wire:ignore></div>

                </div>
                <!-- Best Seller By Revenue End -->

                <!-- Best Seller By Number of Bookings -->
                {{-- <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Best Seller By Number of Bookings') }}
                        </div>
                    </div>

                </div> --}}
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
    <script>
        document.addEventListener("livewire:navigated", function () {
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
</div>
