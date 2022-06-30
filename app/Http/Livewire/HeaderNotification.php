<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Livewire\Component;

class HeaderNotification extends Component
{
    public $hasUnreadNotifications;
    public $unreadNotifications;
    public $unreadNotificationsCount;

    public function mount(){
        $this->unreadNotifications = auth()->user()->unreadNotifications;
        $this->unreadNotificationsCount = $this->unreadNotifications->count();
        $this->hasUnreadNotifications =  $this->unreadNotificationsCount > 0 ? true: false;
    }

    public function viewNotification($id){
        dd($id);
        // Notification::find($id)->markAsRead();
    }
    public function render()
    {
        return view('livewire.header-notification');
    }
}
