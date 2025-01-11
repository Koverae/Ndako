<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'value',
    'model',
    'id'
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
    'model',
    'id'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-1 col-sm-3">
    <a class="card" wire:navigate href="<?php echo e($this->showRoute($id)); ?>">
        <div class="d-flex">
            <img src="<?php echo e($model['avatar'] ? Storage::url('avatars/' . $model['avatar']) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="<?php echo e($model['avatar']); ?>" class="img img-fluid" height="120px" width="120px">
            <div class="p-2 card-body text-truncate">
                <h5 class="mb-2 card-title"> <?php echo e($model[$value->title]); ?> </h5>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="mb-1 cursor-pointer text-truncate w-100"><?php echo e($model[$data]); ?></span> <br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                
            </div>
        </div>
    </a>
</div><?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/table/card/simple.blade.php ENDPATH**/ ?>