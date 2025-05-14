<div>
    <style>
            :root {
                --primary-color: #017e84;
                --secondary-color: #f9fafb;
                --text-color: #1f2937;
                --error-color: #dc2626;
            }
            .wizard-steps {
                display: flex;
                gap: 8px;
                margin-bottom: 1.5rem;
                overflow-x: auto;
                scrollbar-width: thin;
                scrollbar-color: #d1d5db #f3f4f6;
            }
            .wizard-steps .step {
                flex: 1;
                min-width: 100px;
                padding: 12px;
                height: 24px;
                background: #e9e9e9;
                border-radius: 8px;
                text-align: center;
                transition: all 0.3s ease;
                cursor: pointer;
                color: var(--text-color);
                font-weight: 500;
                font-size: 14px;
            }
            .wizard-steps .step.active {
                background: var(--primary-color);
                color: white;
                box-shadow: 0 2px 8px rgba(1, 126, 132, 0.3);
            }
            .wizard-steps .step:before {
                content: attr(data-step);
                display: inline-block;
                width: 14px;
                height: 14px;
                line-height: 14px;
                background: white;
                color: var(--text-color);
                border-radius: 50%;
                margin-right: 2px;
                font-weight: 600;
                font-size: 14px;
            }
            .wizard-steps .step.active:before {
                background: #fff;
                color: var(--primary-color);
            }     
            .card.active-pick {
                border: 2px solid var(--primary-color);
            }
            .form-control {
                border-radius: 8px;
                border: 1px solid #d1d5db;
                padding: 0.75rem 1rem;
                font-size: 14px;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }
            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(1, 126, 132, 0.1);
                outline: none;
            }
            .form-control.is-invalid {
                border-color: var(--error-color);
            }
            .form-label {
                font-size: 14px;
                font-weight: 500;
                color: var(--text-color);
                margin-bottom: 0.5rem;
            }
            .text-danger {
                font-size: 12px;
                animation: fadeIn 0.3s ease;
            }
            .alert {
                border-radius: 8px;
                animation: fadeIn 0.3s ease;
                padding: 1rem;
            }
            .guest-card img {
                border-radius: 8px;
                object-fit: cover;
                width: 120px;
                height: 120px;
            }
            .room-card img {
                border-radius: 8px;
                object-fit: cover;
                width: 100%;
                max-height: 200px;
            }
            .guest-sidebar img {
                border-radius: 8px;
                object-fit: cover;
                width: 100%;
                max-height: 250px;
            }
            .badge-active {
                position: absolute;
                bottom: 8px;
                right: 8px;
                background: var(--primary-color);
                color: white;
                font-size: 12px;
                padding: 4px 8px;
                border-radius: 12px;
                font-weight: 500;
            }
            .search-container {
                position: relative;
                width: 100%;
            }
            .search-container .fa-search {
                position: absolute;
                top: 50%;
                left: 12px;
                transform: translateY(-50%);
                color: #6b7280;
            }
            .search-container .form-control {
                padding-left: 2.5rem;
            }
            .search-container .fa-spinner {
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                color: var(--primary-color);
            }
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @media (max-width: 768px) {
                .k-wizard {
                    padding: 1rem;
                }
                .wizard-steps {
                    padding-bottom: 0.5rem;
                }
                .wizard-steps .step {
                    min-width: 80px;
                    padding: 8px;
                    font-size: 12px;
                }
                .wizard-steps .step:before {
                    width: 20px;
                    height: 20px;
                    line-height: 20px;
                    font-size: 12px;
                }
                .wizard-navigation .btn {
                    padding: 0.5rem 1rem;
                    font-size: 14px;
                }
                .guest-card img {
                    width: 100px;
                    height: 100px;
                }
                .room-card img {
                    max-height: 150px;
                }
                .guest-sidebar {
                    margin-top: 1rem;
                }
                .guest-sidebar img {
                    max-height: 200px;
                }
                .form-control {
                    font-size: 13px;
                    padding: 0.5rem 0.75rem;
                }
                .btn-primary {
                    padding: 0.5rem 1rem;
                    font-size: 13px;
                }
            }
    </style>
    <div class="k-wizard">
        <!-- Steps -->
        <!--[if BLOCK]><![endif]--><?php if(count($this->steps()) >= 1): ?>
        <!-- Steps Navigation -->
        <div class="wizard-steps">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->steps(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $step->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $step]); ?>
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
        <!-- Steps Navigation End -->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <!-- Steps End -->

        <!-- Step Content -->
        <div class="wizard-content position-relative" style="height: auto;">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->stepPages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $page->component] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => $page]); ?>
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
        <div class="mt-3 wizard-navigation position-absolute <?php echo e($showButtons ? '' : 'd-none'); ?>">
            <button class="btn cancel" wire:click="goToPreviousStep" <?php echo e($currentStep == 0 ? 'disabled' : ''); ?>><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
            <button class="btn btn-primary go-next" wire:click="goToNextStep" <?php echo e($currentStep == count($this->steps()) - 1 ? 'disabled' : ''); ?>>Continue</button>
        </div>
        <!-- Step Content End -->
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/wizard/simple-wizard.blade.php ENDPATH**/ ?>