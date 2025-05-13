<?php

namespace Modules\App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\App\Models\Notification\Notification;

class NotificationTrigger extends Component
{
    public $unreadCount = 0;
    public $hasNewNotification = false;
    public $userId;

    protected $listeners = [
        'echo-private:user.{userId},.App\\Events\\NotificationEvent' => 'refresh',
    ];

    public function mount()
    {
        $this->userId = Auth::user()->id;
        $this->refresh();
    }

    public function refresh()
    {
        $this->unreadCount = Notification::where('user_id', Auth::user()->id)
            ->whereNull('read_at')
            ->count();
        $this->hasNewNotification = true;
        $this->dispatch('notification-received');

        // Reset animation after 3 seconds
        $this->dispatch('reset-animation');
    }

    public function resetAnimation()
    {
        $this->hasNewNotification = false;
    }

    public function render()
    {
        return view('app::livewire.components.notification-trigger');
    }
}
