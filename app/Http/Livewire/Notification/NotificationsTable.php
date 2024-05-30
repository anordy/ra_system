<?php

namespace App\Http\Livewire\Notification;

use App\Models\Notification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationsTable extends Component
{
    use CustomAlert;
    use WithPagination;

    public $selectAll = false;
    public $selectedItems = [];
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        Notification::where('notifiable_type', get_class(auth()->user()))
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->increment('seen');
    }

    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;
    }

    public function deleteSelected()
    {
        $selectedIds = array_keys($this->selectedItems, true);
        Notification::destroy($selectedIds);
        return redirect()->route('notifications')->with('success', 'Notifications Deleted successfully');
    }

    public function read($notification)
    {
        $notification = Notification::find($notification['id']);
        if (is_null($notification)) {
            abort(404);
        }
        $data = $notification->data;
        try {
            DB::beginTransaction();
            $notification->read_at = now();
            $notification->save();
            DB::commit();
            return redirect()->route($data->href, !empty($data->hrefParameters) ? encrypt($data->hrefParameters) : null);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {

        $notifications = Notification::where('notifiable_type', get_class(auth()->user()))
            ->where('notifiable_id', auth()->id())
            ->whereNull('read_at')
            ->latest()
            ->paginate(10);
        return view('livewire.notifications.notifications-table', [
            'notifications' => $notifications
        ]);
    }
}
