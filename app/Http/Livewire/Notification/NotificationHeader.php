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
        $this->unreadNotifications = Notification::where('seen',0)
            ->where('notifiable_type', get_class(auth()->user()))
            ->where('notifiable_id', auth()->user()->id)
            ->latest()
            ->get();
        $this->unreadNotificationsCount = $this->unreadNotifications->count();
        $this->hasUnreadNotifications = $this->unreadNotificationsCount > 0;
    }

    public function render()
    {
        return view('livewire.notification-header');
    }
}
