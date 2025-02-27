<?php $__env->startSection('title', "Dashboards"); ?>

    <?php $__env->startSection('styles'); ?>
        <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        body{
            overflow-x: hidden;
        }
          body::-webkit-scrollbar {
              display: none;
          }

          /* Hide scrollbar for IE, Edge, and Firefox */
          body {
              -ms-overflow-style: none;  /* IE and Edge */
              scrollbar-width: none;  /* Firefox */
          }
        </style>
    <?php $__env->stopSection(); ?>

    <div class="p-0 container-fluid">
        <div class="row g-3">
            <!-- Side Bar -->
          <div class="flex-grow-0 flex-shrink-0 mb-5 overflow-auto bg-white border-left d-none d-lg-block col-md-2 app-sidebar bg-view position-relative pe-1 ps-3" style=" z-index: 500;">
            <form action="./" method="get" autocomplete="off" novalidate class="sticky-top">

                <!--[if BLOCK]><![endif]--><?php if(!Auth::user()->can('view_reports')): ?>
                <ul class="pt-3" style="margin-left: 10px;">
                    <a  href="<?php echo e(route('dashboard', ['dash' => 'home'])); ?>" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($dash == 'home' ? 'background-color: #E6F2F3 ;' : ''); ?> ">
                        <?php echo e(__('Home')); ?>

                        </li>
                    </a>
                </ul>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_reservation_reports')): ?>
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Reservations')); ?></b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    
                    <a  href="<?php echo e(route('dashboard', ['dash' => 'reservations'])); ?>" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($dash == 'reservations' ? 'background-color: #E6F2F3 ;' : ''); ?> ">
                        <?php echo e(__('Reservations')); ?>

                        </li>
                    </a>
                    <a  href="<?php echo e(route('dashboard', ['dash' => 'properties'])); ?>" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink <?php echo e($dash == 'properties' ? 'selected' : ''); ?> text-decoration-none panel-category">
                        <?php echo e(__('Rooms')); ?>

                        </li>
                    </a>
                </ul>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_financial_reports')): ?>
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Revenue & Financials')); ?></b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    <a  href="<?php echo e(route('dashboard', ['dash' => 'invoicing'])); ?>" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($dash == 'invoicing' ? 'background-color: #E6F2F3 ;' : ''); ?> ">
                                <?php echo e(__('Invoicing')); ?>

                        </li>
                    </a>
                </ul>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_property_reports')): ?>
                <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Properties')); ?></b></header>
                <ul class="mb-4" style="margin-left: 10px;">
                    <a  href="<?php echo e(route('dashboard', ['dash' => 'property'])); ?>" wire:navigate>
                        <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($dash == 'property' ? 'background-color: #E6F2F3 ;' : ''); ?> ">
                        <?php echo e(__('Properties')); ?>

                        </li>
                    </a>
                </ul>
                <?php endif; ?>

            </form>
          </div>
          <!-- Apps List -->
          <div class="p-3 overflow-y-auto bg-white col-12 col-md-12 col-lg-10" style="height: 100vh;">
            <!--[if BLOCK]><![endif]--><?php if($dash == 'home'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings::dashboards.home-dashboard', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-221140226-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php elseif($dash == 'reservations'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::dashboards.reservation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-221140226-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php elseif($dash == 'properties'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::dashboards.room', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-221140226-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php elseif($dash == 'invoicing'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('revenuemanager::dashboards.invoicing', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-221140226-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php elseif($dash == 'property'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('properties::dashboards.property', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-221140226-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
          </div>
        </div>
    </div>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/livewire/dashboards/overview.blade.php ENDPATH**/ ?>