<?php $__env->startSection('page_title', "Choose a plan to continue managing your properties"); ?>


<section class="overflow-x-hidden page page-center" style="height: 100%;">

    <div class="row align-items-center g-4 started">
        <div class="col-lg d-none d-lg-block started-background">
        </div>
        <div class="col-lg">
            <div class="container py-4">
                <div class="mt-0 mb-2">
                    <h1 class="text-3xl font-bold text-gray-800" wire:click="changeNew">Subscribe to Ndako</h1>
                    <p class="mt-2 text-lg text-gray-600">
                        Keep your property management running smoothly! Choose a plan now to continue accessing all the tools you need, seamlessly and without interruption
                    </p>
                </div>
                <!-- Session Status -->
                <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
                <!-- Session Status -->

                <form class="row" id="getStarted">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                      <label class="form-label">Billing Cycle</label>
                      <div class="form-selectgroup">
                        <label class="form-selectgroup-item">
                          <input type="radio" wire:model.live="billingCycle" value="month" class="form-selectgroup-input">
                          <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/circle -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                            Monthly</span>
                        </label>
                        <label class="form-selectgroup-item">
                          <input type="radio" wire:model.live="billingCycle" value="year" class="form-selectgroup-input">
                          <span class="form-selectgroup-label"><!-- Download SVG icon from http://tabler-icons.io/i/square -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>
                            Yearly</span>
                        </label>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Choose a Plan</label>
                      <div class="form-selectgroup">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="form-selectgroup-item">
                           <input type="radio" wire:model.live="selectedPlan" value="<?php echo e($plan->tag); ?>" class="form-selectgroup-input">
                           <span class="form-selectgroup-label text-start">
                             <span class="text-black"><?php echo e($plan->name); ?></span> <br>
                             <span class="text-small"><?php echo e(format_currency(getFinalPrice($plan->price))); ?> <s><?php echo e(format_currency($plan->price)); ?></s> 
                             
                             </span>
                           </span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                      </div>
                    </div>

                    <div class="mb-2 form-footer">
                        <span wire:click="initiatePayment" class=" text-uppercase btn btn-primary w-100">
                            Subscribe Now
                        </span>
                    </div>

                    <span class="text-gray-600 text-muted">
                        Need help? <a href="https://ndako.koverae.com/contact-us" target="_blank" class="hover:underline">Contact us</a>.
                    </span>
                </form>


            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/subscription/subscription-page.blade.php ENDPATH**/ ?>