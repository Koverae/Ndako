<div>

    <div class="gap-3 px-3 k_control_panel d-flex flex-column gap-lg-1 sticky-top">
        <div class="gap-5 k_control_panel_main d-flex flex-nowrap justify-content-between align-items-lg-start flex-grow-1">
            <!-- Breadcrumbs -->
            <div class="gap-1 k_control_panel_breadcrumbs d-flex align-items-center order-0 h-lg-100">
                <!-- Create Button -->
                <!--[if BLOCK]><![endif]--><?php if($this->new): ?>
                <a href="<?php echo e($new); ?>" wire:navigate class="btn btn-outline-primary k_form_button_create">
                    <?php echo e(__('New')); ?>

                </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <!--[if BLOCK]><![endif]--><?php if($this->add): ?>
                <a wire:click="add" class="btn btn-outline-primary k_form_button_create">
                    <?php echo e($createButtonLabel); ?>

                </a>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <?php
                    $filteredBreadcrumbs = collect($breadcrumbs)->filter(fn($breadcrumb) =>
                        $breadcrumb['url'] && $breadcrumb['url'] !== url()->current()
                    );
                ?>

                <div class="min-w-0 gap-2 k_last_breadcrumb_item active align-items-center lh-sm">

                    <!--[if BLOCK]><![endif]--><?php if($showBreadcrumbs && $filteredBreadcrumbs->isNotEmpty()): ?>
                    <span>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filteredBreadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if(!$loop->first): ?>
                                <span class="mx-1">/</span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                            <!--[if BLOCK]><![endif]--><?php if(!empty($breadcrumb['url'])): ?>
                                <a href="<?php echo e($breadcrumb['url']); ?>" wire:navigate class="fw-bold text-truncate text-decoration-none">
                                    <?php echo e($breadcrumb['label']); ?>

                                </a>
                            <?php else: ?>
                                <span class="fw-bold text-truncate text-decoration-none" aria-current="page">
                                    <?php echo e($breadcrumb['label']); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <div class="gap-1 d-flex">
                        <span class="min-w-0 text-truncate " style="height: 19px;">
                            <?php echo e($this->currentPage); ?>

                        </span>
                        <div class="gap-1 k_cp_action_menus d-flex align-items-center pe-2">

                            <div class="k_dropdown dropdown dropend lh-1 dropdown-no-caret">
                                <a href="#" class="btn-action text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear" wire:loading.remove></i>
                                </a>
                                <div class="k_dropdown_menu dropdown-menu dropdown-menu-end">

                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->actionButtons(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action_button): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $action_button->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $action_button]); ?>
                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if($this->showIndicators): ?>
                        <div class="k_form_status_indicator_buttons d-flex">
                            <span wire:loading.remove wire:click.prevent="saveUpdate()" wire:target="saveUpdate()" class="px-1 py-0 cursor-pointer k_form_button_save btn-light rounded-1 lh-sm">
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                            </span>
                            <span wire:click.prevent="resetForm()" wire:loading.remove class="px-1 py-0 cursor-pointer k_form_button_save btn-light lh-sm">
                                <i class="bi bi-arrow-return-left"></i>
                            </span>
                            <span wire:loading wire:target="saveUpdate()">...</span>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($this->change): ?>
                        <span class="p-0 ml-2 fs-4"><?php echo e(__('Usaved changes')); ?></span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>

            <!--[if BLOCK]><![endif]--><?php if(!$this->isForm): ?>
            <!-- Actions / Search Bar -->

            <!-- Actions -->

            
            <!--[if BLOCK]><![endif]--><?php if($hasSelection): ?>
            <div id="actions" class="order-2 gap-2 d-none d-lg-inline-flex rounded-2 k_panel_control_actions_search d-flex align-items-center justify-content-between order-lg-1 ">

                <div class="gap-3 d-flex align-items-center">
                    <div class="k_cp_switch_buttons align-items-center">

                        <span class="w-auto gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list">
                            <?php echo e(count($selected)); ?> selected
                            <i wire:click="emptyArray" class="bi bi-x"></i>
                        </span>

                        <!-- Action Buttons -->

                        <!-- Dropdown button -->
                        <div class="btn-group">
                            <span class="gap-1 k_switch_view d-lg-inline-block btn btn-secondary active k-list w-100" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="fas fa-cog"></i> Actions
                            </span>
                            <ul class="dropdown-menu">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->actionDropdowns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $action->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $action]); ?>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <!--<li><hr class="dropdown-divider"></li>-->
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Actions -->

            <?php else: ?>
            <!-- Search Bar -->
            <div class="order-2 gap-2 d-none d-lg-inline-flex rounded-2 k_panel_control_actions_search d-flex align-items-center justify-content-between order-lg-1">
                
                <div class="gap-2 d-flex align-items-center">
                    <span class="p-1 border-0 cursor-pointer">
                        <i class="bi bi-search"></i>
                    </span>

                    <div class="gap-2 d-flex">
                        <!-- Filters -->
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $valueList = is_array($values) ? $values : [$values];
                            ?>

                            <span class="cursor-pointer fs-4" style="background-color: #D8DADD;" wire:click="removeFilter('<?php echo e($key); ?>')">
                                <i class="p-1 text-white fas fa-filter rounded-2" style="background-color: #52374B;"></i>
                                <?php echo e(implode(' > ', array_map('ucfirst', $valueList))); ?>

                                <i class="bi bi-x fs-3"></i>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <!-- Filters -->

                        <!-- Group By -->
                        <span class="cursor-pointer fs-4"style="background-color: #D8DADD;">
                            <i class="p-1 text-white fas fa-layer-group rounded-2" style="background-color: #017E84;"></i>
                            Urban Nest
                            <i class="bi bi-x fs-3"></i>
                        </span>
                        <!-- Group By -->
                    </div>
                    <input type="text" wire:model.live='search' placeholder="Search..." class="w-auto k_searchview">
                    <div class="dropdown k_filter_search align-items-end">
                        <span class="btn dropdown-toggle rounded-0" style="height: 34px;" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                            &nbsp;
                            <!-- Filter icon or text -->
                        </span>
                        <!-- Filter dropdown -->

                        <!-- Dropdown Menu -->
                        <div class="p-3 dropdown-menu" aria-labelledby="dropdownMenu2">
                            <!-- Loop through the filter groups dynamically -->
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $filterTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $options): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!-- Filter Group: type, status, etc. -->
                            <h6 class="dropdown-header"><?php echo e(ucfirst($group)); ?></h6>

                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="gap-2 cursor-pointer d-flex rounded-2" wire:click="toggleFilter('<?php echo e($group); ?>', '<?php echo e($option); ?>')"
                                    style="<?php echo e(in_array($option, $filters[$group] ?? []) ? 'background-color: #D8DADD; font-weight: bold;' : ''); ?>">

                                    <!-- Checkmark if selected -->
                                    <i class="p-2 fas fa-check fw-bold <?php echo e(in_array($option, $filters[$group] ?? []) ? '' : 'd-none'); ?>" style="color: #017E84;"></i>

                                    <span class="p-1 form-check-label">
                                        <?php echo e(ucfirst($option)); ?>

                                    </span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                            <hr class="dropdown-divider">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <!-- Dropdown Menu -->

                    </div>

                </div>
            </div>
            <!-- Search Bar -->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Actions / Search Bar -->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!-- Navigations -->
            <!--[if BLOCK]><![endif]--><?php if(!$this->isForm): ?>
            <div class="flex-wrap order-3 align-items-end k_control_panel_navigation d-flex flex-md-wrap align-items-center justify-content-end gap-l-1 gap-xl-5 order-lg-2 flex-grow-1">
                <!-- Display panel buttons -->
                <div class="k_cp_switch_buttons d-print-none d-xl-inline-flex btn-group">
                    <!-- Button view -->
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->switchButtons(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $switchButton): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $switchButton->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $switchButton]); ?>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/navbar/control-panel.blade.php ENDPATH**/ ?>