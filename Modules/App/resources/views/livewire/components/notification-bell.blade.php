<div class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas" aria-labelledby="offcanvasEndLabel" wire:ignore.self>
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
            <!-- All Notifications -->
            <div class="pt-0 tab-pane fade {{ $filter == 'all' ? 'show active' : '' }}" id="nav-home" role="tabpanel" aria-labelledby="nav-all-tab">
                <div class="list-group list-group-flush list-group-hoverable">
                    @forelse($notifications as $notification)
                        <div class="cursor-pointer list-group-item" wire:key="{{ $notification->id }}">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <a href="#" class="text-body d-block">{{ $notification->data['title'] ?? '' }}</a>
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        {{ $notification->data['message'] }}
                                    </div>
                                    <div class="mt-2 d-flex justify-content-between">
                                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
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
            <!-- Unread Notifications -->
            <div class="pt-0 tab-pane fade {{ $filter == 'unread' ? 'show active' : '' }}" id="nav-profile" role="tabpanel" aria-labelledby="nav-unread-tab">
                <div class="list-group list-group-flush list-group-hoverable">
                    @forelse($unreads as $notification)
                        <div
                            class="cursor-pointer list-group-item"
                            x-data="{ visible: true }"
                            x-show="visible"
                            x-transition
                            wire:key="{{ $notification->id }}"
                        >
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <a href="#" class="text-body d-block">{{ $notification->data['title'] ?? '' }}</a>
                                    <div class="d-block text-muted text-truncate mt-n1" title="{{ $notification->data['message'] }}">
                                        {{ $notification->data['message'] }}
                                    </div>
                                    <div class="mt-2 d-flex justify-content-between">
                                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                        <button
                                            @click="visible = false; $wire.markAsRead('{{ $notification->id }}', '{{ $filter }}')"
                                            class="text-end"
                                        >
                                            Mark as Read
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    No unread notifications available.
                                </div>
                                <div class="col-auto"></div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Read Notifications -->
            <div class="pt-0 tab-pane fade {{ $filter == 'read' ? 'show active' : '' }}" id="nav-contact" role="tabpanel" aria-labelledby="nav-read-tab">
                <div class="list-group list-group-flush list-group-hoverable">
                    @forelse($reads as $notification)
                        <div class="cursor-pointer list-group-item" wire:key="{{ $notification->id }}">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <a href="#" class="text-body d-block">{{ $notification->data['title'] ?? '' }}</a>
                                    <div class="d-block text-muted text-truncate mt-n1">
                                        {{ $notification->data['message'] }}
                                    </div>
                                    <div class="mt-2 d-flex justify-content-between">
                                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    No read notifications available.
                                </div>
                                <div class="col-auto"></div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
