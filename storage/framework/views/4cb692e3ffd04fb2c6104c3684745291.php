<?php $__env->startSection('title', "Import File"); ?>

<?php $__env->startSection('styles'); ?>
    <style>
    </style>
<?php $__env->stopSection(); ?>
<!-- Control Panel -->
<?php $__env->startSection('control-panel'); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('app::navbar.control-panel.import-panel', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-1008576848-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<?php $__env->stopSection(); ?>

<!-- Page Content -->
<section class="w-100">
    <div class="empty k_nocontent_help h-100">
        <img src="<?php echo e(asset('assets/images/illustrations/file-icon.svg')); ?>"style="height: 200px" alt="">
        <p class="empty-title"><?php echo e(__('Drop or upload a file to import')); ?></p>
        <p class="empty-subtitle"><?php echo e(__('Excel files are recommended as formatting is automatic. But, you can also use .csv files')); ?></p>

        <span wire:click="downloadTemplate" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2">
            <i class="fas fa-download"></i> <?php echo e(__("Import Template for $modelName")); ?>

        </span>

        <form wire:submit.prevent="import" class="p-2 mb-2 mt-3">

            <div class="file-upload-container" id="file-upload-container">
                <label for="file-upload" class="file-upload-label">
                    <i class="fas fa-cloud-upload-alt"></i> Click or drag to upload
                </label>
                <input type="file" wire:model="file" id="file-upload" class="file-upload-input">
                <div id="file-name" class="file-name"></div>
                <div wire:loading wire:target="file" class="loader-progress"></div>
            </div>
            <br />
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

            <div wire:loading wire:target="file" class="text-sm text-gray-500">
                <div class="loader-dots"></div> Previewing data
            </div>

            <!--[if BLOCK]><![endif]--><?php if(!empty($previewData)): ?>
            <div class="preview-table mt-3 mb-3">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><?php echo e(__('Data Preview')); ?></h3>
            
                <div class="overflow-x-auto rounded-lg border shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 uppercase tracking-wider text-xs text-gray-600">
                            <tr>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = array_keys($previewData[0]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th class="px-4 py-3 whitespace-nowrap"><?php echo e($header); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $previewData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="px-4 py-2 whitespace-nowrap"><?php echo e($cell); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <button type="submit" class="btn btn-outline-primary k_form_button_create gap-2 d-flex fs-3 mt-2 w-100">Import</button>
            <div wire:loading wire:target="file" class="text-sm text-gray-500 p-2">
                <div class="loader-ball"></div>
            </div>
        </form>

        <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
            <div class="mt-4 text-green-600"><?php echo e(session('message')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <?php if(session()->has('error')): ?>
            <div class="mt-4 text-red-600"><?php echo e(session('error')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <p>Need Help? <a href="#" style="color: #0E6163;">Import FAQ</a></p>

    </div>
</section>
<!-- Page Content -->
    <script>
        // Handle file selection
        document.getElementById('file-upload').addEventListener('change', function(event) {
            const fileName = event.target.files[0] ? event.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });

        // Handle drag events
        const fileUploadContainer = document.getElementById('file-upload-container');

        fileUploadContainer.addEventListener('dragover', function(event) {
            event.preventDefault();  // Necessary to allow dropping
            fileUploadContainer.style.backgroundColor = '#e9f1ff';  // Change color while dragging
            fileUploadContainer.style.borderColor = '#E6F2F3';
        });

        fileUploadContainer.addEventListener('dragleave', function(event) {
            event.preventDefault();
            fileUploadContainer.style.backgroundColor = '#f7f7f7';  // Reset color when dragging leaves
            fileUploadContainer.style.borderColor = '#0E6163';
        });

        fileUploadContainer.addEventListener('drop', function(event) {
            event.preventDefault();  // Prevent default behavior (e.g., opening the file)
            fileUploadContainer.style.backgroundColor = '#d1e7ff';  // Color change on drop
            fileUploadContainer.style.borderColor = '#E6F2F3';

            const files = event.dataTransfer.files;
            if (files.length > 0) {
                // Set the file input's files to the dropped files
                document.getElementById('file-upload').files = files;
                document.getElementById('file-name').textContent = files[0].name;  // Show the file name
            }
        });

        // Handle click (label click still works as before)
        document.getElementById('file-upload').addEventListener('click', function() {
            fileUploadContainer.style.backgroundColor = '#f7f7f7';  // Reset color when clicking
            fileUploadContainer.style.borderColor = '#0E6163';
        });

    </script>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/import-file.blade.php ENDPATH**/ ?>