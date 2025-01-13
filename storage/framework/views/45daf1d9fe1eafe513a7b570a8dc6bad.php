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

    <div class="border shadow-sm col-12 col-md-8 card">
        <div class="card-body">
            <h3 class="h2">
                <?php echo e($this->availableRooms->count()); ?> Room(s) Available for:
            </h3>
            <span class="mb-3"><b><?php echo e($this->people); ?></b> People on <b><?php echo e(\Carbon\Carbon::parse($this->startDate) ->format('d M Y')); ?></b> to <b><?php echo e(\Carbon\Carbon::parse($this->endDate) ->format('d M Y')); ?></b></span>
            <hr class="mt-3 mb-3">

            <div class="gap-1 mb-3 d-flex justify-content-between align-items-center">
                <select class="w-50 form-control" wire:model="filterBy" id="">
                    <option value="price"><?php echo e(__('Price')); ?></option>
                    <option value="capacity"><?php echo e(__('Capacity')); ?></option>
                    <option value="number"><?php echo e(__('Number')); ?></option>
                </select>
                <select class="w-50 form-control" wire:model="sortOrder" id="">
                    <option value="asc"><?php echo e(__('Ascending')); ?></option>
                    <option value="desc"><?php echo e(__('Descending')); ?></option>
                </select>
                <span class="gap-2 text-end btn btn-primary"><?php echo e(__('Search')); ?> <i class="fas fa-search-plus"></i></span>
            </div>

            <!-- Available Rooms -->
            <div class="row">
                <!-- Available Rooms Loop -->
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->availableRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-3 col-12 col-md-12">
                        <div class="card <?php if($this->selectedRoom): ?> <?php echo e($this->selectedRoom->id == $room->id ? 'active-pick' : ''); ?> <?php endif; ?>">
                            <div class="card-body row">
                                <div class="col-12 col-lg-7">
                                    <span class="text-muted fw-bolder"><?php echo e($room->capacity); ?> <?php echo e(__('People')); ?> <i class="fas fa-users"></i></span>
                                    <h5 class="mb-0 card-title"><?php echo e($room->name); ?> ~ <?php echo e($room->unitType->name); ?></h5>
                                    <span class="mb-3 text-muted"><?php echo e(format_currency($room->unitType->price->price)); ?> / <?php echo e($room->unitType->price->lease->name ?? ''); ?></span>
                                    <p class="mt-2">
                                        At sunt unde atque quod. Fuga atque iste ea ut nesciunt ut tenetur sed.
                                        Eligendi dolorem quas adipisci nisi distinctio est suscipit.
                                        Provident blanditiis laudantium voluptas eveniet.
                                    </p>
                                    <p class="card-text">
                                        <i class="fas fa-bed"></i> <?php echo e($room->beds); ?> Beds <br>
                                        <i class="fas fa-bath"></i> <?php echo e($room->bathrooms); ?> Bathrooms <br>
                                        <i class="fas fa-ruler-combined"></i> <?php echo e($room->area); ?> sq ft
                                    </p>
                                    <button class="mt-3 btn w-100" wire:click="pickRoom('<?php echo e($room->id); ?>')" <?php echo e($this->startDate == '' && $this->endDate == '' ? 'disabled' : ""); ?>  <?php if($this->selectedRoom): ?> <?php echo e($this->selectedRoom->id == $room->id ? 'disabled' : ''); ?> <?php endif; ?>><?php echo e(__('Choose')); ?></button>
                                </div>
                                <div class="col-md-5 d-none d-lg-block">
                                    <img src="<?php echo e(asset('assets/images/test/test-'. $room->id.'.jpg')); ?>" width="300px" height="auto" alt="<?php echo e($room->name); ?>" class="image">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <!-- Available Rooms ENd -->
        </div>
    </div>
    <!--[if BLOCK]><![endif]--><?php if($this->guest): ?>
    <div class="shadow-sm col-12 col-md-3 card" style="max-height: 450px;">
        <div class="card-body">
            <img src="<?php echo e($this->guest->avatar ? Storage::url('avatars/' . $this->guest->avatar) . '?v=' . time() : asset('assets/images/default/user.png')); ?>" alt="<?php echo e($this->guest->name); ?>" class="img img-fluid" height="350px" width="350px">
            <div class="mt-2">
                <span><i class="fas fa-user-md"></i> <?php echo e($this->guest->name); ?></span> <br>
                <span><i class="bi bi-envelope"></i> <?php echo e($this->guest->email); ?></span> <br>
                <span><i class="bi bi-phone"></i> <?php echo e($this->guest->phone); ?></span> <br>
                <span><i class="bi bi-geo"></i> <?php echo e(__('Qwetu Parklands')); ?></span> <br>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH D:\My Laravel Startup\koverae-saas\Modules/App\resources/views/components/wizard/step-page/special/booking/choose-room.blade.php ENDPATH**/ ?>