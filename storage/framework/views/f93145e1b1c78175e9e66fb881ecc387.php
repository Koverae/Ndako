<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-flex">
                <select wire:model.live="period" id="" class="w-auto k-input fs-3">
                    <option value="1"><?php echo e(__('Select period')); ?></option>
                    <option value="7"><?php echo e(__('Last 7 days')); ?></option>
                    <option value="30"><?php echo e(__('Last 30 days')); ?></option>
                    <option value="90"><?php echo e(__('Last 90 days')); ?></option>
                    <option value="180"><?php echo e(__('Last 180 days')); ?></option>
                    <option value="365"><?php echo e(__('Last 365 days')); ?></option>
                </select>
                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    <option value="null"><?php echo e(__('Property')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Display panel buttons -->
            <div class="k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Button view -->
                <button title=" view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
                    <i class="bi bi-share"></i> <?php echo e(__('Share')); ?>

                </button>
                <!-- Button view -->
            </div>
        </div>
    </div>
    <!-- Controls Panel End -->

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <div class="gap-2 mb-3 row">

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

                <!-- Average Daily Rate (ADR) -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Average Daily Rate (ADR)')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($adr)); ?></h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            19% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                            </span>
                            <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Average Daily Rate (ADR) End -->

                <!-- Revenue Per Available Room (RevPAR) -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3" title="Revenue Per Available Room (RevPAR)"><?php echo e(__('RevPAR')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($revPar)); ?></h3>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-green d-inline-flex align-items-center lh-1">
                            11% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="3 17 9 11 13 15 21 7" /><polyline points="14 7 21 7 21 14" /></svg>
                            </span>
                            <span class="text-end"><?php echo e(__('Since last period')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Revenue Per Available Room (RevPAR) End -->

                <!-- Room Nights Sold -->
                <div class="p-2 rounded col-sm-12 col-lg-2 k-dash-card">
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

            </div>

            <div class="gap-7 row">

                <!-- Monthly Occupancy Rates -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Monthly Occupancy Rates')); ?>

                        </div>
                    </div>
                    <!-- separator -->

                </div>
                <!-- Monthly Occupancy Rates End -->

                <!-- Revenue by Room Type -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Revenue by Room Type')); ?>

                        </div>
                    </div>
                    <div id="bestRoomTypeChart"></div>
                </div>
                <!-- Revenue by Room Type End -->

                <!-- Best Selling Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Best Selling Rooms')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Room')); ?></th>
                                <th><?php echo e(__('Room Type')); ?></th>
                                <th><?php echo e(__('Nights Sold')); ?></th>
                                <th><?php echo e(__('Occupancy Rate')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bestSellingRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(__('Room')); ?> <?php echo e($room['room']); ?></td>
                                <td><?php echo e($room['room_type']); ?></td>
                                <td><?php echo e($room['nights_sold']); ?></td>
                                <td><?php echo e($room['occupancy_rate']); ?></td>
                                <td><?php echo e($room['revenue']); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Rooms End -->

                <!-- Best Selling Room Types -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Best Selling Room Types')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Room Type')); ?></th>
                                <th><?php echo e(__('Nights Sold')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bestSellingRoomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($type['room_type']); ?></td>
                                <td><?php echo e($type['nights_sold']); ?></td>
                                <td><?php echo e($type['revenue']); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Room Types End -->

            </div>

        </div>

    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartOptions = {
            chart: {
                type: 'pie',
                height: 350
            },
            labels: <?php echo json_encode($roomTypeChartData['labels'], 15, 512) ?>,
            series: <?php echo json_encode($roomTypeChartData['series'], 15, 512) ?>,
            colors: ['#017E84', '#72374B', '#FEB019', '#FF4560', '#775DD0'], // Adjust colors as needed
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

        const bestRoomTypeChart = new ApexCharts(document.querySelector('#bestRoomTypeChart'), chartOptions);
        bestRoomTypeChart.render();
    });
</script>
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/Properties\resources/views/livewire/dashboards/property.blade.php ENDPATH**/ ?>