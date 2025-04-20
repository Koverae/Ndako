<div>
    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
    
        <li class="nav-item">
            <a class="nav-link kover-navlink dropdown" wire:navigate href="<?php echo e(route('properties.index')); ?>" style="margin-right: 5px;">
              <span class="nav-link-title">
                  <?php echo e(__('Overview')); ?>

              </span>
            </a>
        </li>
    
        <li class="nav-item dropdown" data-turbolinks>
            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
              <span class="nav-link-title">
                  <?php echo e(__('Operations')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.users')); ?>">
                            <?php echo e(__('Users')); ?>

                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.companies.index')); ?>">
                            <?php echo e(__('Enterprises')); ?>

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
                  <?php echo e(__('Reporting')); ?>

              </span>
            </a>
            <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                    <!-- Left Side -->
                    <div class="dropdown-menu-column">
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.users')); ?>">
                            <?php echo e(__('Users')); ?>

                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.companies.index')); ?>">
                            <?php echo e(__('Enterprises')); ?>

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
                        <a class=" kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.general', ['view' => 'properties'])); ?>">
                            <?php echo e(__('Settings')); ?>

                        </a>
                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.companies.index')); ?>">
                            <?php echo e(__('Enterprises')); ?>

                        </a>
    
                    </div>
                </div>
            </div>
        </li>
    </div>
</div><?php /**PATH D:\My Laravel Startup\ndako\Modules\Properties\resources\views\layouts\navbar-menu.blade.php ENDPATH**/ ?>