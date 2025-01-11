<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'data'
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
    'data'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="mt-3 ps-3">
    <!--[if BLOCK]><![endif]--><?php if($value->label): ?>
    <span>
        <?php echo e($value->label); ?> :
    </span>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if($value->type == 'select'): ?>
    <select wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->model); ?>" class="k-input w-75">
        <option value=""></option>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $value->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </select>
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>

    <?php elseif($value->type == 'tag'): ?>
    <select wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->model); ?>" class="k-input w-75">
        <option value=""></option>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $value->data['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </select>
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>

    <span class="mt-3 d-block">
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $data['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a class="cursor-pointer badge rounded-pill k_web_settings_users">
            <?php echo e($text); ?>

            <i wire:click.prevent="" wire:confirm="Êtes-vous sûr de vouloir annuler l'invitation de ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Annuler l'invitation de"></i>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    </span>

    <?php elseif($value->type == 'textarea'): ?>
    <textarea wire:model="<?php echo e($value->model); ?>" class="border textearea k-input" placeholder="<?php echo e($value->placeholder); ?>" id="description" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
        <?php echo $value->model; ?>

    </textarea>
    <?php elseif($value->type == 'price'): ?>
    <div class="mt-3 ps-3">
        <!--[if BLOCK]><![endif]--><?php if($this->setting->default_currency_position == 'prefix'): ?>
        <span><?php echo e($this->setting->currency->symbol); ?></span>
        <input type="text" class="k-input">
        <?php else: ?>
        <input type="text" class="k-input">
        <span><?php echo e($this->setting->currency->symbol); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <?php else: ?>
    <input type="<?php echo e($value->type); ?>" wire:model="<?php echo e($value->model); ?>" class="w-auto k-input" placeholder="<?php echo e($value->placeholder); ?>" id="<?php echo e($value->model); ?>">
    <i class="cursor-pointer bi bi-arrow-right-short fw-bold"></i>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/blocks/boxes/input/simple.blade.php ENDPATH**/ ?>