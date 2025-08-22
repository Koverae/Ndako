<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />
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

                <!-- Total Registered Users -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Total Registered Users') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $users->count() ?? 0 }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $userChangeRate >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                            {{ $userChangeRate }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Total Registered Users End -->

                <!-- Total Active Users -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Total Active Users') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $activeUsers }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $activeUserChangeRate >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                            {{ $activeUserChangeRate }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Total Active Users End -->

                <!-- User Growth -->
                <div class="p-2 rounded col-sm-12 col-lg-2 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('User Growth') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $userChangeRate }}%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ 59 >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">

                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- User Growth End -->

                <!-- Total Companies -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Total Companies') }}</h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;">{{ $companies->count() }}</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ $companyChangeRate >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                            {{ $companyChangeRate }}%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Total Companies End -->

                <!-- Churn Rate -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3">{{ __('Churn Rate') }}</h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;">5.4%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="{{ 52 >= 0 ? 'text-green' : 'text-red' }} d-inline-flex align-items-center lh-1">
                            4%
                        </span>
                        <span class="text-end">{{ __('Since last period') }}</span>
                    </div>
                    </div>
                </div>
                <!-- Churn Rate End -->

            </div>

            <div class="gap-7 row">

                <!-- Monthly Active Users -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Monthly Active Users') }}
                        </div>
                    </div>
                    <div id="monthly-active-users" wire:ignore></div>
                </div>
                <!-- Monthly Active Users End -->

                <!-- Recent Signups -->
                <div class="p-0 k-dash-category col-md-12 col-lg-6">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Recent Signups') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Properties') }}</th>
                                <th>{{ __('Rooms') }}</th>
                                <th>{{ __('Users') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSignups as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->properties()->count()?? 0 }}</td>
                                <td>{{ $company->units()->count()?? 0 }}</td>
                                <td>{{ $company->users()->count()?? 0 }}</td>
                                <td>{{ \Carbon\Carbon::parse($company->created_at)->format('M j, Y') ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Recent Signups End -->

                <!-- Most Active Users -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            {{ __('Most Active Users') }}
                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Active Users N°') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topActiveUsers as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->active_user_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Most Active Users End -->

            </div>
        </div>

    </div>

    <script>

        document.addEventListener('livewire:navigated', function () {
                const monthlyActiveUsersData = @json($monthlyActiveUsers);
                const labels = monthlyActiveUsersData.map(item => item.month); /*Month names for x-axis*/
                const users = monthlyActiveUsersData.map(item => item.active_users); /* Revenue data for y-axis*/

                new ApexCharts(document.getElementById('monthly-active-users'), {
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
                            name: "Active Users",
                            data: users,
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
                            text: '{{ __('Active Users') }}', // Add y-axis label "Revenue"
                        },
                        labels: {
                            padding: 25
                        },
                    },
                    colors: ["#017E84"],
                    legend: {
                        show: true,
                    },
                }).render();
        });
    </script>


</div>
