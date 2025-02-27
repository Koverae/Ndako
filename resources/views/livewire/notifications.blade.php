
<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
      <h1 class="offcanvas-title h1" id="offcanvasEndLabel">{{ __('Notifications') }}</h1>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="p-0 offcanvas-body">
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="border-0 nav-link {{ $filter == 'all' ? 'active' : '' }}" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All</button>
            <button class="border-0 nav-link {{ $filter == 'unread' ? 'active' : '' }}" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Unreads</button>
            <button class="border-0 nav-link {{ $filter == 'read' ? 'active' : '' }}" id="nav-notif" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Reads</button>
        </div>
        <div class="tab-content">
            <!-- ALL -->
            <div class="pt-0 tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-notif">
                <div class="list-group list-group-flush list-group-hoverable">

                    @forelse($notifications as $notification)
                    <div class="cursor-pointer list-group-item">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">{{ $notification->data['title'] ?? '' }}</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    {{ $notification->data['message'] }}
                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <small class="text-gray-500">{{ $notification->created_at }}</small>
                                    @if(is_null($notification->read_at))
                                        <button class="text-end" wire:click="markAsRead('{{ $notification->id }}')">Mark as Read</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col text-truncate">
                                No notifications available.
                            </div>
                            <div class="col-auto"></div>
                        </div>
                    </div>
                    @endforelse
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



{{-- <div>
    <div class="flex items-center justify-between">
        <button wire:click="$set('filter', 'all')">All</button>
        <button wire:click="$set('filter', 'alert')">Alerts</button>
        <button wire:click="$set('filter', 'chat')">Chats</button>
    </div>

    <ul>
        @forelse($notifications as $notification)
            <li class="p-2 border-b">
                {{ $notification['data']['message'] }}
                <small class="text-gray-500">{{ $notification['created_at'] }}</small>
            </li>
        @empty
            <li>No notifications yet.</li>
        @endforelse
    </ul>
</div> --}}
