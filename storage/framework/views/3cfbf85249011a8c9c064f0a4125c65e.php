<div>

    <!-- Table -->
    <div class="table-responsive mb-2">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead class="list-table">
            <tr class="list-tr">
                <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>
                <?php $__currentLoopData = $this->columns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th wire:click="sort('<?php echo e($column->key); ?>')" class="cursor-pointer">
                        <?php echo e($column->label); ?>

                        <!-- Sort By -->
                        <?php if($sortBy === $column->key): ?>
                          <?php if($sortDirection === 'asc'): ?>
                            <i class="bi bi-arrow-up-short"></i>
                          <?php else: ?>
                          <i class="bi bi-arrow-down-short"></i>
                          <?php endif; ?>
                        <?php endif; ?>
                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            </thead>
            <tbody class=" bg-white">
                <?php $__currentLoopData = $this->tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="cursor-pointer">
                    <td>
                        <input class="form-check-input m-0 align-middle" type="checkbox" wire:model.defer="ids.<?php echo e($row->id); ?>" wire:click="toggleCheckbox(<?php echo e($row->id); ?>)" wire:loading.attr="disabled" defer>
                    </td>
                    <?php $__currentLoopData = $this->columns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="centered-section ">

                    </div>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>
        <div class="card-footer d-flex align-items-end ms-auto w-100">
            <?php echo e($this->data()->links()); ?>

        </div>
    </div>
    <?php if(count($this->tables) == 0): ?>
    <div class="empty k_nocontent_help bg-white">
        <img src="<?php echo e(asset('assets/images/16.svg')); ?>"style="height: 350px" alt="">
        <p class="empty-title"><?php echo e($this->emptyTitle()); ?></p>
        <p class="empty-subtitle"><?php echo e($this->emptyText()); ?></p>
    </div>
    <?php endif; ?>

</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\livewire\components\table\template\dynamic-editable-table.blade.php ENDPATH**/ ?>