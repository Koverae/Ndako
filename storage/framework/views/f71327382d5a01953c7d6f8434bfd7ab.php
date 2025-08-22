<nav class="navbar navbar-expand-md w-100 navbar-light d-block d-print-none k-sticky">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo -->
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="">
                <img src="<?php echo e(asset('assets/images/logo/logo-black.png')); ?>" alt="Ndako Logo" class="navbar-brand-image">
            </a>
        </h1>
        <!-- Logo End -->

        <!-- Navbar Buttons -->
        <div class="flex-row navbar-nav order-md-last">
            <div class="d-md-flex d-flex">
                <!-- Translate -->
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="px-0 nav-link" data-bs-toggle="dropdown" id="dropdownMenuButton" title="Translate" data-bs-placement="bottom">
                        <i class="bi bi-translate" style="font-size: 16px;"></i>
                    </a>
                </div>
                <!-- Translate End -->

                <!-- Shortcuts (role-specific) -->
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo e(__('Shortcuts')); ?>">
                        <i class="bi bi-lightning-charge-fill"></i>
                        <span class="d-none d-xl-inline fw-semibold"><?php echo e(__('Shortcuts')); ?></span>
                        <i class="bi bi-chevron-down small opacity-75"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-2" style="min-width: 280px;">

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'owner|manager')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Owner / Manager')); ?></h6>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access_settings')): ?>
                                <a href="<?php echo e(route('settings.general', ['view' => 'general'])); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-gear me-2"></i><?php echo e(__('Settings')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_staff')): ?>
                                <a href="<?php echo e(route('settings.users')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-people me-2"></i><?php echo e(__('Users')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_roles')): ?>
                                <a href="<?php echo e(route('roles.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-shield-lock me-2"></i><?php echo e(__('Roles & Permissions')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_properties')): ?>
                                <a href="<?php echo e(route('properties.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-buildings me-2"></i><?php echo e(__('Properties')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_expenses')): ?>
                                <a href="<?php echo e(route('expenses.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-receipt me-2"></i><?php echo e(__('Expenses')); ?>

                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'front-office')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Front Office')); ?></h6>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view_reservations','create_reservations','modify_reservations'])): ?>
                                <a href="<?php echo e(route('bookings.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-calendar2-check me-2"></i><?php echo e(__('Reservations')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_rooms')): ?>
                                <a href="<?php echo e(route('properties.units.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-door-open me-2"></i><?php echo e(__('Room Board')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_guests')): ?>
                                <a href="<?php echo e(route('guests.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-person-lines-fill me-2"></i><?php echo e(__('Guests')); ?>

                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'reservations')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Reservations')); ?></h6>
                            <a href="<?php echo e(route('bookings.lists')); ?>" class="dropdown-item kover-navlink">
                                <i class="bi bi-calendar-event me-2"></i><?php echo e(__('All Reservations')); ?>

                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_reservation_payments')): ?>
                                <a href="<?php echo e(route('bookings.payments.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-credit-card me-2"></i><?php echo e(__('Payments')); ?>

                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'housekeeping')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Housekeeping')); ?></h6>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_housekeeping_board')): ?>
                                <a href="<?php echo e(route('tasks.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bucket me-2"></i><?php echo e(__('HK Board & Tasks')); ?>

                                </a>
                            <?php elseif (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_maintenance_tasks')): ?>
                                <a href="<?php echo e(route('tasks.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bucket me-2"></i><?php echo e(__('Tasks')); ?>

                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'maintenance')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Maintenance')); ?></h6>
                            <a href="<?php echo e(route('tasks.lists')); ?>" class="dropdown-item kover-navlink">
                                <i class="bi bi-tools me-2"></i><?php echo e(__('Work Orders')); ?>

                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'accounting')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('Accounting')); ?></h6>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_invoices')): ?>
                                <a href="<?php echo e(route('expenses.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-receipt me-2"></i><?php echo e(__('Expenses & Invoices')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_reservation_payments')): ?>
                                <a href="<?php echo e(route('bookings.payments.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-cash-coin me-2"></i><?php echo e(__('Reservation Payments')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_pos_payments')): ?>
                                <a href="<?php echo e(route('order-payments.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-wallet2 me-2"></i><?php echo e(__('POS Payments')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_pos_sessions')): ?>
                                <a href="<?php echo e(route('pos-sessions.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-clock-history me-2"></i><?php echo e(__('Cash Sessions')); ?>

                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>

                        
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'cashier')): ?>
                            <h6 class="dropdown-header text-uppercase text-muted small"><?php echo e(__('POS')); ?></h6>
                            <a href="<?php echo e(route('pos.overview')); ?>" class="dropdown-item kover-navlink">
                                <i class="bi bi-grid-1x2 me-2"></i><?php echo e(__('POS Overview')); ?>

                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_pos_orders')): ?>
                                <a href="<?php echo e(route('orders.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-bag-check me-2"></i><?php echo e(__('Orders')); ?>

                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_pos_sessions')): ?>
                                <a href="<?php echo e(route('pos-sessions.lists')); ?>" class="dropdown-item kover-navlink">
                                    <i class="bi bi-clock-history me-2"></i><?php echo e(__('Sessions')); ?>

                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <?php if(trim($__env->yieldContent('shortcuts_extra'))): ?>
                            <div class="dropdown-divider"></div>
                            <?php echo $__env->yieldContent('shortcuts_extra'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /Shortcuts -->

                
                <?php
                    $userCompanies = current_company()->team->companies()->orderBy('companies.name')->get();
                    $currentCompany = current_company();
                ?>

                <?php if($userCompanies->count() > 0): ?>
                <div class="nav-item dropdown d-md-flex me-3">
                    <a href="#" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo e(__('Switch company')); ?>">
                        <?php if($currentCompany?->avatar): ?>
                            <span class="avatar avatar-sm rounded" style="background-image:url(<?php echo e(Storage::url("/avatars/{$currentCompany->avatar}")); ?>)"></span>
                        <?php else: ?>
                            <span class="avatar avatar-sm rounded" style="background-image:url(<?php echo e(asset('assets/images/default/placeholder.png')); ?>)"></span>
                        <?php endif; ?>
                        <span class="d-none d-sm-inline fw-semibold text-truncate" style="max-width: 140px;">
                            <?php echo e(\Illuminate\Support\Str::limit($currentCompany?->name, 24)); ?>

                        </span>
                        <i class="bi bi-chevron-down small opacity-75"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow p-3" style="min-width: 320px;">
                        <div class="mb-2 position-relative">
                            <input type="search" class="form-control form-control-sm" placeholder="<?php echo e(__('Search companies…')); ?>" id="companySearch">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                        </div>

                        <ul class="list-unstyled mb-2" id="companyList">
                            <?php $__currentLoopData = $userCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-1">
                                    <form method="POST" action="<?php echo e(route('company.switch')); ?>" class="m-0 p-0">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 rounded <?php echo e($currentCompany && $currentCompany->id === $company->id ? 'active' : ''); ?>">
                                            <?php if($company->avatar): ?>
                                                <span class="avatar avatar-sm rounded" style="background-image:url(<?php echo e(Storage::url("/avatars/{$company->avatar}")); ?>)"></span>
                                            <?php else: ?>
                                                <span class="avatar avatar-sm rounded" style="background-image:url(<?php echo e(asset('assets/images/default/placeholder.png')); ?>)"></span>
                                            <?php endif; ?>
                                            <div class="flex-grow-1 text-start">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-semibold"><?php echo e($company->name); ?></span>
                                                    <?php if($currentCompany && $currentCompany->id === $company->id): ?>
                                                        <span class="badge bg-primary-subtle text-white"><?php echo e(__('Current')); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access_companies')): ?>
                        <div class="d-grid pt-2 border-top">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> <?php echo e(__('Create new company')); ?>

                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                

                <!-- Chat & Notifications -->
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::components.notification-trigger', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-4197708357-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                <!-- Chat & Notifications End -->

                <!-- User's Avatar -->
                <div class="nav-item dropdown">
                    <a href="#" class="p-0 nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url(<?php echo e(Storage::url('avatars/' . auth()->user()->avatar)); ?>)"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="<?php echo e(route('settings.users.show', ['user' => auth()->user()->id])); ?>" class="dropdown-item kover-navlink">My Profile</a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_kover_subscription')): ?>
                        <a href="<?php echo e(route('settings.general') . '#subs'); ?>" class="dropdown-item kover-navlink">My Subscription</a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <span onclick="event.preventDefault(); this.closest('form').submit();" class="cursor-pointer kover-navlink dropdown-item">
                                <?php echo e(__('Log Out')); ?>

                            </span>
                        </form>
                    </div>
                </div>
                <!-- User's Avatar End -->
            </div>
        </div>
        <!-- Navbar Buttons End -->

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                <ul class="navbar-nav">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">

                        <!-- Dashboard -->
                        <li class="nav-item" data-turbolinks>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_dashboard')): ?>
                            <a class="nav-link kover-navlink" href="<?php echo e(route('dashboard')); ?>" style="margin-right: 5px;">
                                <span class="nav-link-title"><?php echo e(__('Dashboard')); ?></span>
                            </a>
                            <?php endif; ?>
                        </li>

                        <!-- Properties (Owner/Manager only; no Rooms/Maintenance here to avoid duplication) -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'owner|manager')): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Properties')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_properties')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.lists')); ?>">
                                            <?php echo e(__('Properties')); ?>

                                        </a>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>

                        <!-- Rooms (single hub for Rooms/HK/Maintenance) -->
                        <?php if(auth()->user()->hasAnyRole(['owner','manager','front-office','housekeeping','maintenance'])): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view_rooms','view_housekeeping_board','view_maintenance_tasks'])): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Rooms')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_rooms')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('properties.units.lists')); ?>">
                                            <?php echo e(__('Rooms')); ?>

                                        </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_housekeeping_board')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('tasks.lists')); ?>">
                                            <?php echo e(__('Housekeeping Board')); ?>

                                        </a>
                                        <?php endif; ?>

                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_maintenance_tasks')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('tasks.lists')); ?>">
                                            <?php echo e(__('Maintenance Requests')); ?>

                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>

                        <!-- Reservations (Owner/Manager/FO/Reservations) -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'owner|manager|front-office|reservations')): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['view_reservations','create_reservations','modify_reservations','cancel_reservations','manage_reservations'])): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Reservations')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('bookings.lists')); ?>">
                                            <?php echo e(__('Reservations')); ?>

                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_reservation_payments')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('bookings.payments.lists')); ?>">
                                            <?php echo e(__('Payments')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_guests')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('guests.lists')); ?>">
                                            <?php echo e(__('Guests')); ?>

                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>

                        <!-- Restaurants / POS (Owner/Manager/Cashier/Accounting) -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'owner|manager|cashier|accounting')): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access_pos')): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Restaurants')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('pos.overview')); ?>">
                                            <?php echo e(__('Overview')); ?>

                                        </a>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_pos_products')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('product-categories.lists')); ?>">
                                            <?php echo e(__('Product Categories')); ?>

                                        </a>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('products.lists')); ?>">
                                            <?php echo e(__('Products')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('pos-floors.lists')); ?>">
                                            <?php echo e(__('Floor Plans')); ?>

                                        </a>
                                    </div>
                                    <div class="dropdown-menu-column">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_pos_orders')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('orders.lists')); ?>">
                                            <?php echo e(__('Orders')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_pos_sessions')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('pos-sessions.lists')); ?>">
                                            <?php echo e(__('Sessions')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view_pos_payments')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('order-payments.lists')); ?>">
                                            <?php echo e(__('Payments')); ?>

                                        </a>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>

                        <!-- Expenses (Accounting/Manager/Owner via permission) -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_expenses')): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Expenses')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('expenses.categories.lists')); ?>">
                                            <?php echo e(__('Expense Categories')); ?>

                                        </a>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('expenses.lists')); ?>">
                                            <?php echo e(__('Expenses')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>

                        <!-- Configuration (Owner/Manager only) -->
                        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'owner|manager')): ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access_settings')): ?>
                        <li class="nav-item dropdown" data-turbolinks>
                            <a class="nav-link kover-navlink" href="#navbar-base" style="margin-right: 5px;" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-title"><?php echo e(__('Configuration')); ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access_settings')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.general', ['view' => 'general'])); ?>">
                                            <?php echo e(__('Settings')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_staff')): ?>
                                        <a class="kover-navlink dropdown-item" wire:navigate href="<?php echo e(route('settings.users')); ?>">
                                            <?php echo e(__('Users')); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage_roles')): ?>
                                        <a class="kover-navlink dropdown-item" href="<?php echo e(route('roles.lists')); ?>" wire:navigate>
                                            <?php echo e(__('Roles & Permissions')); ?>

                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </ul>
            </div>
        </div>
        <!-- Navbar Menu End -->

    </div>

    
    <?php if(!current_company()->is_onboarded && auth()->user()->hasAnyRole(['owner','manager'])): ?>
    <div class="alert alert-primary border-0 text-primary-emphasis rounded-3 shadow-sm sticky-top <?php echo e(Route::currentRouteName() == 'onboarding' ? 'd-none' : ''); ?> d-flex align-items-center justify-content-between py-3 px-4 mb-3" role="alert">
        <div class="d-flex align-items-center">
            <span class="badge rounded-pill text-bg-primary d-inline-flex align-items-center justify-content-center me-3" style="width:2.25rem;height:2.25rem;">
            <i class="bi bi-rocket-takeoff"></i>
            </span>
            <div>
            <div class="fw-semibold"><?php echo e(__('Get the most out of Ndako! Let’s complete your setup')); ?></div>
            <small class="text-secondary d-none d-sm-inline"><?php echo e(__('Finish company, property, rooms & taxes in a few steps.')); ?></small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('onboarding')); ?>" class="btn btn-primary btn-sm px-3">
            <i class="bi bi-magic me-1"></i><?php echo e(__('Continue setup')); ?>

            </a>
            <button type="button" class="btn btn-link btn-sm text-decoration-none text-primary" data-bs-dismiss="alert" aria-label="Close">
            <?php echo e(__('Dismiss')); ?>

            </button>
        </div>
    </div>
    <?php endif; ?>
    
    
    <?php if(current_company()->team->subscription('main')->isOnTrial()): ?>
    <?php
        $daysLeft   = (int) getRemainingTrialDays();
        $trialDays  = 14;
        $pct        = max(0, min(100, (($trialDays - $daysLeft) / max(1,$trialDays)) * 100));
        $trialEndsAt = optional(current_company()->team->subscription('main'))->trial_ends_at;
    ?>
    <div class="alert alert-warning bg-warning-subtle border-0 text-warning-emphasis rounded-3 shadow-sm mt-2" role="alert">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">

        <!-- Left: icon + text -->
        <div class="d-flex w-100 w-md-auto">
        <span class="badge rounded-pill text-bg-warning d-inline-flex align-items-center justify-content-center me-2 me-md-3 flex-shrink-0" style="width:2.25rem;height:2.25rem;">
            <i class="bi bi-hourglass-split"></i>
        </span>

        <div class="text-wrap">
            <div class="fw-semibold mb-1"><?php echo e(__('Your trial is ending soon')); ?></div>
            <div class="small">
            <?php echo __('Your trial will expire in <b>:days</b>.', ['days' => getRemainingTrialDays()]); ?>

            <?php if($trialEndsAt): ?>
                <span class="ms-1 d-inline-block"><?php echo e(__('Ends on')); ?> <b><?php echo e(\Carbon\Carbon::parse($trialEndsAt)->toFormattedDateString()); ?></b></span>
            <?php endif; ?>
            <span class="ms-1 d-inline-block"><?php echo e(__('Upgrade now to keep everything running smoothly.')); ?></span>
            </div>

            <div class="progress mt-2" style="height:6px;">
            <div class="progress-bar progress-bar-striped" role="progressbar"
                style="width: <?php echo e($pct); ?>%;" aria-valuenow="<?php echo e((int)$pct); ?>" aria-valuemin="0" aria-valuemax="100">
            </div>
            </div>
        </div>
        </div>

        <!-- Right: actions -->
        <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 ms-0 ms-md-3 w-100 w-md-auto">
        <a href="<?php echo e(route('subscribe')); ?>" class="btn btn-warning btn-sm fw-semibold w-100 w-sm-auto">
            <i class="bi bi-stars me-1"></i><?php echo e(__('Upgrade now')); ?>

        </a>
        <button type="button" class="btn btn-link btn-sm text-warning text-decoration-none w-100 w-sm-auto text-start text-sm-center" data-bs-dismiss="alert">
            <?php echo e(__('Remind me later')); ?>

        </button>
        </div>

    </div>
    </div>
    <?php endif; ?>



    <!-- Controls Panel -->
    <?php echo $__env->yieldContent('control-panel'); ?>
    <!-- Controls Panel -->
</nav>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('companySearch');
    if (!input) return;
    const items = document.querySelectorAll('#companyList li');
    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        items.forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>