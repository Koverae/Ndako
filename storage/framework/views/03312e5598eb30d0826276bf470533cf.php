<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',

]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'value',

]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="k_settings_box col-12 col-lg-12 k_searchable_setting" style="width: 100%;">

    <!-- Right pane -->
    <div class="k_setting_right_pane" style="width: 100%;">
        <div class="mt-1" style="width: 100%;">

            <!--[if BLOCK]><![endif]--><?php if(current_company()->team->subscription('main')): ?>
                <div class="mb-3 d-flex align-items-center">
                    <div class="me-3">
                        <img src="<?php echo e(asset('assets/images/logo/logo-circle-white.png')); ?>" style="height: 18px; width: 18px;" alt="">
                    </div>
                    <div>
                        <h1 class="mb-0 h2"><?php echo e(ucfirst(current_company()->team->subscription('main')->plan->name)); ?> Plan</h1>
                        <small class="text-muted"><?php echo e(ucfirst(current_company()->team->subscription('main')->status)); ?></small>
                    </div>
                </div>

                <ul class="list-group list-group-flush">
                    <!--[if BLOCK]><![endif]--><?php if(current_company()->team->subscription('main')->ends_at && current_company()->team->subscription('main')->starts_at && !current_company()->team->subscription('main')->cancels_at): ?>
                        <span>Your team is subscribed since <b><?php echo e(current_company()->team->subscription('main')->starts_at->diffForHumans()); ?></b></span>
                        <!--[if BLOCK]><![endif]--><?php if(now()->lessThan(current_company()->team->subscription('main')->ends_at)): ?>
                          <span>Next billing in <b><?php echo e(getRemainingSubDays()); ?></b>, on <b><?php echo e(\Carbon\Carbon::parse(current_company()->team->subscription('main')->ends_at)->format('d M Y')); ?></b></span>
                        <?php elseif(current_company()->team->subscription('main')->ends_at && now()->greaterThan(current_company()->team->subscription('main')->ends_at)): ?>
                        <span>
                            <strong>Your subscription has expired!</strong> Renew to continue using our Ndako.
                        </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <span>Your subscription code is <b><?php echo e(current_company()->team->subscription('main')->paystack_subscription_code ?? 'N/A'); ?></b></span>
                    <?php elseif(current_company()->team->subscription('main')->isOnTrial()): ?>

                    <span>
                        ⏳ Your trial will expire in <?php echo e(getRemainingTrialDays()); ?>!
                        <a href="<?php echo e(route('subscribe')); ?>"><strong>Upgrade now</strong></a> to continue managing your properties effortlessly with Ndako’s full suite of tools.
                    </span>
                    <?php elseif(current_company()->team->subscription('main')->cancels_at && !current_company()->team->subscription('main')->canceled_at): ?>

                    <span>Your subscription was canceled <b><?php echo e(current_company()->team->subscription('main')->cancels_at->diffForHumans()); ?></b>.</span>
                    <span>Access remains until <b><?php echo e(\Carbon\Carbon::parse(current_company()->team->subscription('main')->ends_at)->format('d M Y')); ?></b>.</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </ul>

                <div class="mt-2">
                    <a href="<?php echo e(route('subscribe')); ?>" class="gap-2 text-white btn btn-primary text-uppercase" >
                        <i class="bi bi-arrow-up-right-circle"></i> <?php echo e(current_company()->team->subscription('main')->isOnTrial() ? "Upgrade Now" : "Renew"); ?>

                    </a>
                    <span wire:click="cancelSubscription" wire:confirm='Do you really want to cancel your subscription?' class="btn btn-danger gap-2 text-uppercase <?php echo e(current_company()->team->subscription('main')->cancels_at ? 'd-none' : ''); ?>  <?php echo e(current_company()->team->subscription('main')->isActive() ? '' : 'd-none'); ?>">
                        <i class="bi bi-x-circle"></i> Cancel Subscription
                    </span>
                </div>

            <?php else: ?>
                <p>No active subscription found.</p>
                <a href="<?php echo e(route('subscription.plans')); ?>" class="btn btn-primary">
                    <i class="bi bi-box-seam"></i> Choose a Plan
                </a>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        </div>
    </div>

</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/blocks/boxes/template/subs.blade.php ENDPATH**/ ?>