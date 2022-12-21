<?php

namespace App\Http\Livewire\Notification;

use App\Models\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NotificationsTable extends Component
{
    use LivewireAlert;

    public $notifications;
    public $selectAll = false;
    public $selectedItems = [];

    public function mount()
    {
        $this->notifications =  Notification::where('notifiable_type', get_class(auth()->user()))
            ->where('notifiable_id', auth()->id())
            ->latest()
            ->get();
    }

    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;
    }

    public function deleteSelected()
    {
        $selectedIds = array_keys($this->selectedItems, true);
        Notification::destroy($selectedIds);
    }

    public function read($notification){
        $data = $notification['data'];
        return !empty($data['hrefParameters']) ? redirect()->route($data['href'], encrypt($data['hrefParameters'])) : redirect()->route($data['href']);
    }

    public function render()
    {
        return view('livewire.notifications.notifications-table');
    }
}
