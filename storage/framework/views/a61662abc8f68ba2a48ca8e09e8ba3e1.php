<div>
    <style>
        /* Smooth expansion for sub-rows (keeps your classes) */
        tr.expandable-row td {
            padding-top: .75rem;
            padding-bottom: .75rem;
            background: #fff;
        }

        /* Smaller touch target for header checkbox so it doesn't "feel" heavy */
        .k-head-check { transform: scale(1.05); }

        /* Optional: faint hover to hint interactivity (kept minimal) */
        .kover-navlink:hover { background: rgba(0,0,0,.02); }
    </style>

    <div class="mb-2 table-responsive bg-white">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
                <tr class="list-tr">
                    <th class="w-1">
                        <input
                            class="m-0 align-middle form-check-input k-head-check"
                            type="checkbox"
                            wire:click="toggleSelectAll"
                            <?php if($this->isPageFullySelected()): ?> checked <?php endif; ?>
                            aria-label="Select all rows on this page">
                    </th>

                    <?php
                        $currentSort = $sortBy ?? '';
                        $currentDir  = $sortDirection ?? 'asc';
                    ?>

                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->columns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isActiveSort = $currentSort === $column->key;
                            $ariaSort = $isActiveSort ? ($currentDir === 'asc' ? 'ascending' : 'descending') : 'none';
                        ?>
                        <th
                            wire:click="sort('<?php echo e($column->key); ?>')"
                            class="cursor-pointer fs-5"
                            aria-sort="<?php echo e($ariaSort); ?>"
                            title="Sort by <?php echo e($column->label); ?>"
                        >
                            <?php echo e($column->label); ?>


                            
                            <!--[if BLOCK]><![endif]--><?php if($isActiveSort): ?>
                                <!--[if BLOCK]><![endif]--><?php if($currentDir === 'asc'): ?>
                                    <i class="bi bi-arrow-up-short"></i>
                                <?php else: ?>
                                    <i class="bi bi-arrow-down-short"></i>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </tr>
            </thead>

            
            <tbody class="bg-white">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->data(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $expanded = in_array($row->id, $this->expandedRows);
                    ?>
                    <tr class="cursor-pointer kover-navlink"
                        wire:key="row-<?php echo e($row->id); ?>"
                        wire:click="toggleRowExpansion(<?php echo e($row->id); ?>)"
                        aria-expanded="<?php echo e($expanded ? 'true' : 'false'); ?>"
                    >
                        <td>
                            
                            <input
                                class="m-0 align-middle form-check-input"
                                type="checkbox"
                                wire:model="selected.<?php echo e($row->id); ?>"
                                wire:click.stop="toggleCheckbox(<?php echo e($row->id); ?>)"
                                wire:loading.attr="disabled"
                                aria-label="Select row <?php echo e($row->id); ?>"
                            >
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
                    </tr>

                    
                    <?php
                        $hasSubData = method_exists($this, 'subData') && method_exists($this, 'subColumns');
                    ?>

                    <!--[if BLOCK]><![endif]--><?php if($hasSubData && $expanded): ?>
                        <tr class="expandable-row show" wire:key="sub-<?php echo e($row->id); ?>">
                            <td colspan="<?php echo e(count($this->columns()) + 1); ?>">
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
                                            <tr class="cursor-pointer kover-navlink" wire:key="subrow-<?php echo e($row->id); ?>">
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
        <div class="d-flex w-100 justify-content-between p-3 align-items-center">
            <div class="d-flex align-items-center gap-2">
                <label class="text-muted">Rows</label>
                <select class="form-select form-select-sm" wire:model.live="perPage" style="width:auto">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
            <div><?php echo e($this->data()->links()); ?></div>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if($this->data()->count() == 0): ?>
        <div class="bg-white empty k_nocontent_help h-100">
            <img src="<?php echo e(asset('assets/images/illustrations/errors/419.svg')); ?>" style="height: 350px" alt="">
            <p class="empty-title"><?php echo e($this->emptyTitle()); ?></p>
            <p class="empty-subtitle"><?php echo e($this->emptyText()); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/table/table.blade.php ENDPATH**/ ?>