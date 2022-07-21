<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\Business\SendBusinessApprovedSMS;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\Currency;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\TaxType;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $isiic_i;
    public $isiic_ii;
    public $isiic_iii;
    public $isiic_iv;
    public $taxTypes, $selectedTaxTypes = [];


    public $isiiciList = [];
    public $isiiciiList = [];
    public $isiiciiiList = [];
    public $isiicivList = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->isiiciList = ISIC1::all();
        $this->taxTypes = TaxType::all();
    }

    public function isiiciChange($value)
    {
        $this->isiiciiList = ISIC2::where('isic1_id', $value)->get();
        $this->isiic_ii = null;
        $this->isiic_iii = null;
        $this->isiic_iv = null;
        $this->isiiciiiList = [];
        $this->isiicivList = [];
    }

    public function isiiciiChange($value)
    {
        $this->isiiciiiList = ISIC3::where('isic2_id', $value)->get();
        $this->isiic_iii = null;
        $this->isiic_iv = null;
        $this->isiicivList = [];
    }

    public function isiiciiiChange($value)
    {
        $this->isiicivList = ISIC4::where('isic3_id', $value)->get();
        $this->isiic_iv = null;
    }

    public function approve($transtion)
    {
        if ($this->checkTransition('registration_officer_review')) {
            $this->subject->isiic_i = $this->isiic_i ?? null;
            $this->subject->isiic_ii = $this->isiic_ii ?? null;
            $this->subject->isiic_iii = $this->isiic_iii ?? null;
            $this->subject->isiic_iv = $this->isiic_iv ?? null;

            if (count($this->selectedTaxTypes) < 1){
                $this->addError('selectedTaxTypes', 'Please selected at least one tax type.');
                return;
            }

            $currency = Currency::find($this->subject->currency_id);

            $business = Business::find($this->subject->id);

            $business->taxTypes()->detach();

            foreach ($this->selectedTaxTypes as $type) {
                DB::table('business_tax_type')->insert([
                    'business_id' => $business->id,
                    'tax_type_id' => $type,
                    'currency' => $currency->iso,
                    'created_at' => Carbon::now()
                ]);
            }
        }
   
        if ($this->checkTransition('director_of_trai_review')) {
            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = BusinessStatus::APPROVED;
            $this->subject->z_no = 'ZBR_' . rand(1, 1000000);
            event(new SendSms('business-registration-approved', $this->subject->id));
            event(new SendMail('business-registration-approved', $this->subject->id));
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
                event(new SendSms('business-registration-correction', $this->subject->id));
                event(new SendMail('business-registration-correction', $this->subject->id));
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.approval.processing');
    }
}
