<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class HeaderNotification extends Component
{
    public $hasUnreadNotifications;
    public $unreadNotifications;
    public $unreadNotificationsCount;

    public function mount(){
        $this->unreadNotifications = auth()->user()->unreadNotifications()->latest()->get();
        $this->unreadNotificationsCount = $this->unreadNotifications->count();
        $this->hasUnreadNotifications =  $this->unreadNotificationsCount > 0 ? true: false;
    }

    public function viewNotification($not){
        $notification = Notification::find($not['id']);
        $notification->read_at = Carbon::now();
        $notification->save();
        $this->resetCount();
    }

    public function resetCount(){
        $this->unreadNotifications = auth()->user()->unreadNotifications;
        $this->unreadNotificationsCount = $this->unreadNotifications->count();
        $this->hasUnreadNotifications =  $this->unreadNotificationsCount > 0 ? true: false;
    }

    public function render()
    {
        return view('livewire.header-notification');
    }
}
