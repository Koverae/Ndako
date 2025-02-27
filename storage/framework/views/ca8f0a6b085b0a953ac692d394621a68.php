<div>
    <!--[if BLOCK]><![endif]--><?php if(isset($jsPath)): ?>
        <script><?php echo file_get_contents($jsPath); ?></script>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <!--[if BLOCK]><![endif]--><?php if(isset($cssPath)): ?>
        <style><?php echo file_get_contents($cssPath); ?></style>
        <style>
            #modal-container {
            vertical-align: middle !important;
            }
        </style>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <div
            x-data="LivewireUIModal()"
            x-on:close.stop="setShowPropertyTo(false)"
            x-on:keydown.escape.window="closeModalOnEscape()"
            x-show="show"
            class="fixed inset-0 overflow-y-auto"
            style="display: none; z-index: 100000;"
    >
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-10 text-center modal sm:block sm:p-0">
            <div
                    x-show="show"
                    x-on:click="closeModalOnClickAway()"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-all transform"
            >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                    x-show="show && showActiveComponent"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-bind:class="modalWidth"
                    class="inline-block w-full overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl modal-dialog modal-lg modal-fullscreen-md-down md:w-1/4 sm:my-8 sm:align-middle sm:w-full"
                    id="modal-container"
                    x-trap.noscroll.inert="show && showActiveComponent"
                    aria-modal="true"
            >
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

<?php /**PATH D:\My Laravel Startup\ndako\resources\views/vendor/wire-elements-modal/modal.blade.php ENDPATH**/ ?>