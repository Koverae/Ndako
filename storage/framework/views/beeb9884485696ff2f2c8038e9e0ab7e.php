<div class="nav-item dropdown d-none d-md-flex me-3" x-data="{ hasNew: <?php if ((object) ('hasNewNotification') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('hasNewNotification'->value()); ?>')<?php echo e('hasNewNotification'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('hasNewNotification'); ?>')<?php endif; ?> }">
    <a class="px-0 nav-link" data-bs-toggle="offcanvas" href="#notificationOffcanvas" role="button" aria-controls="notificationOffcanvas">
        <i class="bi bi-bell" style="font-size: 16px;"></i>
        <!--[if BLOCK]><![endif]--><?php if($unreadCount > 0): ?>
            <span class="text-white badge font-weight-bold" style="background-color: #017E84;" x-bind:class="{ 'animate-pulse': hasNew }"><?php echo e($unreadCount); ?></span>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </a>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notification-received', () => {
            console.log('New notification triggered!');
        });
        Livewire.on('reset-animation', () => {
            // Handled by Alpine.js
        });
    });
</script>
<?php /**PATH D:\My Laravel Startup\ndako\Modules/App\resources/views/livewire/components/notification-trigger.blade.php ENDPATH**/ ?>