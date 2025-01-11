<?php $__env->startSection('page_title', 'Sign In'); ?>

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
        <h2 class="mb-4 text-center h2">Login to your account</h2>
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
        
        <form method="POST" action="<?php echo e(Route::subdomainRoute('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label" for="email">Email address</label>
                <input type="email" class="form-control" placeholder="eg. ardenbouet@koverae.com" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                
            </div>
            <div class="mb-2">
                <label class="form-label" for="password">
                Password
                <?php if(Route::has('password.request')): ?>
                <span class="form-label-description">
                    <a href="<?php echo e(Route::subdomainRoute('password.request')); ?>">I forgot password</a>
                </span>
                <?php endif; ?>
                </label>
                <div class="input-group input-group-flat">
                <input type="password" class="form-control"  placeholder="Your password" id="password" name="password"  autocomplete="off">
                <span class="input-group-text">
                    <span  onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://koverae-icons.io/i/eye -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </span>
                </span>
                </div>
                
            </div>
            <div class="mb-2">
                <label class="form-check" for="remember_me">
                <input type="checkbox" id="remember_me" name="remember" class="form-check-input"/>
                <span class="form-check-label">Remember me on this device</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
            </div>
        </form>
      </div>
      <div class="hr-text">or</div>
      <div class="p-3 card-body">
        <div class="row">
            <div class="mt-2 col-md-6 col-12">
                <a href="<?php echo e(Route::subdomainRoute('auth.social.redirect', ['provider' => 'google'])); ?>" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-google"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z" /></svg>
                    <?php echo e(__('Login with Google')); ?>

                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="<?php echo e(Route::subdomainRoute('auth.social.redirect', ['provider' => 'koverae'])); ?>" class="btn w-100">
                    <img src="<?php echo e(asset('assets/images/logo/favicon.ico')); ?>" width="20px" height="20px" alt="">
                    <?php echo e(__('Login with Koverae')); ?>

                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="<?php echo e(Route::subdomainRoute('auth.social.redirect', ['provider' => 'linkedin'])); ?>" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-linkedin"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 11l0 5" /><path d="M8 8l0 .01" /><path d="M12 16l0 -5" /><path d="M16 16v-3a2 2 0 0 0 -4 0" /></svg>
                    <?php echo e(__('Login with LinkedIn')); ?>

                </a>
            </div>
            <div class="mt-2 col-md-6 col-12">
                <a href="<?php echo e(Route::subdomainRoute('auth.social.redirect', ['provider' => 'facebook'])); ?>" class="btn w-100">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-meta"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10.174c1.766 -2.784 3.315 -4.174 4.648 -4.174c2 0 3.263 2.213 4 5.217c.704 2.869 .5 6.783 -2 6.783c-1.114 0 -2.648 -1.565 -4.148 -3.652a27.627 27.627 0 0 1 -2.5 -4.174z" /><path d="M12 10.174c-1.766 -2.784 -3.315 -4.174 -4.648 -4.174c-2 0 -3.263 2.213 -4 5.217c-.704 2.869 -.5 6.783 2 6.783c1.114 0 2.648 -1.565 4.148 -3.652c1 -1.391 1.833 -2.783 2.5 -4.174z" /></svg>
                    <?php echo e(__('Login with Facebook')); ?>

                </a>
            </div>
        </div>
        <?php if(Route::has('register')): ?>
        <div class="mt-4 text-center text-secondary">
          Don't have account yet? <a href="<?php echo e(Route::subdomainRoute('register')); ?>" tabindex="-1">Sign up</a>
        </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\My Laravel Startup\koverae-saas\resources\views/auth/login.blade.php ENDPATH**/ ?>