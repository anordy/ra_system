<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;

    public $modelId;
    public $modelName;
    public $comments;
    public $duration_id;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
    }


    public function approve($transition)
    {
        $this->validate(['comments' => 'required', 'duration_id' => 'required'], ['duration_id.required' => 'You must select License Duration to approve']);
        
        DB::beginTransaction();

        try {
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_PAYMENT])->id;
            $this->subject->dl_license_duration_id = $this->duration_id;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $bill = $this->subject->generateBill();
            $response = ZmCore::sendBill($bill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                session()->flash('success', 'A control number request was sent successful.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
            }
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function reject($transition)
    {
        $this->validate(['comments' => 'required']);
        $operators = [];
        if ($this->checkTransition('application_filled_incorrect')) {
            $operators = [1, 2, 3];
        }
        try {
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_DETAILS_CORRECTION])->id;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.drivers-license.approval-processing');
    }
}
