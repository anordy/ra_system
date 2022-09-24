<?php

namespace App\Http\Livewire;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class NotificationHeader extends Component
{
    public $hasUnreadNotifications;
    public $unreadNotifications;
    public $unreadNotificationsCount;

    public function mount(){
        // $this->unreadNotifications = auth()->user()->unreadNotifications()->latest()->get();
        // dd($this->unreadNotifications);
        $this->unreadNotifications = Notification::where('notifiable_id', auth()->user()->id)->whereNull('read_at')->latest()->get();
        // dd($this->unreadNotifications);
        $this->unreadNotificationsCount = $this->unreadNotifications->count();
        $this->hasUnreadNotifications =  $this->unreadNotificationsCount > 0 ? true: false;
    }

    public function render()
    {
        return view('livewire.notification-header');
    }
}
