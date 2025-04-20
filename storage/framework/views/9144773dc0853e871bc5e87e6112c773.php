<div>
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

        <li class="nav-item">
            <a class="nav-link kover-navlink dropdown" wire:navigate href="<?php echo e(route('channels.index')); ?>" style="margin-right: 5px;">
              <span class="nav-link-title">
                  <?php echo e(__('Overview')); ?>

              </span>
            </a>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Channels')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('channels.lists')); ?>">
                            <?php echo e(__('Manage Channel')); ?>

                        </a>
                        

                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Properties')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.lists')); ?>">
                            <?php echo e(__('Properties')); ?>

                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.units.lists')); ?>">
                            <?php echo e(__('Units')); ?>

                        </a>

                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Reservations')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('bookings.lists')); ?>">
                            <?php echo e(__('Reservations')); ?>

                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('bookings.lists')); ?>">
                            <?php echo e(__('Payments')); ?>

                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('guests.lists')); ?>">
                            <?php echo e(__('Guests')); ?>

                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('bookings.lists')); ?>">
                            <?php echo e(__('Sync Reservations')); ?>

                        </a>

                    </div>
                </div>
            </div>
        </li>

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Rates & Availability')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.lists')); ?>">
                            <?php echo e(__('Manage Rates')); ?>

                        </a>
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.units.lists')); ?>">
                            <?php echo e(__('Sync Rates')); ?>

                        </a>

                    </div>
                </div>
            </div>
        </li>

        

        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Configuration')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.general', ['view' => 'channel-manager'])); ?>">
                            <?php echo e(__('Settings')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </li>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\ChannelManager\resources\views\layouts\navbar-menu.blade.php ENDPATH**/ ?>