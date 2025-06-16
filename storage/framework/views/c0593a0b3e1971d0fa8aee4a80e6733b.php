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

<div class="setting_block">
    <div class="gap-5 p-3 app_settings_header d-md-flex d-block">
        <h3>Restaurant</h3>
        <!-- Box Input -->
        <div class="gap-4 d-flex">
            <select id="Fiscal Localization" class="k-input">
                <option value=""></option>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->restaurants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>" <?php echo e($this->pos->id == $value ? 'selected' : ''); ?>><?php echo e($text); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </select>
            <span class="cursor-pointer text-primary font-weight-bold" style="font-weight: 600;"><i class="bi bi-plus-circle"></i> New Front Desk</span>
        </div>
        <!-- Box Input End -->
    </div>
    <div class="mt-2 alert alert-warning">
        A session is currently opened for this Front Desk. Some settings can only be changed after the session is closed.
        <span class="cursor-pointer text-primary" style="font-weight: 600;" wire:click="closeSession">Click here to close session</span>
    </div>
    
</div>

<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/blocks/templates/pos-header.blade.php ENDPATH**/ ?>