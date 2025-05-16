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
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Agent') }}</option>
                    @foreach(current_company()->users() as $index => $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
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

                <!-- Invoiced -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Total Expenses') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;" title="{{ format_currency($spentAmount) }}">{{ format_currency($spentAmount) }}</h3>
                        <span class="text-muted">{{ format_currency($unpaidAmount) }} {{ __('unpaid') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Invoiced End -->

                <!-- Average Invoice -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Average Expense') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;" title="{{ format_currency($averageSpentAmount) }}">{{ format_currency($averageSpentAmount) }}</h3>
                            <span class="text-muted">{{ $numberOfExpenses }} {{ __('expense(s)') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Average Invoice End -->

                <!-- DSO -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Top Spending Category') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;" title="{{ __($bestCategory['category_name'] ?? '') }}">{{ __($bestCategory['category_name'] ?? '') }}</h3>
                        <span class="text-muted">{{ format_currency(($bestCategory['total_amount'] ?? 0)) }} {{ __('spent') }}</span>
                    </div>
                    </div>
                </div>
                <!-- DSO End -->

            </div>

            <div class="gap-7 row">

                <!-- Expense by Month -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Expense by Month') }}
                        </div>
                    </div>
                    <div id="monthly-expenses-chart" wire:ignore></div>

                </div>
                <!-- Expense by Month End -->

                <!-- Expense by Category -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Expense by Category') }}
                        </div>
                    </div>
                    <div id="bestExpenseCategoryChart"></div>
                </div>
                <!-- Expense by Category End -->

                <!-- Top Invoices -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Expenses') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Reference') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Agent') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $key => $expense)
                            <tr>
                                <td>{{ $expense->reference }}</td>
                                <td>{{ $expense->title }}</td>
                                <td>
                                    @if($expense->status == 'pending')
                                    {{ __('Pending') }}
                                    @elseif($expense->status == 'paid')
                                    {{ __('Paid') }}
                                    @endif
                                </td>
                                <td>{{ $expense->agent->name ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($expense->date)->format('m/d/y') }}</td>
                                <td>{{ __(format_currency($expense->amount)) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Invoices End -->

                <!-- Top Expense Categories -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Expense Categories') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Amount Spent') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenseCategories as $key => $category)
                            <tr>
                                <td>{{ $category['category_name'] }}</td>
                                <td>{{ __(format_currency($category['total_amount'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Expense Categories End -->

                <!-- Top Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-6">
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
                                <th>{{ __('Room Type') }}</th>
                                <th>{{ __('Amount Spent') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $key => $room)
                            <tr>
                                <td>{{ $room['room_name'] }}</td>
                                <td>{{ $room['room_type'] }}</td>
                                <td>{{ __(format_currency($room['total_amount'])) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Rooms End -->

            </div>

        </div>

    </div>

<script>

    document.addEventListener('livewire:navigated', function () {
            const months = @json($monthlyExpenses->pluck('month'));
            const spent = @json($monthlyExpenses->pluck('spent'));
            // const unpaidAmounts = @json($monthlyExpenses->pluck('unpaid')); /* Revenue data for y-axis*/

            new ApexCharts(document.getElementById('monthly-expenses-chart'), {
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
                        name: "{{ __('Amount Spent') }}",
                        data: spent,
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
                        text: '{{ __('Amount Spent') }}', // Add y-axis label "Revenue"
                    },
                    labels: {
                        padding: 25
                    },
                },
                colors: ["#017E84"],
                legend: {
                    show: false,
                },
            }).render();

            // Expense by Category
            const chartOptions = {
                chart: {
                    type: 'pie',
                    height: 350
                },
                labels: @json($expenseCategoryChartData['labels'] ?? []), // Prevent errors if empty
                series: @json($expenseCategoryChartData['series'] ?? []), // Prevent errors if empty
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
                const bestExpenseCategoryChart = new ApexCharts(document.querySelector('#bestExpenseCategoryChart'), chartOptions);
                bestExpenseCategoryChart.render();
            }
    });
    
</script>
</div>
