<?php

namespace App\Http\Livewire\Approval;

use App\Models\Business;
use App\Models\BusinessDirector;
use App\Models\BusinessLocation;
use App\Models\BusinessShare;
use App\Models\BusinessShareholder;
use App\Models\BusinessStatus;
use App\Models\BusinessType;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\LumpSumPayment;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
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
    public $isiic_i;
    public $isiic_ii;
    public $isiic_iii;
    public $isiic_iv;
    public $taxTypes;
    public $selectedTaxTypes = [];
    public $taxRegions;
    public $selectedTaxRegion;
    public $isBusinessElectric = false;
    public $isBusinessLTO = false;

    public $isiiciList   = [];
    public $isiiciiList  = [];
    public $isiiciiiList = [];
    public $isiicivList  = [];

    public $showLumpsumOptions = false;

    public $Ids, $exceptionOne, $exceptionTwo;

    public $directors;
    public $shareholders;
    public $shares;

    public function mount($modelName, $modelId)
    {
//        todo: encrypt modelID
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->isiiciList = ISIC1::all();
        $this->taxTypes   = TaxType::main()->get();

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

        $this->taxRegions = TaxRegion::all();

        foreach ($this->subject->taxTypes as $value) {
            $this->selectedTaxTypes[] = [
                'currency'    => $value->pivot->currency ?? '',
                'tax_type_id' => $value->id,
            ];
        }

        if (count($this->selectedTaxTypes) < 1) {
            $this->selectedTaxTypes[] = [
                'tax_type_id' => '',
                'currency'    => '',
            ];
        }

        $this->directors = BusinessDirector::where('business_id', $this->subject->id)->get();
        $this->shareholders = BusinessShareholder::where('business_id', $this->subject->id)->get();
        $this->shares = BusinessShare::where('business_id', $this->subject->id)->get();
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
        $property = explode('.', $property);

        if (end($property) === 'tax_type_id') {
            // Pluck id
            $this->Ids  = Arr::pluck($this->selectedTaxTypes, 'tax_type_id');

//            todo: if id is the only property needed, i suggest selecting it in a query to optimize performance
            // Get lumpsum ID
            $lumpSumId = TaxType::query()->where('code', TaxType::LUMPSUM_PAYMENT)->first()->id;

            // Get vat ID
            $vatId = TaxType::query()->where('code', TaxType::VAT)->first()->id;

            // Get vat ID
            $hotelId = TaxType::query()->where('code', TaxType::HOTEL)->first()->id;

            // Get stamp ID
            $stampId = TaxType::query()->where('code', TaxType::STAMP_DUTY)->first()->id;

            //adding IDs to array
            $this->exceptionOne = [$vatId, $hotelId];
            $this->exceptionTwo = [$vatId, $stampId];

            // compare if plucked ID are the same as Lumpsum id
            if (in_array($lumpSumId, $this->Ids)) {
                $this->showLumpsumOptions = true;
                $this->selectedTaxTypes   = [];
                $this->selectedTaxTypes[] = [
                    'tax_type_id'     => $lumpSumId,
                    'currency'        => '',
                    'annual_estimate' => '',
                    'quarters'        => '',
                ];
            } else {
                $this->showLumpsumOptions = false;
            }
        }
    }

    public function addTaxtype()
    {
        $this->selectedTaxTypes[] = [
            'tax_type_id' => '',
            'currency'    => '',
        ];
    }

    public function removeTaxType($index)
    {
        unset($this->selectedTaxTypes[$index]);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        if ($this->checkTransition('registration_officer_review')) {
            
            $this->subject->isiic_i   = $this->isiic_i ?? null;
            $this->subject->isiic_ii  = $this->isiic_ii ?? null;
            $this->subject->isiic_iii = $this->isiic_iii ?? null;
            $this->subject->isiic_iv  = $this->isiic_iv ?? null;

            $this->validate([
                'isiic_i'                        => 'required',
                'isiic_ii'                       => 'required',
                'isiic_iii'                      => 'required',
                'isiic_iv'                       => 'required',
                'selectedTaxTypes'               => 'required',
                'selectedTaxTypes.*.currency'    => 'required',
                'selectedTaxTypes.*.tax_type_id' => 'required|distinct',
                'selectedTaxRegion'              => 'required|exists:tax_regions,id',
            ], [
                'selectedTaxTypes.*.tax_type_id.distinct' => 'Duplicate value',
                'selectedTaxTypes.*.tax_type_id.required' => 'Tax type is require',
                'selectedTaxTypes.*.currency.required'    => 'Currency is required',
            ]);

//            todo: customize a fall back action
            $business = Business::find($this->subject->id);

            if($business == null){
                $this->alert('error', 'Business information does not exist');
                return;
            }

            $business->is_business_lto = $this->isBusinessLTO;

            if ($this->isBusinessElectric == true) {
                $business->business_type = BusinessType::ELECTRICITY;
            }

//            todo: with all the database insertions i suggest wrapping the logics in transaction
            $business->save();
            $business->headquarter->tax_region_id = $this->selectedTaxRegion;
            $business->headquarter->save();
            $business->taxTypes()->detach();

            if ($this->showLumpsumOptions == true) {
                $currency        = Arr::pluck($this->selectedTaxTypes, 'currency');
                $annualEstimate  = Arr::pluck($this->selectedTaxTypes, 'annual_estimate');
                $quarters        = Arr::pluck($this->selectedTaxTypes, 'quarters');

                $this->validate(
                    [
                        'selectedTaxTypes.*.annual_estimate'    => 'required|integer',
                        'selectedTaxTypes.*.quarters'           => 'required|integer|between:1,12',
                    ],
                    [
                        'selectedTaxTypes.*.annual_estimate.required'   => 'Annual estimation is required',
                        'selectedTaxTypes.*.annual_estimate.integer'    => 'Please enter the valid Annual Estimate',
                        'selectedTaxTypes.*.quarters.required'          => 'Please enter the valid payment Quaters',
                        'selectedTaxTypes.*.quarters.between'           => 'Please enter Quaters between 1 to 12',
                    ]
                );

                DB::table('lump_sum_payments')->insert([
                    'filed_by_id'         => auth()->user()->id,
                    'business_id'         => $this->subject->id,
                    'business_location_id' => $business->id,
                    'annual_estimate'     => $annualEstimate[0],
                    'payment_quarters'    => $quarters[0],
                    'currency'            => $currency[0],
                ]);
            }

            foreach ($this->selectedTaxTypes as $type) {
                DB::table('business_tax_type')->insert([
                    'business_id' => $business->id,
                    'tax_type_id' => $type['tax_type_id'],
                    'currency'    => $type['currency'],
                    'created_at'  => Carbon::now(),
                    'status' => 'current-used'
                ]);
            }
        }

        if ($this->checkTransition('director_of_trai_review')) {
            $location = BusinessLocation::where('business_id', $this->subject->id)
                ->where('is_headquarter', true)
                ->firstOrFail();
            $lumpsum = LumpSumPayment::where('business_id', $this->subject->id)
                ->latest()
                ->first();

            if ($lumpsum != null) {
                $lumpsum->update(['business_location_id' => $location->id]);
            }

            if ($location->ztnGeneration()) {

                if (!$location->generateZ()) {
                    $this->alert('error', 'Something went wrong.');
                    return;
                }
            } else {
                $this->alert('error', 'Something went wrong.');
                return;
            }

            if (!$location->business->taxTypes->where('code', 'vat')->isEmpty()) {
                $location->generateVrn();
            }

            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = BusinessStatus::APPROVED;
        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
            return;
        }

        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.processing');
    }
}
