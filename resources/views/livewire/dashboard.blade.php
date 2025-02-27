
@section('title', "Home")
<section class="m-2 mb-4">

    <!-- My To Dos -->
    <div class=" container-fluid">
        <div class="mb-2 d-flex justify-content-between g-2 ">
            <h2 class="page-title">
                My To Dos
            </h2>
            <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal'})" class="gap-2 text-end btn btn-primary">{{ __('Add') }} <i class="fas fa-plus-circle"></i></span>
        </div>
        <ul class="mb-1 nav nav-bordered">
            <li class="nav-item">
                <a class="nav-link active" id="my-task-tab" data-bs-toggle="tab" data-bs-target="#my-task" type="button" role="tab" aria-controls="my-task" aria-selected="true" ><b>My Tasks  ({{ $tasks->count() }})</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="my-situation-tab" data-bs-toggle="tab" data-bs-target="#my-situation" type="button" role="tab" aria-controls="my-situation" aria-selected="true"><b>My Situations ({{ $situations->count() }})</b></a>
            </li>
        </ul>
        <!-- App -->
        <div class="tab-content" id="nav-tabContent">
            <!-- Tasks -->
            <div class="mt-2 app_list tab-pane fade show active" id="my-task" role="tabpanel" aria-labelledby="my-task-tab">
                <div class="row">

                    <!-- Tasks -->
                    @forelse ($tasks as $task)
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                {{ $task->title }}
                            </div>
                            <div class="mb-2 card-subtitle">
                                <span>Priority:  <b style="color: #095c5e;">{{ $task->priority }}</b></span>
                                <br>
                                <span class="text-black">Created By: {{ $task->createdBy->name ?? 'Kwame Bot' }}</span>
                            </div>
                            <span>Task created: {{ \Carbon\Carbon::parse($task->created_at)->diffForHumans() }}</span>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $task->description }}"></i></span>
                        </div>
                    </div>
                    @empty
                        <div class="mt-1 rounded cursor-pointer col-md-12 col-12">
                            <div class="p-2 card">
                                {{ __("You don't have active tasks 😊") }}
                            </div>
                        </div>
                    @endforelse
                    <!-- Tasks End -->

                </div>
            </div>

            <!-- Situations -->
            <div class="mt-2 app_list tab-pane fade" id="my-situation" role="tabpanel" aria-labelledby="my-situation-tab">
                <div class="row">

                    <!-- Situations -->
                    @forelse ($situations as $situation)
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                {{ $situation->title }}
                            </div>
                            <div class="mb-2 card-subtitle">
                                {{-- <span>Priority:  <b style="color: #095c5e;">{{ $situation->priority }}</b></span>
                                <br> --}}
                                <span class="text-black">Reported By: {{ $situation->created_by ?? 'Kwame Bot' }}</span>
                            </div>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $situation->description }}"></i></span>
                            <span>Situation created: {{ \Carbon\Carbon::parse($situation->created_at)->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                        <div class="mt-1 rounded cursor-pointer col-md-12 col-12">
                            <div class="p-2 card">
                                {{ __("You don't have unresolve situations 😊") }}
                            </div>
                        </div>
                    @endforelse
                    <!-- Situations End -->
                </div>
            </div>
            <!-- Situations End -->
        </div>

    </div>
    <!-- My To Dos End -->

    <!-- My Insights -->
    <div class="mb-4 container-fluid">
        <div class="mb-3 row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                My Insights
                </h2>
            </div>

        </div>
        <div class="row row-cards">
            <div class="col-12 col-lg-6">
                <div class="border shadow-sm card" style="border-radius: 0.5rem">
                    <div class="card-body">
                        <h2 class="h2">{{ $guestsCurrentlyStaying }} {{ __('Guests this day') }}</h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border shadow-sm card" style="border-radius: 0.5rem">
                    <div class="card-body">
                        <h2 class="h2">{{ $checkoutsToday }} {{ __('Check-outs this day') }}</h2>
                    </div>
                </div>
            </div>

            <!-- Guests Table -->
            <div class="col-lg-12">
                <div class="border shadow-sm card">
                    <div class="card-header">
                        <div class="row ">
                            <div class="col-lg-12 d-flex justify-content-between">
                                <div class="gap-3 d-flex">
                                    <h3 class="h2">Current Guests</h3>
                                    <a href="#" class="btn btn-tool btn-sm" style="height:25px;">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="#" class="btn btn-tool btn-sm" style="height:25px;">
                                        <i class="bi bi-menu-app"></i>
                                    </a>
                                </div>
                                {{-- <div class="text-end">
                                    <span wire:click='testNotif'>Test notifications</span>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="p-0 card-body table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
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
                                        @if(\Carbon\Carbon::parse($booking->check_out)->isFuture())
                                        <a class="text-decoration-none" title="{{ __('Check-Out') }}" wire:navigate><i class="fas fa-sign-out-alt fs-2" style="color: #095c5e;"></i></a>
                                        @elseif(\Carbon\Carbon::parse($booking->check_in)->isFuture())
                                        <a class="text-decoration-none" title="{{ __('Check-In') }}" wire:navigate><i class="fas fa-calendar-check fs-2" style="color: #095c5e;"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        There's no data in this table
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Guests Table -->

            <div class="col-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h2 class="mb-2 card-title h2">Channels</h2>
                            {{-- <div class="ms-auto">
                                <div class="dropdown">
                                <a class="text-muted" href="#" aria-expanded="false">Per Month</a>
                                </div>
                            </div> --}}
                        </div>
                        <div class="d-block">
                            <p class="text-muted">Connect your online platform. Match bookings automatically.</p>
                        </div>
                        <div class="mt-2 d-flex">
                            <div class="gap-2 k-gallery-box" id="channel-box">
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/airbnb.png') }}" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/bookingcom.jpg') }}" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/expedia.jpg') }}" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/tripadvisor.png') }}" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/agoda.png') }}" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="{{ asset('assets/images/third-icons/channels/hotelcom.png') }}" class="inline-flex rounded image">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservations by Channel -->
            <div class="col-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h2 class="card-title h2">Channel Performance</h2>
                            <div class="ms-auto">
                                <div class="dropdown">
                                    <a class=" text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">This Week</a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">This Week</a>
                                        <a class="dropdown-item" href="#">This Month</a>
                                        <a class="dropdown-item" href="#">3 Last Months</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div id="channel-performance-chart"></div>
                          </div>
                          <div class="col-md-auto">
                            <div class="divide-y divide-y-fill">
                              <div class="px-3">
                                <div class="text-secondary">
                                  <span class="status-dot bg-primary"></span> Expedia
                                </div>
                                <div class="h2">11,425</div>
                              </div>
                              <div class="px-3">
                                <div class="text-secondary">
                                  <span class="status-dot bg-azure"></span> Airbnb
                                </div>
                                <div class="h2">6,458</div>
                              </div>
                              <div class="px-3">
                                <div class="text-secondary">
                                  <span class="status-dot bg-green"></span> Website
                                </div>
                                <div class="h2">3,985</div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Reservations by Channel -->

            <!-- Total Reservations -->
            <div class="col-12 col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h2 class="card-title h2">Total Reservations Over Time</h2>
                            <div class="ms-auto">
                                <div class="dropdown">
                                    <a class=" text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">This Week</a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item active" href="#">This Week</a>
                                        <a class="dropdown-item" href="#">This Month</a>
                                        <a class="dropdown-item" href="#">3 Last Months</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="total-reservation-chart"></div>
                    </div>
                </div>
            </div>
            <!-- Total Reservations -->

        </div>
    </div>
    <!-- My Insights End -->

    <!-- My Apps -->
    {{-- <div class="mb-4 container-fluid">
        <div class="mb-2 row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                My Apps
                </h2>
            </div>
        </div>
        <ul class="mb-1 nav nav-bordered">
            <li class="nav-item">
                <a class="nav-link active" id="my-app-tab" data-bs-toggle="tab" data-bs-target="#my-app" type="button" role="tab" aria-controls="my-app" aria-selected="true" ><b>My Apps</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="recently-used-tab" data-bs-toggle="tab" data-bs-target="#recently-used" type="button" role="tab" aria-controls="recently-used" aria-selected="true"><b>Recently Used</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="my-favourite-tab" data-bs-toggle="tab" data-bs-target="#my-favourite" type="button" role="tab" aria-controls="my-favourite" aria-selected="true"><b>My Favourites</b></a>
            </li>
        </ul>
        <!-- App -->
        <div class="tab-content" id="nav-tabContent">
            <div class="p-3 mt-3 bg-white rounded shadow app_list tab-pane fade show active" id="my-app" role="tabpanel" aria-labelledby="my-app-tab">
                <div class="row">

                    <!-- App -->
                    @foreach(modules() as $module)
                        @if(module($module->slug))
                        <div class="pt-2 pb-2 mb-3 rounded cursor-pointer app kover-navlink col-6 col-lg-3" wire:click="openApp('{{ $module->id }}')">
                            <div class="gap-1 d-flex">
                                <a class="text-decoration-none">
                                    <img src="{{asset('assets/images/apps/'.$module->icon.'.png')}}" height="40px" width="40px" alt="" class="rounded app_icon">
                                </a>
                                <span class="text-decoration-none font-weight-bold">
                                    <span>{{ $module->short_name }}</span>
                                </span>
                            </div>
                        </div>
                        @endif
                    @endforeach
                    <!-- App End -->

                    <!-- App -->
                    <div class="pt-2 pb-2 mb-3 rounded cursor-pointer app kover-navlink col-6 col-lg-3">
                        <div class="gap-1 d-flex">
                            <a class="text-decoration-none" wire:click="openApp()">
                                <img src="{{asset('assets/images/apps/reservation.png')}}" height="40px" width="40px" alt="" class="rounded app_icon">
                            </a>
                            <a href="#" class="text-decoration-none font-weight-bold" wire:click.prevent="openApp()">
                                <span>Rooms Sys</span>
                            </a>
                        </div>
                    </div>
                    <!-- App End -->
                </div>
            </div>
            <div class="p-3 mt-3 bg-white rounded shadow tab-pane fade" id="recently-used" role="tabpanel" aria-labelledby="recently-used-tab">
                No applications have been used yet.
            </div>
            <div class="p-3 mt-3 bg-white rounded shadow tab-pane fade" id="my-favourite" role="tabpanel" aria-labelledby="my-favourite-tab">
                You don't have Favorites Apps.
            </div>
        </div>

    </div> --}}
    <!-- My Apps End -->

</section>
