<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlFee;
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
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $this->validate(['comments' => 'required', 'duration_id' => 'required'], ['duration_id.required' => 'You must select License Duration to approve']);

        DB::beginTransaction();

        try {
            $fee = DlFee::query()->where(['type' => $this->subject->type, 'dl_license_duration_id'=>$this->subject->dl_license_duration_id])->first();

            if (empty($fee)) {
                $this->alert('error', "Fee for Drivers license application ({$this->subject->type}) is not configured");
                return;
            }
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_PAYMENT])->id;
            $this->subject->dl_license_duration_id = $this->duration_id;
            $this->subject->save();

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $bill = $this->subject->generateBill();
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($bill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            } else {
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = rand(2000070001000, 2000070009999);
                $bill->save();
                $this->flash('success', 'A control number for this dispute has been generated successfull and approved');
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
