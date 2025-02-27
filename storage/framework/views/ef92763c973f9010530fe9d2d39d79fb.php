<div>

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span><?php echo e(session('message')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <div class="gap-2 mb-3 row" wire:poll.10s>

                <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('role', 'Maintenance Staff')): ?>
                <!-- Tasks Today -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Tasks Today')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($tasksThisDay); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Tasks Today End -->

                <!-- Tasks Assigned -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Tasks Assigned')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($tasksAssigned); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Tasks Assigned End -->

                <!-- Tasks Completed -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Tasks Completed')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($tasksCompleted); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Tasks Completed End -->

                <!-- Avg Completion Time -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Avg Completion Time')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($avgCompletionTime); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Avg Completion Time End -->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('role', 'Front Desk / Receptionist')): ?>
                <!-- Guest Today -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Guests Today')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($guestsCurrentlyStaying); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Guest Today End -->

                <!-- Check-ins Today -->
                <div class="p-2 rounded col-sm-12 col-lg-6 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Check-ins Today')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e($checkinsToday); ?></h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            </span>
                            <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Check-ins Today End -->

                <!-- Check-ins Today -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Check-ins Today"><?php echo e(__('Check-outs Today')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e($checkoutsToday); ?></h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            </span>
                            <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Check-ins Today End -->

                <!-- Available Rooms -->
                <div class="p-2 rounded col-sm-12 col-lg-6 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Available Rooms"><?php echo e(__('Available Rooms')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e($checkoutsToday); ?></h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            0%
                            
                            </span>
                            <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Available Rooms End -->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            </div>

            <!-- Maintenance Requests -->
            <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('role', 'Maintenance Staff')): ?>
            <div class="p-0 col-lg-12">
                <div class="shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2"><?php echo e(__('Active Maintenance Requests')); ?> (<?php echo e($tasks->count()); ?>)</h3>
                        </div>
                        <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal'})" class="gap-2 text-end btn btn-primary"><?php echo e(__('Add')); ?> <i class="fas fa-plus-circle"></i></span>
                    </div>
                    <div class="cursor-pointer table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr class="list-tr fs-4">
                                    <th class="fs-5"><?php echo e(__('Description')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Priority')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Category')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Room')); ?></th>
                                    <th class="fs-5"><?php echo e(__('Reported')); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($request->description); ?></td>
                                        <td><?php echo e(ucfirst($request->priority)); ?></td>
                                        <td><?php echo e($request->category); ?></td>
                                        <td>
                                            <a href="#"><?php echo e($request->room->name); ?></a>
                                        </td>
                                        <td><?php echo e($request->created_at->diffForHumans()); ?></td>
                                        <td>
                                            <span onclick="Livewire.dispatch('openModal', {component: 'settings::modal.add-work-item-modal', arguments: {task: <?php echo e($request->id); ?> }})">
                                                <i class="fas fa-info-circle fs-2" style="color: #095c5e;"></i>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center"><?php echo e(__('No active maintenance requests')); ?></td>
                                    </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!-- Maintenance Requests End -->

            <!-- Guests Table -->
            <!--[if BLOCK]><![endif]--><?php if (\Illuminate\Support\Facades\Blade::check('role', 'Front Desk / Receptionist')): ?>
            <div class="p-0 col-lg-12">
                <div class="border shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2">Current Guests (<?php echo e($bookings->count()); ?>)</h3>
                        </div>
                        <a  wire:navigate href="<?php echo e(route('bookings.create')); ?>" class="gap-2 text-end btn btn-primary"><?php echo e(__('Add')); ?> <i class="fas fa-plus-circle"></i></a>
                    </div>
                    <div class="cursor-pointer table-responsive">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr class="list-tr fs-4">
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
                                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                        <!--[if BLOCK]><![endif]--><?php if(\Carbon\Carbon::parse($booking->check_in)->isFuture()): ?>
                                            <span class="text-white badge bg-warning">Upcoming</span>
                                        <?php elseif(\Carbon\Carbon::parse($booking->check_out)->isFuture()): ?>
                                            <span class="text-white badge bg-success">In Progress</span>
                                        <?php else: ?>
                                            <span class="text-white badge bg-secondary">Completed</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td>
                                        <a class="text-decoration-none" title="<?php echo e(__('View Details')); ?>" wire:navigate href="<?php echo e(route('bookings.show', ['booking' => $booking->id])); ?>"><i class="fas fa-info-circle fs-2" style="color: #095c5e;"></i></a>
                                        <span onclick="Livewire.dispatch('openModal', {component: 'channelmanager::modal.guest-booking-modal', arguments: {booking: <?php echo e($booking->id); ?> }})">
                                            <i class="fas fa-user-cog fs-2" style="color: #095c5e;"></i>
                                        </span>
                                        
                                        
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <?php echo e(__('No active bookings.')); ?>

                                    </td>
                                </tr>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!-- Guests Table -->
        </div>

    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Settings\resources/views/livewire/dashboards/home-dashboard.blade.php ENDPATH**/ ?>