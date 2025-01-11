<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value'
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
    'value'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<?php
    $booking = \Modules\ChannelManager\Models\Booking\Booking::find($value);
    $date1 = \Carbon\Carbon::parse($booking->check_in);
    $date2 = \Carbon\Carbon::parse($booking->check_out);
    $daysDifference = $date1->diffInDays($date2);
?>
<div>
    <?php echo e($daysDifference); ?> <?php echo e(__('Days')); ?>

</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/table/column/special/booking/booking-days.blade.php ENDPATH**/ ?>