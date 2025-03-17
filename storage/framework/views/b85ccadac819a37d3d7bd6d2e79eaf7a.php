
<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
      <h1 class="offcanvas-title h1" id="offcanvasEndLabel"><?php echo e(__('Notifications')); ?></h1>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="p-0 offcanvas-body">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="border-0 nav-link <?php echo e($filter == 'all' ? 'active' : ''); ?>" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All</button>
            <button class="border-0 nav-link <?php echo e($filter == 'unread' ? 'active' : ''); ?>" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Unreads</button>
            <button class="border-0 nav-link <?php echo e($filter == 'read' ? 'active' : ''); ?>" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Reads</button>
        </div>
        <div class="tab-content">
            <!-- ALL -->
            <div class="pt-0 tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-notif">
                <div class="list-group list-group-flush list-group-hoverable">

                    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="cursor-pointer list-group-item">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block"><?php echo e($notification->data['title'] ?? ''); ?></a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    <?php echo e($notification->data['message']); ?>

                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <small class="text-gray-500"><?php echo e($notification->created_at); ?></small>
                                    <!--[if BLOCK]><![endif]--><?php if(is_null($notification->read_at)): ?>
                                        <button class="text-end" wire:click="markAsRead('<?php echo e($notification->id); ?>')">Mark as Read</button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                No notifications available.
                            </div>
                            <div class="col-auto"></div>
                        </div>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <!-- CHATS -->
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-notif">
                <div class="list-group list-group-flush list-group-hoverable">

                </div>
            </div>
            <!-- CHANNEL -->
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-notif">
                <div class="list-group list-group-flush list-group-hoverable">

                </div>
            </div>
        </div>

    </div>
</div>




<?php /**PATH D:\My Laravel Startup\ndako\resources\views/livewire/notifications.blade.php ENDPATH**/ ?>