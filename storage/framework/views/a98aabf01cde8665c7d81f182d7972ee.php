<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <select wire:model.live="period" id="" class="w-auto k-input fs-3">
                    <option value="0"><?php echo e(__('Select period')); ?></option>
                    <option value="1"><?php echo e(__('Today')); ?></option>
                    <option value="2"><?php echo e(__('Yesterday')); ?></option>
                    <option value="7"><?php echo e(__('Last 7 days')); ?></option>
                    <option value="30"><?php echo e(__('Last 30 days')); ?></option>
                    <option value="90"><?php echo e(__('Last 90 days')); ?></option>
                    <option value="180"><?php echo e(__('Last 180 days')); ?></option>
                    <option value="365"><?php echo e(__('Last 365 days')); ?></option>
                </select>
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select wire:model.live="type" id="" class="w-auto k-input fs-3">
                    <?php $__currentLoopData = $unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select wire:model.live="bookingSource" id="" class="w-auto k-input fs-3">
                    <option value=""><?php echo e(__('Source')); ?></option>
                    <option value="direct_booking"><?php echo e(__('Direct Booking')); ?></option>
                    <option value="ota"><?php echo e(__('Online Travel Agency')); ?></option>
                    <option value="website"><?php echo e(__('Website')); ?></option>
                </select>
            </div>

            <!-- Display panel buttons -->
            <div class="k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Button view -->
                <button wire:click="export" title="view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
                    <i class="fas fa-file-export"></i> <?php echo e(__('Export')); ?>

                </button>
                <!-- Button view -->
            </div>
        </div>
    </div>
    <!-- Controls Panel End -->


    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <div class="gap-2 mb-3 row">

                <!-- Reservation -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Bookings')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($bookings->count()); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="<?php echo e($bookingRateChange >= 0 ? 'text-green' : 'text-red'); ?> d-inline-flex align-items-center lh-1">
                            <?php echo e($bookingRateChange); ?>%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Reservation End -->

                <!-- Revenue -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Revenue')); ?></h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($revenue)); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="<?php echo e($revenueChange >= 0 ? 'text-green' : 'text-red'); ?> d-inline-flex align-items-center lh-1">
                        <?php echo e($revenueChange); ?>%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Revenue End -->

                <!-- Average Reservation -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Average Bookings')); ?></h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($avgRevenue)); ?></h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="<?php echo e($averageRevenueChange >= 0 ? 'text-green' : 'text-red'); ?> d-inline-flex align-items-center lh-1">
                        <?php echo e($averageRevenueChange); ?>%
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Average Reservation End -->

                <!-- Cancelation Rate -->
                <div class="p-2 rounded col-sm-12 col-lg-2 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Cancelation Rate')); ?></h3>
                    </div>
                    <div class="text-center">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($cancellationRate); ?>%</h3>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span class="<?php echo e($cancellationRate >= 0 ? 'text-green' : 'text-red'); ?> d-inline-flex align-items-center lh-1">
                        <?php echo e($cancellationRateChange); ?>% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                        </span>
                        <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Cancelation Rate End -->

            </div>

            <div class="gap-7 row">

                <!-- Monthly Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Monthly Bookings')); ?>

                        </div>
                    </div>
                    <div id="total-booking-chart" wire:ignore></div>
                </div>
                <!-- Monthly Bookings End -->

                <!-- Top Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Bookings')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Guest')); ?></th>
                                <th><?php echo e(__('Room')); ?></th>
                                <th><?php echo e(__('Agent')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($booking->guest->name); ?></td>
                                <td><?php echo e(__('Room')); ?> <?php echo e($booking->unit->name); ?></td>
                                <td><?php echo e($booking->agent->name); ?></td>
                                <td><?php echo e(__(format_currency($booking->total_amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>

                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Top Bookings End -->

                <!-- Top Canceled Bookings -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Canceled Bookings')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Guest')); ?></th>
                                <th><?php echo e(__('Room')); ?></th>
                                <th><?php echo e(__('Agent')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $canceledBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($booking->guest->name); ?></td>
                                <td><?php echo e(__('Room')); ?> <?php echo e($booking->unit->name); ?></td>
                                <td><?php echo e($booking->agent->name); ?></td>
                                <td><?php echo e(__(format_currency($booking->total_amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>

                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Top Canceled Bookings End -->

                <!-- Top Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Rooms')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Room')); ?></th>
                                <th><?php echo e(__('Booking')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>Room <?php echo e($room->name); ?></td>
                                <td><?php echo e($room->bookings_count); ?></td>
                                <td><?php echo e(__(format_currency($room->bookings_sum_total_amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Top Rooms End -->

                <!-- Top Guests -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Guests')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Guest')); ?></th>
                                <th><?php echo e(__('Bookings')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $guestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $guest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($guest->name); ?></td>
                                <td><?php echo e($guest->bookings_count); ?></td>
                                <td><?php echo e(format_currency($guest->bookings_sum_total_amount)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Top Guests End -->

                <!-- Top Room Type -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Room Type')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Type')); ?></th>
                                <th><?php echo e(__('Bookings')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($type['name']); ?></td>
                                <td><?php echo e($type['total_bookings']); ?></td>
                                <td><?php echo e(__(format_currency($type['total_revenue']))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- Top Room Type End -->

                <!-- Top Channels -->
                
                <!-- Top Channels End -->

            </div>

        </div>

    </div>
    <script>

        document.addEventListener('livewire:navigated', function () {
                const monthlyBookingsData = <?php echo json_encode($monthlyBookings, 15, 512) ?>;
                const labels = monthlyBookingsData.map(item => item.month); /*Month names for x-axis*/
                // const data = monthlyBookingsData.map(item => item.revenue); /* Revenue data for y-axis*/
                const bookings = monthlyBookingsData.map(item => item.revenue); /* Revenue data for y-axis*/
                const canceled = monthlyBookingsData.map(item => item.cancel); /* Revenue data for y-axis*/

                new ApexCharts(document.getElementById('total-booking-chart'), {
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
                            name: "Revenue",
                            data: bookings,
                        },
                        {
                            name: "Canceled Bookings",
                            data: canceled,
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
                            text: '<?php echo e(__('Revenue')); ?>', // Add y-axis label "Revenue"
                        },
                        labels: {
                            padding: 25
                        },
                    },
                    colors: ["#017E84", '#72374B'],
                    legend: {
                        show: true,
                    },
                }).render();
        });
    </script>
</div>

<?php /**PATH D:\My Laravel Startup\ndako\Modules\ChannelManager\resources\views\livewire\dashboards\reservation.blade.php ENDPATH**/ ?>