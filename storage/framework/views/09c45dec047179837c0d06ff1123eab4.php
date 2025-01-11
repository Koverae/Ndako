
<?php $__env->startSection('title', "Home"); ?>
<section class="m-2 mb-4">

    <!-- My To Dos -->
    <div class=" container-fluid">
        <div class="mb-2 row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                My To Dos
                </h2>
            </div>
        </div>
        <ul class="mb-1 nav nav-bordered">
            <li class="nav-item">
                <a class="nav-link active" id="my-task-tab" data-bs-toggle="tab" data-bs-target="#my-task" type="button" role="tab" aria-controls="my-task" aria-selected="true" ><b>My Tasks (3)</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="my-situation-tab" data-bs-toggle="tab" data-bs-target="#my-situation" type="button" role="tab" aria-controls="my-situation" aria-selected="true"><b>My Situations (2)</b></a>
            </li>
        </ul>
        <!-- App -->
        <div class="tab-content" id="nav-tabContent">
            <!-- Tasks -->
            <div class="mt-2 app_list tab-pane fade show active" id="my-task" role="tabpanel" aria-labelledby="my-task-tab">
                <div class="row">
                    <!-- Tasks -->
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                Check-in Preparation for Booking #4721
                            </div>
                            <div class="mb-2 card-subtitle">
                                <span>Priority:  <b style="color: #095c5e;">Medium</b></span>
                                <br>
                                <span class="text-black">Created By: Achieng Onyango</span>
                            </div>
                            <span>Task created: 3 hours ago</span>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ensure room 203 is ready for Mr. Ochieng and provide details on nearby restaurants."></i></span>
                        </div>
                    </div>
                    <!-- Tasks End -->

                    <!-- Tasks -->
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                Follow-up on Late Check-out Request
                            </div>
                            <div class="mb-2 card-subtitle">
                                <span>Priority:  <b style="color: #095c5e;">Low</b></span>
                                <br>
                                <span class="text-black">Created By: Achieng Onyango</span>
                            </div>
                            <span>Task created: 1 hours ago</span>
                            <span>Details: <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Confirm with the guest in room 510 if they will require an extended stay."></i></span>
                        </div>
                    </div>
                    <!-- Tasks End -->

                    <!-- Tasks -->
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                Prepare Guest Welcome Package for VIP Clients
                            </div>
                            <div class="mb-2 card-subtitle">
                                <span>Priority:  <b style="color: #095c5e;">High</b></span>
                                <br>
                                <span class="text-black">Created By: Njambi Mwangi</span>
                            </div>
                            <span>Task created: 37 minutes ago</span>
                            <span>Details: <i class="bi bi-info-circle-fill" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Include a fruit basket, handwritten welcome note, and event calendar for their stay."></i></span>
                        </div>
                    </div>
                    <!-- Tasks End -->

                </div>
            </div>

            <!-- Situations -->
            <div class="mt-2 app_list tab-pane fade" id="my-situation" role="tabpanel" aria-labelledby="my-situation-tab">
                <div class="row">
                    <!-- Situations -->
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                Late Arrival Notification for Room 408
                            </div>
                            <span class="text-black">Reported By: Front Desk Agent, Mary Wambui</span>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Mr. Kiprono called to inform he will check in after midnight. Ensure his room key is ready, and provide a note with instructions for after-hours access."></i></span>
                            <div class="mt-2 mb-2 card-subtitle">
                                <span>Situation created: 1 hours ago</span>
                            </div>
                        </div>
                    </div>
                    <!-- Situations End -->

                    <!-- Situations -->
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                Overbooking Alert
                            </div>
                            <span class="text-black">Reported By: Front Desk Supervisor, Isaac Kinyua</span>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Two guests, Mr. Omondi and Ms. Wanjiru, have been booked for the same room. Check availability for alternatives and offer a complimentary upgrade to one guest."></i></span>
                            <div class="mt-2 mb-2 card-subtitle">
                                <span>Situation created: 2 hours ago</span>
                            </div>
                        </div>
                    </div>
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
                                        <img src="<?php echo e(asset('assets/images/avatar/avatar-2.jpg')); ?>"
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
                            
                        </div>
                        <div class="d-block">
                            <p class="text-muted">Connect your online platform. Match bookings automatically.</p>
                        </div>
                        <div class="mt-2 d-flex">
                            <div class="gap-2 k-gallery-box" id="channel-box">
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/airbnb.png')); ?>" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/bookingcom.jpg')); ?>" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/expedia.jpg')); ?>" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/tripadvisor.png')); ?>" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/agoda.png')); ?>" class="inline-flex rounded image">
                                </span>
                                <span class="inline-flex bg-gray-200 border rounded k-image-box">
                                    <img src="<?php echo e(asset('assets/images/third-icons/channels/hotelcom.png')); ?>" class="inline-flex rounded image">
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
    
    <!-- My Apps End -->

</section>
<?php /**PATH D:\My Laravel Startup\koverae-saas\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>