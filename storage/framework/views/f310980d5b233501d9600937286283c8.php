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

<div class="d-flex" style="margin-bottom: 8px;">
    <!-- Input Label -->
    <!--[if BLOCK]><![endif]--><?php if($value->label): ?>
    <div class="k_cell k_wrap_label flex-grow-1 flex-sm-grow-0 text-break text-900">
        <label class="k_form_label">
            <?php echo e($value->label); ?>

            <!--[if BLOCK]><![endif]--><?php if($value->help): ?>
                <sup><i class="bi bi-question-circle-fill" style="color: #0E6163" data-toggle="tooltip" data-placement="top" title="<?php echo e($value->help); ?>"></i></sup>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </label>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <!-- Input Form -->
    <div class="k_cell k_wrap_input flex-grow-1 <?php echo e($value->type == 'tag' ? 'mb-4' : ''); ?> <?php echo e($value->type == 'textarea' ? 'mb-4' : ''); ?>">

        <!--[if BLOCK]><![endif]--><?php if($value->type == 'select'): ?>
        <select wire:model.live="<?php echo e($value->model); ?>" id="<?php echo e($value->model); ?>" class="k-input" <?php echo e($value->disabled ? 'disabled' : ''); ?> <?php echo e($this->blocked ? 'disabled' : ''); ?>>
            <option value=""></option>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $value->data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>"><?php echo e($text); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </select>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$value->model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        
        

        <?php elseif($value->type == 'tag'): ?>
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
                <a class="cursor-pointer badge rounded-pill k_web_settings_users" style="color: #0E6163;">
                    <?php echo e($text); ?>

                    <!--[if BLOCK]><![endif]--><?php if($data['delete']): ?>
                    <i wire:click.prevent="<?php echo e($data['delete']); ?>('<?php echo e($value); ?>')" wire:confirm="Are you sure you want to remove <?php echo e($text); ?> ?" class="bi bi-x cancelled_icon" data-bs-toggle="tooltip" data-bs-placement="right" title="Remove <?php echo e($text); ?>"></i>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </span>
        </div>
        <br>
        <?php elseif($value->type == 'price'): ?>

            <!--[if BLOCK]><![endif]--><?php if(settings()->default_currency_position == 'prefix'): ?>
            <span style="margin-right: 10px; "><?php echo e(settings()->currency->symbol); ?></span>
            <input type="text" class="k-input" wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->key); ?>" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
            <?php else: ?>
            <input type="text" class="k-input" wire:model="<?php echo e($value->model); ?>" id="<?php echo e($value->key); ?>" <?php echo e($this->blocked ? 'disabled' : ''); ?>>
            <span><?php echo e(settings()->currency->symbol); ?></span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php elseif($value->type == 'textarea'): ?>
        <textarea wire:model="<?php echo e($value->model); ?>" class="p-0 m-0 textearea k-input" placeholder="<?php echo e($value->placeholder); ?>" id="description" <?php echo e($this->blocked ? 'disabled' : ''); ?>>

        </textarea>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$value->model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>

        <input type="<?php echo e($value->type); ?>" style="margin-left: 5px;" wire:model="<?php echo e($value->model); ?>" wire:change="calculatePrice" class="p-0 k-input" placeholder="<?php echo e($value->placeholder); ?>" id="date_0" <?php echo e($value->disabled ? 'disabled' : ''); ?> <?php echo e($this->blocked ? 'disabled' : ''); ?>>
        <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$value->model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-danger"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>
</div>

<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/form/input/simple.blade.php ENDPATH**/ ?>