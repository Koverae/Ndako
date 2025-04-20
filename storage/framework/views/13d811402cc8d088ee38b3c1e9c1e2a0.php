<div>

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <?php if(session()->has('message')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="alert-body">
                    <span><?php echo e(session('message')); ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            <?php endif; ?>


            <div class="gap-2 mb-3 row" wire:poll.10s>

                <div class="bg-white empty k_nocontent_help h-100">
                    <img src="<?php echo e(asset('assets/images/illustrations/errors/503.svg')); ?>"style="height: 450px" alt="">
                    <p class="empty-title"><?php echo e(__('Welcome to Your Dashboard')); ?></p>
                    <p class="empty-subtitle"><?php echo e(__('Get a quick overview of your insights and reports.')); ?></p>
                </div>

                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'maintenance-staff')): ?>

                <!-- Total Open Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Total Open Tickets')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($ticketsThisDay); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Total Open Tickets End -->

                <!-- Tickets Assigned -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Tickets Assigned')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($ticketsAssigned); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Tickets Assigned End -->

                <!-- Ongoing Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Ongoing Tickets')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($ongoingTickets); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Ongoing Tickets End -->

                <!-- Closed Tickets -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Closed Tickets')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($ticketsClosed); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        0%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Closed Tickets End -->
                <?php endif; ?>

                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'front-desk')): ?>
                <!-- Occupancy Rate -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Occupancy Rate')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($occupancyRate); ?>%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        7% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Occupancy Rate End -->

                <!-- Room Nights Sold -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Room Nights Sold')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($occupiedNights); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        33% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Room Nights Sold End -->

                <!-- Occupied Rooms -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Occupied Rooms')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($occupiedRooms); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                        33% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Occupied Rooms End -->

                <!-- Available Room -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Room Nights Available')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($totalNightsAvailable); ?></h3>
                    </div>
                    </div>
                </div>
                <!-- Available Room End -->

                <!-- Check-ins Today -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
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
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
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

                <!-- Guest Today -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card pink">
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

                <!-- Available Rooms -->
                <div class="p-2 rounded col-sm-12 col-lg-4 k-dash-card">
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
                <?php endif; ?>

            </div>

            <!-- Maintenance Requests -->
            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'maintenance-staff')): ?>
            <div class="p-0 col-lg-12">
                <div class="shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2"><?php echo e(__('Active Requests')); ?> (<?php echo e($currentTickets->whereIn('status', ['pending', 'in_progress'])->count()); ?>)</h3>
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
                                <?php $__empty_1 = true; $__currentLoopData = $currentTickets->whereIn('status', ['pending', 'in_progress']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($request->title); ?></td>
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
                                            <?php if($request->status == 'in_progress'): ?>
                                            <span title="<?php echo e(__('Close the ticket')); ?>" wire:click="closeTicket('<?php echo e($request->id); ?>')" wire:confirm="Are you sure you want to close this ticket?">
                                                <i class="fas fa-times-circle fs-2" style="color: #FF0033;"></i>
                                            </span>
                                            <?php elseif($request->status == 'pending'): ?>
                                            <span title="<?php echo e(__('Open the ticket')); ?>" wire:click="openTicket('<?php echo e($request->id); ?>')" wire:confirm="Are you sure you want to open this ticket?">
                                                <i class="fas fa-envelope-open fs-2" style="color: #095c5e;"></i>
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center"><?php echo e(__('No active maintenance requests')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- Maintenance Requests End -->

            <!-- Guests Table -->
            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'front-desk')): ?>
            <div class="p-0 col-lg-12">
                <div class="border shadow-sm card">
                    <div class="card-header justify-content-between">
                        <div class="gap-3 d-flex">
                            <h3 class="h2">Guests (<?php echo e($bookings->count()); ?>)</h3>
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
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- Guests Table -->
        </div>

    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\Settings\resources\views\livewire\dashboards\home-dashboard.blade.php ENDPATH**/ ?>