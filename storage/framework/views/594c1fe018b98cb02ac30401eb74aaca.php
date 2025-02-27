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
            <h2 class="h2">Congratulations🎉! You've Made It! 🚀</h2>
            <p>Welcome aboard! Your journey starts here. Watch this quick tour to explore your new dashboard and make the most out of it.</p>
        </div>
        <div class="card-body">

            <!-- YouTube Video Embed -->
            <div class="aspect-w-16 aspect-h-100 rounded-lg shadow-lg overflow-hidden mb-6" style="height: 315px; ">
                <iframe class="w-100 h-100" src="<?php echo e($this->videoUrl); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                
            </div>

            <div class="d-flex justify-content-between">
                <span>&nbsp;</span>
                <div class="mt-3 wizard-navigation text-end">
                    <span class="btn cancel" wire:click="goToPreviousStep" <?php echo e($this->currentStep == 0 ? 'disabled' : ''); ?>><i class="fa fa-chevron-left" aria-hidden="true"></i></span>
                    
                    <span wire:click="goToDashboard" type="submit" class="btn btn-primary go-next">
                        <span>🚀 Go to Dashboard</span>
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/components/wizard/step-page/special/onboarding/final.blade.php ENDPATH**/ ?>