
<?php $__env->startSection('title', "Home"); ?>
<section class="m-2 mb-4">

    <!-- My To Dos -->
    <div class=" container-fluid">
        <div class="mb-2 d-flex justify-content-between g-2 ">
            <h2 class="page-title">
                My To Dos
            </h2>
            <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal'})" class="gap-2 text-end btn btn-primary"><?php echo e(__('Add')); ?> <i class="fas fa-plus-circle"></i></span>
        </div>
        <ul class="mb-1 nav nav-bordered">
            <li class="nav-item">
                <a class="nav-link active" id="my-task-tab" data-bs-toggle="tab" data-bs-target="#my-task" type="button" role="tab" aria-controls="my-task" aria-selected="true" ><b>My Tasks  (<?php echo e($tasks->count()); ?>)</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="my-situation-tab" data-bs-toggle="tab" data-bs-target="#my-situation" type="button" role="tab" aria-controls="my-situation" aria-selected="true"><b>My Situations (<?php echo e($situations->count()); ?>)</b></a>
            </li>
        </ul>
        <!-- App -->
        <div class="tab-content" id="nav-tabContent">
            <!-- Tasks -->
            <div class="mt-2 app_list tab-pane fade show active" id="my-task" role="tabpanel" aria-labelledby="my-task-tab">
                <div class="row">

                    <!-- Tasks -->
                    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                <?php echo e($task->title); ?>

                            </div>
                            <div class="mb-2 card-subtitle">
                                <span>Priority:  <b style="color: #095c5e;"><?php echo e($task->priority); ?></b></span>
                                <br>
                                <span class="text-black">Created By: <?php echo e($task->createdBy->name ?? 'Kwame Bot'); ?></span>
                            </div>
                            <span>Task created: <?php echo e(\Carbon\Carbon::parse($task->created_at)->diffForHumans()); ?></span>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e($task->description); ?>"></i></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="mt-1 rounded cursor-pointer col-md-12 col-12">
                            <div class="p-2 card">
                                <?php echo e(__("You don't have active tasks 😊")); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- Tasks End -->

                </div>
            </div>

            <!-- Situations -->
            <div class="mt-2 app_list tab-pane fade" id="my-situation" role="tabpanel" aria-labelledby="my-situation-tab">
                <div class="row">

                    <!-- Situations -->
                    <?php $__empty_1 = true; $__currentLoopData = $situations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $situation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mt-1 rounded cursor-pointer col-md-3 col-6">
                        <div class="p-2 card">
                            <div class="card-title">
                                <?php echo e($situation->title); ?>

                            </div>
                            <div class="mb-2 card-subtitle">
                                
                                <span class="text-black">Reported By: <?php echo e($situation->created_by ?? 'Kwame Bot'); ?></span>
                            </div>
                            <span>Details: <i class="bi bi-info-circle-fill k-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e($situation->description); ?>"></i></span>
                            <span>Situation created: <?php echo e(\Carbon\Carbon::parse($situation->created_at)->diffForHumans()); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="mt-1 rounded cursor-pointer col-md-12 col-12">
                            <div class="p-2 card">
                                <?php echo e(__("You don't have unresolve situations 😊")); ?>

                            </div>
                        </div>
                    <?php endif; ?>
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
                        <h2 class="h2"><?php echo e($guestsCurrentlyStaying); ?> <?php echo e(__('Guests this day')); ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="border shadow-sm card" style="border-radius: 0.5rem">
                    <div class="card-body">
                        <h2 class="h2"><?php echo e($checkoutsToday); ?> <?php echo e(__('Check-outs this day')); ?></h2>
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
                                    <th class="fs-5"><?php echo e(__('Name')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Room')); ?></th>
                                    <th class="fs-5" class="text-center"><?php echo e(__('Stay')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Day Left')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Outstanding Due')); ?></th>
                                    <th class="fs-5"><?php echo e(__('From')); ?></th>
                                    <th class="text-center fs-5"><?php echo e(__('Status')); ?></th>
                                    <th class="text-center fs-5"><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="cursor-pointer">
                                    <td>
                                        <img src="<?php echo e($booking->guest->avatar ? Storage::url('avatars/' . $booking->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>"
                                        class="rounded-circle img-thumbnail" width="40px" height="40px"
                                        alt="">
                                    </td>
                                    <td>
                                        <a href="#"><?php echo e($booking->guest->name); ?></a>
                                    </td>
                                    <td>
                                        <a href="#"><?php echo e(\Modules\Properties\Models\Property\PropertyUnit::find($booking->property_unit_id)->name); ?></a>
                                    </td>
                                    <td>
                                        <?php echo e(\Carbon\Carbon::parse($booking->check_in)->format('d M Y')); ?> ~ <?php echo e(\Carbon\Carbon::parse($booking->check_out)->format('d M Y')); ?>

                                    </td>
                                    <?php
                                        $date1 = \Carbon\Carbon::parse($booking->check_in);
                                        $date2 = \Carbon\Carbon::parse($booking->check_out);
                                        $daysDifference = $date1->diffInDays($date2);
                                    ?>
                                    <td>
                                        <?php echo e($daysDifference); ?> Day(s)
                                    </td>
                                    <td>
                                        <?php echo e(format_currency($booking->due_amount)); ?>

                                    </td>
                                    <td>
                                        <?php echo e($booking->source ?? __('Direct Booking')); ?>

                                    </td>
                                    <td>
                                        <?php if(\Carbon\Carbon::parse($booking->check_in)->isFuture()): ?>
                                            <span class="text-white badge bg-warning">Upcoming</span>
                                        <?php elseif(\Carbon\Carbon::parse($booking->check_out)->isFuture()): ?>
                                            <span class="text-white badge bg-success">In Progress</span>
                                        <?php else: ?>
                                            <span class="text-white badge bg-secondary">Completed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a class="text-decoration-none" title="<?php echo e(__('View Details')); ?>" wire:navigate href="<?php echo e(route('bookings.show', ['booking' => $booking->id])); ?>"><i class="fas fa-info-circle fs-2" style="color: #095c5e;"></i></a>
                                        <?php if(\Carbon\Carbon::parse($booking->check_out)->isFuture()): ?>
                                        <a class="text-decoration-none" title="<?php echo e(__('Check-Out')); ?>" wire:navigate><i class="fas fa-sign-out-alt fs-2" style="color: #095c5e;"></i></a>
                                        <?php elseif(\Carbon\Carbon::parse($booking->check_in)->isFuture()): ?>
                                        <a class="text-decoration-none" title="<?php echo e(__('Check-In')); ?>" wire:navigate><i class="fas fa-calendar-check fs-2" style="color: #095c5e;"></i></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        There's no data in this table
                                    </td>
                                </tr>
                                <?php endif; ?>
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
<?php /**PATH D:\My Laravel Startup\ndako\resources\views\livewire\dashboard.blade.php ENDPATH**/ ?>