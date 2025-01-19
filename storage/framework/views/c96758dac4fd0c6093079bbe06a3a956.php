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

<div class="w-auto pt-2 k_field_widget k_field_text k_read_only modify ps-3 text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Cette valeur est appliquée par défaut sur tous les nouveaux produits. Ceci peut être modifié dans la fiche du produit.">
    <!-- What is ordered -->
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $value->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="form-check k_radio_item">
        <input type="<?php echo e($value->type); ?>" class="form-check-input" name="<?php echo e($value->model); ?>" wire:model.blur="<?php echo e($value->model); ?>" id="<?php echo e($option['value']); ?>" value="<?php echo e($option['value']); ?>"/>
        <label class="form-check-label k_form_label" for="<?php echo e($option['value']); ?>">
                <?php echo e($option['label']); ?>

        </label>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</div>

<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/blocks/boxes/input/radio.blade.php ENDPATH**/ ?>