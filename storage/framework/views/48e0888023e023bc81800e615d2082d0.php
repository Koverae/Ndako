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

<div class="row gap-1 justify-content-md-center <?php echo e($this->currentStep == $value->step ? '' : 'd-none'); ?>">

    <div class="border shadow-sm col-12 col-md-8 card mt-2">
        <div class="card-header d-block">
            <h2 class="h2">Who’s on Your Team? 👥</h2>
            <p>Let’s make work easier, invite your staff or colleagues and start managing together.</p>

        </div>
        <div class="card-body">
            <form wire:submit.prevent="inviteMembers">
                <?php echo csrf_field(); ?>
                <div class="row">
                
                    <div class="mb-4 col-lg-6 col-md-12">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" wire:model="memberEmail" class="form-control rounded p-2 w-full">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['memberEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                
                    <div class="mb-4 col-lg-6 col-md-6">
                        <label class="block text-sm font-medium">Role</label>
                        <select wire:model="memberRole" id="memberRole" class="form-control">
                            <option value="">--- Choose ---</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>"><?php echo e($text); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['memberRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            
                <span wire:click="addMember" class="btn btn-primary">Add Member</span>
                
            
                <div class="row <?php echo e($this->teamMembers ? '' : 'd-none'); ?>">
                    <h3 class="form-label h3">
                        <?php echo e(__('Team Members')); ?>

                    </h3>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->teamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-1 mb-1 cursor-pointer col-12 col-lg-4" style="min-height: 100px;">
                            <div class="p-2 border rounded" style="min-height: 100px;">
                                <div class="d-flex justify-content-between">
                                    <h3 class="h3"><?php echo e($member['email']); ?></h3>
                                    <span class="text-end" wire:click="removeMember(<?php echo e($index); ?>)"><i class="fas fa-trash"></i></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span> <?php echo e($member['role']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
    

                <div class="d-flex justify-content-between">
                    <span>&nbsp;</span>
                    <div class="mt-3 wizard-navigation text-end">
                        <span class="btn cancel" wire:click="goToPreviousStep" <?php echo e($this->currentStep == 0 ? 'disabled' : ''); ?>><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                        <span class="btn cancel" wire:click="goToNextStep"><?php echo e(__('Skip')); ?></span>
                        <button type="submit" class="btn btn-primary go-next" <?php echo e($this->currentStep == count($this->steps()) - 1 ? 'disabled' : ''); ?>>
                            <span wire:loading.remove>Continue</span>
                            <span wire:loading>Loading...</span>
                        </button>
                    </div>
                </div>
                
            </form>

        </div>
    </div>
</div><?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/onboarding/invite-members.blade.php ENDPATH**/ ?>