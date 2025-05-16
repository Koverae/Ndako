<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />

                <select wire:model.live="property" id="" class="w-auto k-input fs-3">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Display panel buttons -->
<div class="k_cp_switch_buttons gap-2 d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Open Dashboard -->
                <a title="view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash" data-bs-toggle="offcanvas" href="#dashboardOffcanvas" role="button" aria-controls="offcanvasEnd">
                    <i class="fas fa-hand-point-right"></i> <?php echo e(__('Dashboards')); ?>

                </a>
                <!-- Open Dashboard -->

                <!-- Button view -->
                <button wire:click="export" title="export" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash">
                    <i class="fas fa-file-export"></i> <?php echo e(__('Export')); ?>

                </button>
                <!-- Button view -->
            </div>
        </div>
    </div>
    <!-- Controls Panel End -->

    <div class="overflow-hidden k-grid-overlay col-lg-12">
        <div class="container-xl">

            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition
                class="alert alert-success"
            >
                <?php echo e(session('message')); ?>

            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <?php if(session()->has('error')): ?>
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 3000)"
                    x-show="show"
                    x-transition
                    class="alert alert-danger"
                >
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <div class="gap-2 mb-3 row">

                <!-- Best Seller -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Best Seller')); ?></h3>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0 h3" style="font-size: 40px;"><?php echo e($bestSellerRoom['room_name']); ?></h3><br>
                            <span class="text-muted"><?php echo e($bestSellerRoom['total_nights']); ?> <?php echo e(__('nights booked')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Best Seller End -->

                <!-- Best Type -->
                <div class="p-2 rounded col-sm-12 col-lg-5 k-dash-card pink">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Best Type')); ?></h3>
                        </div>
                        <div class="text-center">
                            <h3 class="mb-0 h3" style="font-size: 40px;"><?php echo e($bestSellerType['type_name']); ?></h3><br>
                            <span class="text-muted"><?php echo e($bestSellerType['total_nights']); ?> <?php echo e(__('nights booked')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Best Type End -->

            </div>

            <div class="gap-7 row">

                <!-- Best Seller By Revenue -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Best Seller By Revenue')); ?>

                        </div>
                    </div>
                    <div id="best-seller-rooms-chart" wire:ignore></div>

                </div>
                <!-- Best Seller By Revenue End -->

                <!-- Best Seller By Number of Bookings -->
                
                <!-- Best Seller By Number of Bookings End -->

                <!-- Best Selling Rooms -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
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
                                <th><?php echo e(__('Nights Sold')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>Room <?php echo e($room['room_name']); ?></td>
                                <td><?php echo e($room['total_nights']); ?></td>
                                <td><?php echo e(__(format_currency($room['total_revenue']))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Best Selling Rooms End -->

                <!-- Best Selling Room Types -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
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
                                <th><?php echo e(__('Nights')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($type['type_name']); ?></td>
                                <td><?php echo e($type['total_nights']); ?></td>
                                <td><?php echo e(__(format_currency($type['total_revenue']))); ?></td>
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
        document.addEventListener("livewire:navigated", function () {
            const roomNames = <?php echo json_encode($bestSellerRooms->pluck('room_name'), 15, 512) ?>;
            const revenues = <?php echo json_encode($bestSellerRooms->pluck('revenue'), 15, 512) ?>;
                new ApexCharts(document.getElementById("best-seller-rooms-chart"), {
                    chart: {
                        type: "bar",
                        height: 350,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false,
                        },
                        animations: {
                            enabled: true
                        },
                    },
                    series: [{
                        name: "Revenue",
                        data: revenues,
                    }],
                    xaxis: {
                        categories: roomNames,
                    },
                    yaxis: {
                        title: {
                            text: 'Revenue',
                        }
                    },
                    title: {
                        text: "Best Seller Rooms (Last <?php echo e($period); ?> Days)",
                        align: 'center',
                    },
                    colors: ['#017E84'],
                    grid: {
                        show: true,
                        strokeDashArray: 4,
                    },
                }).render();
        });
    </script>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/ChannelManager\resources/views/livewire/dashboards/room.blade.php ENDPATH**/ ?>