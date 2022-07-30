<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\Currency;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Notifications\DatabaseNotification;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $annual_estimate;
    public $quaters;
    public $isiic_i;
    public $isiic_ii;
    public $isiic_iii;
    public $isiic_iv;
    public $taxTypes;
    public Collection $selectedTaxTypes;

    public $isiiciList   = [];
    public $isiiciiList  = [];
    public $isiiciiiList = [];
    public $isiicivList  = [];

    public $showLumpsumOptions = false;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->isiiciList = ISIC1::all();
        $this->taxTypes   = TaxType::all();

        $this->isiic_i = $this->subject->isiic_i ?? null;

        if ($this->isiic_i) {
            $this->isiiciChange($this->isiic_i);
        }
        $this->isiic_ii = $this->subject->isiic_ii ?? null;
        if ($this->isiic_ii) {
            $this->isiiciiChange($this->isiic_ii);
        }
        $this->isiic_iii = $this->subject->isiic_iii ?? null;
        if ($this->isiic_iii) {
            $this->isiiciiiChange($this->isiic_iii);
        }
        $this->isiic_iv = $this->subject->isiic_iv ?? null;

        $this->selectedTaxTypes = collect();

        foreach ($this->subject->taxTypes as $value) {
            $this->selectedTaxTypes->push([
                'currency'    => $value->pivot->currency ?? '',
                'tax_type_id' => $value->id,
            ]);
        }

        if ($this->selectedTaxTypes->count() < 1) {
            $this->fill([
                'selectedTaxTypes' => collect([
                    [
                        'tax_type_id' => '',
                        'currency'    => '',
                    ],
                ]),
            ]);
        }
    }

    public function isiiciChange($value)
    {
        $this->isiiciiList  = ISIC2::where('isic1_id', $value)->get();
        $this->isiic_ii     = null;
        $this->isiic_iii    = null;
        $this->isiic_iv     = null;
        $this->isiiciiiList = [];
        $this->isiicivList  = [];
    }

    public function isiiciiChange($value)
    {
        $this->isiiciiiList = ISIC3::where('isic2_id', $value)->get();
        $this->isiic_iii    = null;
        $this->isiic_iv     = null;
        $this->isiicivList  = [];
    }

    public function isiiciiiChange($value)
    {
        $this->isiicivList = ISIC4::where('isic3_id', $value)->get();
        $this->isiic_iv    = null;
    }

    public function updated($property)
    {
        // Pluck id
        $Ids  = Arr::pluck($this->selectedTaxTypes, 'tax_type_id');

        // Get lumpsum ID
        $lumpSumId = TaxType::where('code', 'lumpsum-payment')->first()->id;

        // compare if plucked ID are the same as Lumpsum id
        if (in_array($lumpSumId, $Ids)) {
            $this->showLumpsumOptions = true;
        } else {
            $this->showLumpsumOptions =false;
        }
    }

    public function addTaxtype()
    {
        $this->selectedTaxTypes->push([
            'tax_type_id' => '',
            'currency'    => '',
        ]);
    }

    public function removeTaxType($key)
    {
        $this->selectedTaxTypes->pull($key);
    }

    public function approve($transtion)
    {
        if ($this->checkTransition('registration_officer_review')) {
            $this->subject->isiic_i   = $this->isiic_i ?? null;
            $this->subject->isiic_ii  = $this->isiic_ii ?? null;
            $this->subject->isiic_iii = $this->isiic_iii ?? null;
            $this->subject->isiic_iv  = $this->isiic_iv ?? null;

            // dd($this->annual_estimate);

            $this->validate([
                'isiic_i'                        => 'required',
                'isiic_ii'                       => 'required',
                'isiic_iii'                      => 'required',
                'isiic_iv'                       => 'required',
                'selectedTaxTypes'               => 'required',
                'comments'                       => 'required',
                'selectedTaxTypes.*.currency'    => 'required',
                'selectedTaxTypes.*.tax_type_id' => 'required|distinct',
            ], [
                'selectedTaxTypes.*.tax_type_id.distinct' => 'Duplicate value',
                'selectedTaxTypes.*.tax_type_id.required' => 'Tax type is require',
                'selectedTaxTypes.*.currency.required'    => 'Currency is required',
            ]);

            $business = Business::find($this->subject->id);
            $business->taxTypes()->detach();

            if ($this->showLumpsumOptions == true) {
                $this->validate(
                    [
                        'annual_estimate'  => 'required|integer',
                        'quaters'          => 'required|integer|between:1,12',
                        'quaters'          => 'required|integer',
                    ],
                    [
                        'annual_estimate.required'  => 'Annual estimation is required',
                        'annual_estimate.integer'   => 'Please enter the valid Annual Estimate',
                        'quaters.required'          => 'Please enter the valid payment Quaters',
                        'quaters.between'           => 'Please enter Quaters between 1 to 12',
                    ]
                );
                    
                DB::table('lump_sum_payments')->insert([
                    'business_id'       => $business->id,
                    'financial_year_id' => 1,
                    'annual_estimate'   => $this->annual_estimate,
                    'payment_quarters'  => $this->quaters,
                    'created_at'        => Carbon::now(),
                ]);
            }

            foreach ($this->selectedTaxTypes as $type) {
                DB::table('business_tax_type')->insert([
                    'business_id' => $business->id,
                    'tax_type_id' => $type['tax_type_id'],
                    'currency'    => $type['currency'],
                    'created_at'  => Carbon::now(),
                ]);
            }
        }

        $this->validate([
            'comments' => 'required',
        ]);

        if ($this->checkTransition('director_of_trai_review')) {
            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status      = BusinessStatus::APPROVED;
            $this->subject->z_no        = 'ZBR_' . rand(1, 1000000);
            event(new SendSms('business-registration-approved', $this->subject->id));
            event(new SendMail('business-registration-approved', $this->subject->id));
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
                event(new SendSms('business-registration-correction', $this->subject->id));
                event(new SendMail('business-registration-correction', $this->subject->id));
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.processing');
    }
}
