<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['url']));

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

foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<table cellpadding="0" cellspacing="0">
    <tr>
        <td class="py-lg">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <a href="<?php echo e($url); ?>">
                            <img src="<?php echo e(public_path('assets/images/logo/logo-black.png')); ?>" width="116" height="34" alt="" />
                        </a>
                    </td>
                    
                </tr>
            </table>
        </td>
    </tr>
</table><?php /**PATH D:\My Laravel Startup\ndako\resources\views/vendor/mail/html/header.blade.php ENDPATH**/ ?>