<div>
    <!-- Table -->
    <div class="mb-2 table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
                <tr class="list-tr">
                    <th class="w-1"><input class="m-0 align-middle form-check-input" type="checkbox" aria-label="Select all invoices"></th>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->columns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th wire:click="sort('<?php echo e($column->key); ?>')" class="cursor-pointer fs-5">
                            <?php echo e($column->label); ?>

                            <!-- Sort By -->
                            <!--[if BLOCK]><![endif]--><?php if($sortBy === $column->key): ?>
                            <!--[if BLOCK]><![endif]--><?php if($sortDirection === 'asc'): ?>
                                <i class="bi bi-arrow-up-short"></i>
                            <?php else: ?>
                            <i class="bi bi-arrow-down-short"></i>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </tr>
            </thead>
            
            <tbody class="bg-white ">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->data(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $expand = in_array($row->id, $this->expandedRows) ? 'Collapse' : 'Expand';
                ?>
                <tr class="cursor-pointer kover-navlink" wire:click="toggleRowExpansion(<?php echo e($row->id); ?>)">
                    <td>
                        <input class="m-0 align-middle form-check-input" type="checkbox" wire:model.defer="ids.<?php echo e($row->id); ?>" wire:click="toggleCheckbox(<?php echo e($row->id); ?>)" wire:loading.attr="disabled" defer>
                    </td>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->columns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $column->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $row[$column->key],'id' => $row->id]); ?>
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
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <div class="centered-section ">

                    </div>
                </tr>
                
               
                <?php
                    $hasSubData = method_exists($this, 'subData') && method_exists($this, 'subColumns');
                ?>
                <!--[if BLOCK]><![endif]--><?php if($hasSubData && in_array($row->id, $this->expandedRows)): ?>
                <tr class="expandable-row show" colspan="6">
                    <td colspan="6">
                        <table class="table card-table table-vcenter text-nowrap datatable">
                            <thead class="list-table">
                                <tr>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->subColumns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="cursor-pointer fs-5" colspan="auto">
                                        <?php echo e($column->label); ?>

                                    </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->subData($row->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="cursor-pointer kover-navlink">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->subColumns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td>
                                        <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $column->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $row[$column->key],'id' => $row->id]); ?>
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
                                    </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            </tbody>
        </table>
        <div class="card-footer d-flex align-items-end ms-auto w-100">
            <?php echo e($this->data()->links()); ?>

        </div>
    </div>
    <!--[if BLOCK]><![endif]--><?php if($this->data()->count() == 0): ?>
    <div class="bg-white empty k_nocontent_help">
        <img src="<?php echo e(asset('assets/images/illustrations/errors/missing-element.svg')); ?>"style="height: 350px" alt="">
        <p class="empty-title"><?php echo e($this->emptyTitle()); ?></p>
        <p class="empty-subtitle"><?php echo e($this->emptyText()); ?></p>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/livewire/components/table/table.blade.php ENDPATH**/ ?>