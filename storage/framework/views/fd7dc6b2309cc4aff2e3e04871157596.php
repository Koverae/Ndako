<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <input type="date" wire:model.live="startDate" class="k-input fs-3" />
                <input type="date" wire:model.live="endDate" class="k-input fs-3" />
                <select wire:model.live="restaurant" id="" class="w-auto k-input fs-3">
                    <option value=""><?php echo e(__('Restaurant')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $restaurants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $restaurant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($restaurant->id); ?>"><?php echo e($restaurant->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value=""><?php echo e(__('Agent')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = current_company()->users(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($agent->id); ?>"><?php echo e($agent->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
            </div>

            <!-- Display panel buttons -->
            <div class="gap-2 k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group text-end">

                <!-- Open Dashboard -->
                <a title="view" class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list" id="share-dash" data-bs-toggle="offcanvas" href="#dashboardOffcanvas" role="button" aria-controls="offcanvasEnd">
                    <i class="fas fa-hand-point-right"></i> <?php echo e(__('Dashboards')); ?>

                </a>
                <!-- Open Dashboard -->

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

                <!-- Sales -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Sold')); ?></h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($soldAmount)); ?></h3>
                        <span class="text-muted"><?php echo e(format_currency($unpaidAmount)); ?> <?php echo e(__('unpaid')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Sales End -->

                <!-- Average Order -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Average Order')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($averageOrderAmount)); ?></h3>
                            <span class="text-muted"><?php echo e($numberOfOrders); ?> <?php echo e(__('Orders')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Average Order End -->

                <!-- DSO -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card pink">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Days Sales Outstanding (DSO)')); ?></h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e($dso); ?> <?php echo e(__('days')); ?></h3>
                        <span class="text-muted"><?php echo e(__('in current year')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- DSO End -->

            </div>

            <div class="gap-7 row">

                <!-- Sales by Month -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Sales by Month')); ?>

                        </div>
                    </div>
                    <div id="monthly-orders-chart" wire:ignore></div>

                </div>
                <!-- Sales by Month End -->

                <!-- Top Orders -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Orders')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Reference')); ?></th>
                                <th><?php echo e(__('Guest')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Agent')); ?></th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($order->reference); ?></td>
                                <td><?php echo e($order->guest->name ?? 'N/A'); ?></td>
                                <td>
                                    <!--[if BLOCK]><![endif]--><?php if($order->payment_status == 'partial'): ?>
                                    <?php echo e(__('Partially Paid')); ?>

                                    <?php elseif($order->payment_status == 'pending'): ?>
                                    <?php echo e(__('Not Paid')); ?>

                                    <?php elseif($order->payment_status == 'paid'): ?>
                                    <?php echo e(__('Paid')); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td><?php echo e($order->agent->name ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($order->date)->format('m/d/y')); ?></td>
                                <td><?php echo e(__(format_currency($order->total_amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Orders End -->

                <!-- Top Payments -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Payments')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Reference')); ?></th>
                                <th><?php echo e(__('Order')); ?></th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($payment->reference); ?></td>
                                <td><?php echo e($payment->order->reference ?? 'N/A'); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($payment->date)->format('m/d/y')); ?></td>
                                <td><?php echo e(__(format_currency($payment->amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Payments End -->

                <!-- Top Categories -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Categories')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Name')); ?></th>
                                <th><?php echo e(__('Qty')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bestCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($category['name']); ?></td>
                                <td><?php echo e($category['total_orders']); ?></td>
                                <td><?php echo e(__(format_currency($category['total_revenue']))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Categories End -->

                <!-- Top Products -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Products')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Product')); ?></th>
                                <th><?php echo e(__('Qty')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bestProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($product['name']); ?></td>
                                <td><?php echo e($product['total_orders']); ?></td>
                                <td><?php echo e(__(format_currency($product['total_revenue']))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Products End -->

                <!-- Top Sessions -->
                <div class="p-0 k-dash-category col-md-12 col-lg-5">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Sessions')); ?>

                        </div>
                    </div>
                    <table class="k-borderless-table">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Top Sessions')); ?></th>
                                <th><?php echo e(__('N° Orders')); ?></th>
                                <th><?php echo e(__('Revenue')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $bestProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($product['name']); ?></td>
                                <td><?php echo e($product['total_orders']); ?></td>
                                <td><?php echo e(__(format_currency($product['total_revenue']))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Sessions End -->

            </div>

        </div>

    </div>

<script>

    document.addEventListener('livewire:navigated', function () {
            const months = <?php echo json_encode($mothlyOrders->pluck('month'), 15, 512) ?>;
            const revenues = <?php echo json_encode($mothlyOrders->pluck('revenue'), 15, 512) ?>;
            const unpaidAmounts = <?php echo json_encode($mothlyOrders->pluck('unpaid'), 15, 512) ?>; /* Revenue data for y-axis*/

            new ApexCharts(document.getElementById('monthly-orders-chart'), {
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
                        columnWidth: '50%',
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
                        name: "<?php echo e(__('Revenue')); ?>",
                        data: revenues,
                    },

                    {
                        name: "<?php echo e(__('Unpaid Amount')); ?>",
                        data: unpaidAmounts,
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
                    categories: months, /*Month names as x-axis labels*/
                    // title: {
                    //     text: "<?php echo e(__('Months')); ?>",
                    // },
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
                    show: false,
                },
            }).render();
    });
</script>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/Pos\resources/views/livewire/dashboards/order.blade.php ENDPATH**/ ?>