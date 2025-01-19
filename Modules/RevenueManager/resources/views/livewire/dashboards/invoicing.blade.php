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
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Room Type') }}</option>
                    @foreach($unitTypes as $index => $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value="">{{ __('Room') }}</option>
                    @foreach($units as $index => $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
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

                <!-- Invoiced -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Invoiced') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ format_currency($invoicedAmount) }}</h3>
                        <span class="text-muted">{{ format_currency($unpaidAmount) }} {{ __('unpaid') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Invoiced End -->

                <!-- Average Invoice -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3">{{ __('Average Invoice') }}</h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;">{{ format_currency($averageInvoiceAmount) }}</h3>
                            <span class="text-muted">{{ $numberOfInvoices }} {{ __('invoices') }}</span>
                        </div>
                    </div>
                </div>
                <!-- Average Invoice End -->

                <!-- DSO -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Days Sales Outstanding (DSO)') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">{{ $dso }} {{ __('days') }}</h3>
                        <span class="text-muted">{{ __('in current year') }}</span>
                    </div>
                    </div>
                </div>
                <!-- DSO End -->

            </div>

            <div class="gap-7 row">

                <!-- Invoiced by Month -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Invoiced by Month') }}
                        </div>
                    </div>
                    <div id="monthly-invoices-chart"></div>

                </div>
                <!-- Invoiced by Month End -->

                <!-- Top Invoices -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Top Invoices') }}
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
                            @forelse($invoices as $key => $invoice)
                            <tr>
                                <td>{{ $invoice->reference }}</td>
                                <td>{{ $invoice->guest->name }}</td>
                                <td>
                                    @if($invoice->payment_status == 'partial')
                                    {{ __('Partially Paid') }}
                                    @elseif($invoice == 'pending')
                                    {{ __('Not Paid') }}
                                    @elseif($invoice == 'paid')
                                    {{ __('Paid') }}
                                    @endif
                                </td>
                                <td>{{ $invoice->agent->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->date)->format('m/d/y') }}</td>
                                <td>{{ __(format_currency($invoice->total_amount)) }}</td>
                            </tr>
                            @empty
                            <tr></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Top Invoices End -->

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
                                <th>{{ __('Invoice') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $key => $payment)
                            <tr>
                                <td>{{ $payment->reference }}</td>
                                <td>{{ $payment->invoice->reference }}</td>
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

            </div>

        </div>

    </div>

<script>

    document.addEventListener('livewire:navigated', function () {
            const months = @json($mothlyInvoices->pluck('month'));
            const revenues = @json($mothlyInvoices->pluck('revenue'));
            const unpaidAmounts = @json($mothlyInvoices->pluck('unpaid')); /* Revenue data for y-axis*/

            new ApexCharts(document.getElementById('monthly-invoices-chart'), {
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
