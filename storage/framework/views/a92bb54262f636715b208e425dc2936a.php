<div>
    <!-- Controls Panel -->
    <div class="gap-3 px-3 mb-3 k_control_panel d-flex flex-column gap-lg-1">
        <div class="flex-wrap gap-5 k_control_panel_main d-flex justify-content-between align-items-lg-start flex-grow-1">
            <div class="flex-1 gap-3 d-none d-lg-flex">
                <select wire:model.live="period" id="" class="w-auto k-input fs-3">
                    <option value="0"><?php echo e(__('Select period')); ?></option>
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
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value=""><?php echo e(__('Room Type')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <select wire:model.live="" id="" class="w-auto k-input fs-3">
                    <option value=""><?php echo e(__('Room')); ?></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
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

                <!-- Invoiced -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h3 class="h3"><?php echo e(__('Invoiced')); ?></h3>
                    </div>
                    <div class="text-center text-truncate">
                        <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($invoicedAmount)); ?></h3>
                        <span class="text-muted"><?php echo e(format_currency($unpaidAmount)); ?> <?php echo e(__('unpaid')); ?></span>
                    </div>
                    </div>
                </div>
                <!-- Invoiced End -->

                <!-- Average Invoice -->
                <div class="p-2 rounded col-sm-12 col-lg-3 k-dash-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <h3 class="h3"><?php echo e(__('Average Invoice')); ?></h3>
                        </div>
                        <div class="text-center text-truncate">
                            <h3 class="h3" style="font-size: 40px;"><?php echo e(format_currency($averageInvoiceAmount)); ?></h3>
                            <span class="text-muted"><?php echo e($numberOfInvoices); ?> <?php echo e(__('invoices')); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Average Invoice End -->

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

                <!-- Invoiced by Month -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Invoiced by Month')); ?>

                        </div>
                    </div>
                    <div id="monthly-invoices-chart"></div>

                </div>
                <!-- Invoiced by Month End -->

                <!-- Top Invoices -->
                <div class="p-0 k-dash-category col-md-12 col-lg-12">
                    <!-- separator -->
                    <div class="g-col-sm-2">
                        <div class="m-0 mt-3 k_horizontal_separator text-uppercase fw-bolder small">
                            <?php echo e(__('Top Invoices')); ?>

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
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($invoice->reference); ?></td>
                                <td><?php echo e($invoice->guest->name); ?></td>
                                <td>
                                    <!--[if BLOCK]><![endif]--><?php if($invoice->payment_status == 'partial'): ?>
                                    <?php echo e(__('Partially Paid')); ?>

                                    <?php elseif($invoice == 'pending'): ?>
                                    <?php echo e(__('Not Paid')); ?>

                                    <?php elseif($invoice == 'paid'): ?>
                                    <?php echo e(__('Paid')); ?>

                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </td>
                                <td><?php echo e($invoice->agent->name); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($invoice->date)->format('m/d/y')); ?></td>
                                <td><?php echo e(__(format_currency($invoice->total_amount))); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr></tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <!-- Top Invoices End -->

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
                                <th><?php echo e(__('Invoice')); ?></th>
                                <th><?php echo e(__('Date')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($payment->reference); ?></td>
                                <td><?php echo e($payment->invoice->reference); ?></td>
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

            </div>

        </div>

    </div>

<script>

    document.addEventListener('livewire:navigated', function () {
            const months = <?php echo json_encode($mothlyInvoices->pluck('month'), 15, 512) ?>;
            const revenues = <?php echo json_encode($mothlyInvoices->pluck('revenue'), 15, 512) ?>;
            const unpaidAmounts = <?php echo json_encode($mothlyInvoices->pluck('unpaid'), 15, 512) ?>; /* Revenue data for y-axis*/

            new ApexCharts(document.getElementById('monthly-invoices-chart'), {
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
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/RevenueManager\resources/views/livewire/dashboards/invoicing.blade.php ENDPATH**/ ?>