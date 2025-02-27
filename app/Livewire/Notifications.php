<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Notifications extends Component
{
    public $notifications, $unreads = [], $reads = [];
    public $filter = 'all';

    public function mount()
    {
        $this->fetchNotifications();
    }

    #[On('fetch-notifications')]
    public function fetchNotifications()
    {
        $query = User::find(auth()->user()->id)->notifications();

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $this->notifications = $query->get();
    }

    public function markAsRead($notificationId)
    {
        $notification = User::find(auth()->user()->id)->notifications()->find($notificationId);
        $notification->markAsRead();

        $this->fetchNotifications();
    }

    public function render()
    {
        return view('livewire.notifications');
    }
}
