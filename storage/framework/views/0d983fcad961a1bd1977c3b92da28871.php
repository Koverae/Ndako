<?php $__env->startSection('page_title', "Choose a plan to continue managing your properties"); ?>


<section class="overflow-x-hidden page page-center" style="height: 100%;">

    <div class="row align-items-center g-4 started">
        <div class="col-lg d-none d-lg-block started-background">
        </div>
        <div class="col-lg">
            <div class="container py-4">
                <div class="mt-0 mb-2">
                    <?php if($renew): ?>
                    <h1 class="text-3xl font-bold text-gray-800">Renew your Ndako Subscription</h1>
                    <?php else: ?>
                    <h1 class="text-3xl font-bold text-gray-800">Subscribe to Ndako</h1>
                    <?php endif; ?>

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
                        <label class="form-label">Number of Units/Rooms</label>
                        <input type="number" wire:model.live="roomCount" min="1" class="form-control" placeholder="Enter number of rooms">
                        <small class="text-muted">Enter the total number of rooms you manage or plan to manage.</small>
                    </div>

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

                    <div class="mb-2">
                      <label class="form-label">Choose a Plan</label>
                      <div class="form-selectgroup">
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="form-selectgroup-item">
                           <input type="radio" wire:model.live="selectedPlan" value="<?php echo e($plan->tag); ?>" class="form-selectgroup-input">
                           <span class="form-selectgroup-label text-start">
                             <span class="text-black"><?php echo e($plan->name); ?></span> <br>
                             <span class="text-small">
                                <?php echo e(format_currency($plan->discounted_price * max(1, $roomCount))); ?> <s><?php echo e(format_currency($plan->price * max(1, $roomCount))); ?></s>
                             </span>
                           </span>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </div>
                    </div>

                    <div class="mb-2 col-md-12 col-lg-12">
                        <label class="form-label">Invoice Period</label>
                        <div class="number-input-wrapper <?php $__errorArgs = ['invoicePeriod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <span class="btn btn-link minus" wire:click="decreaseInvoicePeriod">−</span>
                            <input type="number" id="number-input" min="1" wire:model="invoicePeriod" class="number-input" />
                            <span class="btn btn-link plus" wire:click="increaseInvoicePeriod">+</span>
                        </div>
                        <span><?php echo e(ucfirst($billingCycle)); ?>(s)</span>
                        <?php $__errorArgs = ['invoicePeriod'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="mt-1 text-danger">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php if($selectedPlan): ?>
                    <span>You are about to <?php echo e($renew ? "renew" : "subscribe"); ?> to <strong><?php echo e(getPlan($selectedPlan)); ?></strong> for <b><?php echo e(format_currency($amount)); ?></b> to manage your <b><?php echo e($roomCount); ?> rooms/units</b> for <?php echo e($invoicePeriod); ?> <?php echo e($billingCycle.'(s)'); ?>.</span>
                    <?php endif; ?>

                    <div class="mb-2 form-footer">
                        <span wire:click="initiatePayment" class="<?php echo e($selectedPlan && $invoicePeriod >= 1 ? '' : 'disabled'); ?> text-uppercase btn btn-primary w-100">
                            Subscribe Now
                        </span>
                    </div>

                    <span class="text-sm text-gray-600 text-muted">
                        Not sure which plan is best for your needs? Want more details about what each plan includes? <a href="https://ndako.koverae.com#pricing" target="__blank">See our pricing</a> to learn more or reach out to us for help!
                    </span>

                </form>


            </div>
        </div>
    </div>
</section>
<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\livewire\subscription\subscription-page.blade.php ENDPATH**/ ?>