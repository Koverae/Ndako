<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />
                <select wire:model.live="restaurant" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Restaurant') }}</option>
                    @foreach($restaurants as $index => $restaurant)
                    <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Agent') }}</option>
                    @foreach(current_company()->users() as $index => $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
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

                <!-- Sales -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Sold') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ format_currency($soldAmount) }}</h3>
                        <span class="text-muted">{{ format_currency($unpaidAmount) }} {{ __('unpaid') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Sales End -->

                <!-- Average Order -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Average Order') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ format_currency($averageOrderAmount) }}</h3>
                            <span class="text-muted">{{ $numberOfOrders }} {{ __('Orders') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Average Order End -->

                <!-- Best Product -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Best Product') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ $bestProduct['name'] }}</h3>
                        <span class="text-muted">{{ $bestProduct['total_orders'] ?? 0 }} {{ __('orders') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Best Product End -->

                <!-- Best Category -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Best Category') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ $bestCategory['name'] }}</h3>
                        <span class="text-muted">{{ $bestCategory['total_orders'] ?? 0 }} {{ __('orders') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Best Category End -->

            </div>

            <div class="gap-7 row">

                <!-- Sales by Month -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Sales by Month') }}
                        </div>
                    </div>
                    <div id="monthly-orders-chart" wire:ignore></div>

                </div>
                <!-- Sales by Month End -->

                <!-- Top Orders -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Orders') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Guest') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Agent') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $key => $order)
                            <tr>
                                <td>{{ $order->reference }}</td>
                                <td>{{ $order->guest->name ?? 'N/A' }}</td>
                                <td>
                                    @if($order->payment_status == 'partial')
                                    {{ __('Partially Paid') }}
                                    @elseif($order->payment_status == 'pending')
                                    {{ __('Not Paid') }}
                                    @elseif($order->payment_status == 'paid')
                                    {{ __('Paid') }}
                                    @endif
                                </td>
                                <td>{{ $order->agent->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->date)->format('m/d/y') }}</td>
                                <td>{{ __(format_currency($order->total_amount)) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Orders End -->

                <!-- Top Payments -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Payments') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Order') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $key => $payment)
                            <tr>
                                <td>{{ $payment->reference }}</td>
                                <td>{{ $payment->order->reference ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->date)->format('m/d/y') }}</td>
                                <td>{{ __(format_currency($payment->amount)) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Payments End -->

                <!-- Top Categories -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Categories') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestCategories as $key => $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td>{{ $category['total_orders'] }}</td>
                                <td>{{ __(format_currency($category['total_revenue'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Categories End -->

                <!-- Top Products -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Products') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestProducts as $key => $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['total_orders'] }}</td>
                                <td>{{ __(format_currency($product['total_revenue'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Products End -->

                <!-- Top Sessions -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Sessions') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Top Sessions') }}</th>
                                <th>{{ __('Closing Date') }}</th>
                                <th>{{ __('N° Orders') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestPosSessions as $key => $session)
                            <tr>
                                <td>{{ $session['reference'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($session['closing_date'])->format('m/d/y') ?? 'N/A' }}</td>
                                <td>{{ $session['total_orders'] }}</td>
                                <td>{{ __(format_currency($session['total_revenue'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Sessions End -->

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
                                <th>{{ __('Orders') }}</th>
                                <th>{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($guestOrders as $key => $guest)
                            <tr>
                                <td>{{ $guest->name }}</td>
                                <td>{{ $guest->orders_count }}</td>
                                <td>{{ format_currency($guest->orders_sum_total_amount) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Guests End -->

            </div>

        </div>

    </div>

<script>

    document.addEventListener('livewire:navigated', function () {
            const months = @json($mothlyOrders->pluck('month'));
            const revenues = @json($mothlyOrders->pluck('revenue'));
            const unpaidAmounts = @json($mothlyOrders->pluck('unpaid')); /* Revenue data for y-axis*/

            new ApexCharts(document.getElementById('monthly-orders-chart'), {
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
                        columnWidth: '50%',
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
                        name: "{{ __('Revenue') }}",
                        data: revenues,
                    },

                    {
                        name: "{{ __('Unpaid Amount') }}",
                        data: unpaidAmounts,
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
                    categories: months, /*Month names as x-axis labels*/
                    // title: {
                    //     text: "{{ __('Months') }}",
                    // },
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
                    show: false,
                },
            }).render();
    });
</script>
</div>
