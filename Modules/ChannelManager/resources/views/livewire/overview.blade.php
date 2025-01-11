@section('title', "Overview")

<!-- Control Panel -->
@section('control-panel')
<livewire:properties::navbar.control-panel.overview-panel />
@endsection
<!-- Page Content -->
<section class="m-2">

    <div class="row row-cards">
        <div class="col-12 col-lg-6">
            <div class="border shadow-sm card" style="border-radius: 0.5rem">
                <div class="card-body">
                    <h2 class="h2">43 Guests this day</h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="border shadow-sm card" style="border-radius: 0.5rem">
                <div class="card-body">
                    <h2 class="h2">12 Check-outs this day</h2>
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
                        </div>
                    </div>
                </div>
                <div class="p-0 card-body table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Room</th>
                                <th class="text-center">Stay</th>
                                <th>Day Left</th>
                                <th>Outstanding Due</th>
                                <th>From</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="cursor-pointer">
                                <td>
                                    <img src="{{asset('assets/images/avatar/avatar-2.jpg')}}"
                                        class="rounded-circle img-thumbnail" width="40px" height="40px"
                                        alt="">
                                </td>
                                <td>
                                    <a href="">Sam Altman</a>
                                </td>
                                <td>
                                    <a href="#">10A</a>
                                </td>
                                <td>
                                    18 Nov 2024 ~ 20 Nov 2024
                                </td>
                                <td>
                                    2 Days
                                </td>
                                <td>
                                    KSh. 32,500
                                </td>
                                <td>
                                    Airbnb
                                </td>
                                <td>
                                    <span
                                        class="text-white justify-content-center badge bg-success">
                                        In Progress
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="10" class="text-center">
                                    There's no data in this table
                                </td>
                            </tr>
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

</section>
<!-- Page Content -->
