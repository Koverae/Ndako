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

              <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Reservations')); ?></b></header>
              <ul class="mb-4" style="margin-left: 10px;">
                <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($this->dash == 'reservations' ? 'background-color: #E6F2F3 ;' : ''); ?> " wire:click="changeDash('reservations')">
                  <?php echo e(__('Reservations')); ?>

                </li>
                <li class="w-auto p-2 rounded cursor-pointer kover-navlink <?php echo e($dash == 'properties' ? 'selected' : ''); ?> text-decoration-none panel-category" wire:click="changeDash('properties')">
                  <?php echo e(__('Rooms')); ?>

                </li>
              </ul>

              <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Revenue & Financials')); ?></b></header>
              <ul class="mb-4" style="margin-left: 10px;">
                <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($this->dash == 'invoicing' ? 'background-color: #E6F2F3 ;' : ''); ?> " wire:click="changeDash('invoicing')">
                  <?php echo e(__('Invoicing')); ?>

                </li>
              </ul>

              <header class="pt-3 form-label font-weight-bold text-uppercase"> <b><?php echo e(__('Properties')); ?></b></header>
              <ul class="mb-4" style="margin-left: 10px;">
                <li class="w-auto p-2 rounded cursor-pointer kover-navlink text-decoration-none panel-category" style="<?php echo e($this->dash == 'property' ? 'background-color: #E6F2F3 ;' : ''); ?> " wire:click="changeDash('property')">
                  <?php echo e(__('Properties')); ?>

                </li>
              </ul>

            </form>
          </div>
          <!-- Apps List -->
          <div class="p-3 overflow-y-auto bg-white col-12 col-md-12 col-lg-10" style="height: 100vh;">
            <!--[if BLOCK]><![endif]--><?php if($dash == 'reservations'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('channelmanager::dashboards.reservation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1133223436-0', $__slots ?? [], get_defined_vars());

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

$__html = app('livewire')->mount($__name, $__params, 'lw-1133223436-1', $__slots ?? [], get_defined_vars());

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

$__html = app('livewire')->mount($__name, $__params, 'lw-1133223436-2', $__slots ?? [], get_defined_vars());

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

$__html = app('livewire')->mount($__name, $__params, 'lw-1133223436-3', $__slots ?? [], get_defined_vars());

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
<?php /**PATH D:\My Laravel Startup\koverae-saas\resources\views/livewire/dashboards/overview.blade.php ENDPATH**/ ?>