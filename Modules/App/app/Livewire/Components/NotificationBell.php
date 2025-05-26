<?php

namespace Modules\App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\App\Models\Notification\Notification;

class NotificationBell extends Component
{
    public $filter = 'all';
    public $notifications = [], $unreads = [], $reads = [];
    public $unreadCount = 0, $userId;

    protected $listeners = [
        'echo-private:user.{userId},.App\\Events\\NotificationEvent' => 'refreshNotifications',
    ];

    public function mount()
    {
        $this->userId = Auth::user()->id;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $query = Notification::where('user_id', Auth::user()->id)->latest();

        // if ($this->filter === 'unread') {
        //     $query->whereNull('read_at');
        // } elseif ($this->filter === 'read') {
        //     $query->whereNotNull('read_at');
        // }

        $this->notifications = Notification::where('user_id', Auth::user()->id)->latest()->take(20)->get();
        $this->reads = Notification::where('user_id', Auth::user()->id)->latest()->whereNotNull('read_at')->take(20)->get();
        $this->unreads = Notification::where('user_id', Auth::user()->id)->latest()->whereNull('read_at')->take(20)->get();
        $this->unreadCount = Notification::where('user_id', Auth::user()->id)
            ->whereNull('read_at')
            ->count();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadNotifications();
    }

    public function markAsRead($notificationId, $filter = "all")
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->markAsRead();
        $this->filter = $filter;

        $this->loadNotifications();
    }

    public function refreshNotifications($event)
    {
        $this->loadNotifications();
        $this->dispatch('notification-received');
    }

    public function render()
    {
        return view('app::livewire.components.notification-bell');
    }
}
