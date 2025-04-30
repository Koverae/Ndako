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
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->name); ?></option>
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

            

            

        </div>

    </div>


</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/RevenueManager\resources/views/livewire/dashboards/expense.blade.php ENDPATH**/ ?>