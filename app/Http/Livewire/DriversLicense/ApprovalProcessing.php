<?php

namespace App\Http\Livewire\DriversLicense;

use App\Models\DlApplicationStatus;
use App\Models\DlFee;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\WorkflowProcesssingTrait;
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
        $this->validate(['comments' => 'required','duration_id'=>'required'],['duration_id.required'=>'You must select License Duration to approve']);

        try {
            DB::beginTransaction();
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_PAYMENT])->id;
            $this->subject->dl_license_duration_id = $this->duration_id;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $fee = DlFee::query()->where(['type' => $this->subject->type])->first();
            $exchange_rate = 1;
            $amount = $fee->amount;

            $zmBill = ZmCore::createBill(
                $this->subject->id,
                get_class($this->subject),
                6,
                $this->subject->taxpayer->id,
                get_class($this->subject->taxpayer),
                $this->subject->taxpayer->fullname(),
                $this->subject->taxpayer->email,
                ZmCore::formatPhone($this->subject->taxpayer->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->name,
                ZmCore::PAYMENT_OPTION_EXACT,
                'TZS',
                1,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $this->subject->id,
                        'billable_type' => get_class($this->subject),
                        'fee_id' => $fee->id,
                        'fee_type' => get_class($fee),
                        'tax_type_id' => 6,
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' => $fee->gfs_code
                    ]
                ]
            );
            $response = ZmCore::sendBill($zmBill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                session()->flash('success', 'A control number request was sent successful.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transition)
    {
        $this->validate(['comments' => 'required']);
        try {
            $this->subject->dl_application_status_id = DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_DETAILS_CORRECTION])->id;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.drivers-license.approval-processing');
    }
}
