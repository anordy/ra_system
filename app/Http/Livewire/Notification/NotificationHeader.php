<?php

namespace App\Http\Livewire\Notification;

use App\Models\Notification;
use Livewire\Component;

class NotificationHeader extends Component
{
    public $hasUnreadNotifications;
    public $unreadNotifications;
    public $unreadNotificationsCount;

    public function mount()
    {
        $this->unreadNotifications = null;
        $this->unreadNotificationsCount = 0;
        $this->hasUnreadNotifications = 0;
    }

    public function render()
    {
        return view('livewire.notification-header');
    }
}
