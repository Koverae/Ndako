<div>
    <!--[if BLOCK]><![endif]--><?php if(isset($jsPath)): ?>
        <script><?php echo file_get_contents($jsPath); ?></script>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!--[if BLOCK]><![endif]--><?php if(isset($cssPath)): ?>
        <style><?php echo file_get_contents($cssPath); ?></style>
        <style>
            #modal-container { vertical-align: middle !important; }
        </style>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div
        x-data="LivewireUIModal()"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="closeModalOnEscape()"
        x-show="show"
        class="fixed inset-0 p-4"
        style="display:none; z-index: 9999999;"
    >
        <!-- Backdrop -->
        <div
            x-show="show"
            x-on:click="closeModalOnClickAway()"
            x-transition:enter="ease-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 will-change-[opacity]"
            aria-hidden="true"
        ></div>

        <!-- Wrapper: bottom sheet on mobile, centered on >= sm -->
        <div class="fixed inset-0 flex items-end sm:items-center sm:justify-center p-0 md:py-4 modal sm:p-4">
            <div
                x-show="show && showActiveComponent"

                x-transition:enter="ease-out duration-700"
                x-transition:enter-start="opacity-0 translate-y-10 sm:translate-y-6 sm:scale-[.98]"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-y-0 sm:scale-100"

                x-transition:leave="ease-in duration-600"
                x-transition:leave-start="opacity-100 translate-y-0 sm:translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-10 sm:translate-y-6 sm:scale-[.98]"

                x-trap.noscroll.inert="show && showActiveComponent"
                :class="[ modalWidth || '' ]"
                class="relative w-screen sm:w-full sm:max-w-lg md:max-w-2xl lg:max-w-3xl bg-white rounded-t-2xl sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden mx-auto will-change-[transform,opacity]"
                id="modal-container"
                role="dialog"
                aria-modal="true"
            >
                <!-- Scrollable content area -->
                <div class="max-h-[calc(100dvh-4rem)] sm:max-h-[80vh] overflow-y-auto">
                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $component): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div x-show.immediate="activeComponent == '<?php echo e($id); ?>'" x-ref="<?php echo e($id); ?>" wire:key="<?php echo e($id); ?>">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split($component['name'], $component['arguments']);

$__html = app('livewire')->mount($__name, $__params, $id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\My Laravel Startup\ndako\resources\views/vendor/wire-elements-modal/modal.blade.php ENDPATH**/ ?>