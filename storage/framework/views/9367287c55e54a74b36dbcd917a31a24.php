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

<?php if(current_company()->team->subscription('main')->isOnTrial()): ?>
<div class="setting_block">
    <div class="mt-2 alert alert-warning">
        <?php
            $subscription = current_company()->team->subscription('main');
            $daysLeft = $subscription->getTrialPeriodRemainingUsageIn('day');
            $hoursLeft = $subscription->getTrialPeriodRemainingUsageIn('hour');
        ?>
    
        <p>⏳ Your trial will expire in 
            <?php if($daysLeft >= 1): ?>
                <?php echo e($daysLeft); ?> days
            <?php else: ?>
                <?php echo e($hoursLeft); ?> hours
            <?php endif; ?>
            ! <a href="#" target="__blank"><strong>Register your subscription</strong></a> or 
            <a href="#" target="__blank"><strong>buy a subscription</strong></a>
        </p>
    </div>
</div>
<?php endif; ?>

<?php /**PATH D:\My Laravel Startup\ndako\Modules\App\resources\views\components\blocks\templates\subscription-reminder.blade.php ENDPATH**/ ?>