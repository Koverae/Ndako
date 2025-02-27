<?php $__env->startSection('page_title', 'Verify OTP'); ?>

<?php $__env->startSection('page_content'); ?>
<div class="page page-center">
  <div class="container py-4 container-tight">
    <div class="card card-md">
      <div class="card-body">
        <div class="mt-0 mb-2 text-center">
          <a href="#" class="navbar-brand navbar-brand-autodark">
            <img src="<?php echo e(asset('assets/images/logo/logo-black.png')); ?>" width="130" height="52" alt="Tabler" class="navbar-brand-image">
          </a>
        </div>
        
        
        <form method="POST" action="<?php echo e(route('verify.store')); ?>">
            <?php echo csrf_field(); ?>
            <p class="my-4 text-center">Please confirm your account by entering the authorization code sent to <strong><?php echo e(auth()->user()->email); ?></strong>.</p>
            
            <!-- Session Status -->
            <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4 text-green-50','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4 text-green-50','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
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
            
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('two_factor_code'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('two_factor_code')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
            <div class="my-5">
              <div class="row g-4">
                <div class="col">
                  <div class="row g-2">
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" autofocus maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="row g-2">
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                    <div class="col">
                      <input type="tel" class="py-3 text-center form-control form-control-lg" maxlength="1" inputmode="numeric" name="two_factor_code[]" required pattern="[0-9]*" data-code-input />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="my-4">
              <label class="form-check">
                <input type="checkbox" name="dont_ask" class="form-check-input" />
                <?php echo e(__("Dont't ask for codes again on this device")); ?>

              </label>
            </div>
            <div class="form-footer">
              <div class="btn-list flex-nowrap">
                <button type="button" class="btn w-100">
                  <?php echo e(__('Cancel')); ?>

                </button>
                <button type="submit" class="capitalize btn btn-primary w-100 text-capitalize">
                  <?php echo e(__('Verify')); ?>

                </button>
              </div>
            </div>
            <div class="mt-3 text-center text-secondary">
                <?php echo e(new Illuminate\Support\HtmlString(__("It may take a minute to receive your code. Haven't received it? If not, click <a class=\"hover:underline\" href=\":url\">here</a>.", ['url' => route('verify.resend')]))); ?>

            </div>
        </form>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>