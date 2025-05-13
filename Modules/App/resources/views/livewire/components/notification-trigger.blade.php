<div class="nav-item dropdown d-none d-md-flex me-3" x-data="{ hasNew: @entangle('hasNewNotification') }">
    <a class="px-0 nav-link" data-bs-toggle="offcanvas" href="#notificationOffcanvas" role="button" aria-controls="notificationOffcanvas">
        <i class="bi bi-bell" style="font-size: 16px;"></i>
        @if($unreadCount > 0)
            <span class="text-white badge font-weight-bold" style="background-color: #017E84;" x-bind:class="{ 'animate-pulse': hasNew }">{{ $unreadCount }}</span>
        @endif
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
