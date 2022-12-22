<?php

namespace App\Http\Livewire\Settings\DualControlActivity;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Approve extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $dual_control_id;

    public function mount($dual_control_id)
    {
        $this->dual_control_id = decrypt($dual_control_id);
    }

    public function approve()
    {
        $req = DualControl::findOrFail($this->dual_control_id);
        if (!empty($req)) {
            DB::beginTransaction();
            try {
                $req->update(['status' => 'approved']);
                $this->updateControllable($req->controllable_type, $req->controllable_type_id, 1);
                DB::commit();
                $this->alert('success', 'Approved Successfully');
                return redirect()->route('settings.dual-control-activities.index');
            } catch (\Throwable $exception) {
                DB::rollBack();
                Log::error($exception->getMessage());
                $this->alert('error', 'Something went wrong. Please contact an admin');

            }
        }

    }

    public function reject()
    {
        $req = DualControl::findOrFail($this->dual_control_id);
        if (!empty($req)) {
            DB::beginTransaction();
            try {
                $req->update(['status' => 'rejected']);
                $this->updateControllable($req->controllable_type, $req->controllable_type_id, 2);
                DB::commit();
                $this->alert('success', 'Rejected Successfully');
                return redirect()->route('settings.dual-control-activities.index');
            } catch (\Throwable $exception) {
                DB::rollBack();
                Log::error($exception->getMessage());
                $this->alert('error', 'Something went wrong. Please contact an admin');
            }
        }
    }

    public function render()
    {
        return view('livewire.settings.dual-control-activity.approve');
    }
}
