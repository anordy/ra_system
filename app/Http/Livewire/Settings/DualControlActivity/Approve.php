<?php

namespace App\Http\Livewire\Settings\DualControlActivity;

use App\Models\DualControl;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        if (!Gate::allows('setting-dual-control-activities-view')) {
            abort(403);
        }
        $req = DualControl::findOrFail($this->dual_control_id);

        if (!empty($req)) {

            if ($req->create_by_id == Auth::id()) {
                $this->alert('error', 'You are not allowed to complete this action', ['timer' => 8000]);
                return;
            }

            DB::beginTransaction();
            try {
                $req->update(['status' => 'approved']);
                $this->updateControllable($req, DualControl::APPROVE);
                $this->updateHistory($req->controllable_type, $req->controllable_type_id, $this->dual_control_id, 'approved', 'ok');
                DB::commit();
                $this->alert('success', 'Approved Successfully');
                return redirect()->route('system.dual-control-activities.index');
            } catch (\Throwable $exception) {
                DB::rollBack();
                Log::error($exception);
                $this->alert('error', 'Something went wrong. Please contact an admin');
                return redirect()->route('system.dual-control-activities.index');

            }
        }

    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null

        ]);
    }

    public function reject()
    {
        if (!Gate::allows('setting-dual-control-activities-view')) {
            abort(403);
        }

        $req = DualControl::findOrFail($this->dual_control_id);

        if (!empty($req)) {

            if ($req->create_by_id == Auth::id()) {
                $this->alert('error', 'You are not allowed to complete this action', ['timer' => 8000]);
                return;
            }

            DB::beginTransaction();
            try {
                $req->update(['status' => 'rejected']);
                $this->updateControllable($req, DualControl::REJECT);
                $this->updateHistory($req->controllable_type, $req->controllable_type_id, $this->dual_control_id, 'rejected', 'not ok');
                DB::commit();
                $this->alert('success', 'Rejected Successfully');
                return redirect()->route('system.dual-control-activities.index');
            } catch (\Throwable $exception) {
                DB::rollBack();
                Log::error($exception);
                $this->alert('error', 'Something went wrong. Please contact an admin');
                return redirect()->route('system.dual-control-activities.index');
            }
        }
    }

    public function render()
    {
        return view('livewire.settings.dual-control-activity.approve');
    }
}
