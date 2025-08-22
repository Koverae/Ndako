<?php $__env->startSection('page_title', 'Sign In Admin'); ?>

<?php $__env->startSection('page_content'); ?>
<div class="overflow-x-hidden page page-center">
    <div class="row align-items-center g-4">
        <div class="col-lg">
            <div class="container py-4 container-tight">
                <div class="card card-md">
                    <div class="card-body">
                        <div class="mt-0 mb-2 text-center">
                            <a href="#" class="navbar-brand navbar-brand-autodark">
                                <img src="<?php echo e(asset('assets/images/logo/koverae.png')); ?>" width="130" height="52" alt="Tabler" class="navbar-brand-image">
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

                        <form method="POST" action="<?php echo e(route('admin.login')); ?>" id="login">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email address</label>
                                <input type="email" class="form-control" placeholder="eg. ardenbouet@koverae.com" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label" for="password">
                                    Password
                                    <?php if(Route::has('password.request') && env('APP_DISTRIBUTION') === "production"): ?>
                                    <span class="form-label-description">
                                        <a href="<?php echo e(route('password.request')); ?>">I forgot password</a>
                                    </span>
                                    <?php endif; ?>
                                </label>
                                <div class="input-group input-group-flat">
                                    <input type="password" class="form-control" placeholder="Your password" id="password" name="password" autocomplete="off">
                                    <span class="input-group-text">
                                        <span onclick="togglePassword()" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                            </svg>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
    $('#login').submit(function(event) {
        event.preventDefault();

        grecaptcha.ready(function() {
            grecaptcha.execute("<?php echo e(env('GOOGLE_RECAPTCHA_KEY')); ?>", {action: 'subscribe_newsletter'}).then(function(token) {
                $('#login').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                $('#login').unbind('submit').submit();
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\My Laravel Startup\ndako\Modules/Admin\resources/views/auth/login.blade.php ENDPATH**/ ?>