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

        <div class="mb-4 d-block w-100">
            <div class="mb-1 d-flex col-12">
                <select wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->model); ?>" class="k-input" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
                    <option value=""></option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $value->data['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php if($data['action']): ?>
                <i class="cursor-pointer bi bi-plus-circle fw-bold" wire:click="<?php echo e($data['action']); ?>"></i>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <span class="col-12">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $data['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $journal = \Modules\RevenueManager\Models\Accounting\Journal::find($text);
                ?>
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    <?php echo e($journal->name); ?>

                    <!--[if BLOCK]><![endif]--><?php if($data['delete']): ?>
                    <i wire:click.prevent="<?php echo e($data['delete']); ?>('<?php echo e($journal->id); ?>')" wire:confirm="Are you sure you want to remove <?php echo e($journal->name); ?> ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove <?php echo e($journal->name); ?>"></i>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </span>
        </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/blocks/boxes/input/tag/journal-payment.blade.php ENDPATH**/ ?>