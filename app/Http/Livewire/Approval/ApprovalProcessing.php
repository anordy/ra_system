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
use App\Models\Returns\Vat\SubVat;
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
    public $selectedTaxRegion, $effectiveDate;
    public $isBusinessElectric = false;
    public $isBusinessLTO = false;

    public $isiiciList   = [];
    public $isiiciiList  = [];
    public $isiiciiiList = [];
    public $isiicivList  = [];

    public $subVatOptions = [];

    public $showLumpsumOptions = false;
    public $showSubVatOptions = false;

    public $Ids, $exceptionOne, $exceptionTwo;

    public $directors;
    public $shareholders;
    public $shares;
    public $sub_vat_id;

    public function mount($modelName, $modelId)
    {
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

            // Get lumpsum ID
            $lumpSumId = TaxType::query()->select('id')->where('code', TaxType::LUMPSUM_PAYMENT)->firstOrFail()->id;

            // Get vat ID
            $vatId = TaxType::query()->select('id')->where('code', TaxType::VAT)->firstOrFail()->id;

            // Get vat ID
            $hotelId = TaxType::query()->select('id')->where('code', TaxType::HOTEL)->firstOrFail()->id;

            // Get stamp ID
            $stampId = TaxType::query()->select('id')->where('code', TaxType::STAMP_DUTY)->firstOrFail()->id;

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

            if (in_array($vatId, $this->Ids)) {
                $this->subVatOptions  = SubVat::select('id', 'name')->where('is_approved', 1)->get();
                $this->showSubVatOptions = true;
            } else {
                $this->showSubVatOptions = false;
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
            try {
                $this->subject->isiic_i = $this->isiic_i ?? null;
                $this->subject->isiic_ii = $this->isiic_ii ?? null;
                $this->subject->isiic_iii = $this->isiic_iii ?? null;
                $this->subject->isiic_iv = $this->isiic_iv ?? null;

                $this->validate([
                    'isiic_i' => 'required|numeric|exists:isic1s,id',
                    'isiic_ii' => 'required|numeric|exists:isic2s,id',
                    'isiic_iii' => 'required|numeric|exists:isic3s,id',
                    'isiic_iv' => 'required|numeric|exists:isic4s,id',
                    'selectedTaxTypes' => 'required',
                    'selectedTaxTypes.*.currency' => 'required',
                    'selectedTaxTypes.*.tax_type_id' => 'required|distinct',
                    'selectedTaxRegion' => 'required|exists:tax_regions,id',
                    'effectiveDate' => 'required|strip_tag'
                ], [
                    'selectedTaxTypes.*.tax_type_id.distinct' => 'Duplicate value',
                    'selectedTaxTypes.*.tax_type_id.required' => 'Tax type is required',
                    'selectedTaxTypes.*.currency.required' => 'Currency is required',
                ]);

                $business = Business::findOrFail($this->subject->id);

                DB::beginTransaction();

                $business->is_business_lto = $this->isBusinessLTO;

                if ($this->isBusinessElectric == true) {
                    $business->business_type = BusinessType::ELECTRICITY;
                }

                $business->save();
                $business->headquarter->tax_region_id = $this->selectedTaxRegion;
                $business->headquarter->effective_date = $this->effectiveDate;
                $business->headquarter->save();
                $business->taxTypes()->detach();

                if ($this->showLumpsumOptions == true) {
                    $currency = Arr::pluck($this->selectedTaxTypes, 'currency');
                    $annualEstimate = Arr::pluck($this->selectedTaxTypes, 'annual_estimate');
                    $quarters = Arr::pluck($this->selectedTaxTypes, 'quarters');

                    $this->validate(
                        [
                            'selectedTaxTypes.*.annual_estimate' => 'required|integer',
                            'selectedTaxTypes.*.quarters' => 'required|integer|between:1,12',
                        ],
                        [
                            'selectedTaxTypes.*.annual_estimate.required' => 'Annual estimation is required',
                            'selectedTaxTypes.*.annual_estimate.integer' => 'Please enter the valid Annual Estimate',
                            'selectedTaxTypes.*.quarters.required' => 'Please enter the valid payment Quaters',
                            'selectedTaxTypes.*.quarters.between' => 'Please enter Quaters between 1 to 12',
                        ]
                    );

                    DB::table('lump_sum_payments')->insert([
                        'filed_by_id' => auth()->user()->id,
                        'business_id' => $this->subject->id,
                        'business_location_id' => $business->id,
                        'annual_estimate' => $annualEstimate[0],
                        'payment_quarters' => $quarters[0],
                        'currency' => $currency[0],
                    ]);
                }

                if ($this->showSubVatOptions == true) {
                    $this->validate([
                        'sub_vat_id' => 'required'
                    ]);
                }

                foreach ($this->selectedTaxTypes as $type) {
                    DB::table('business_tax_type')->insert([
                        'business_id' => $business->id,
                        'tax_type_id' => $type['tax_type_id'],
                        'sub_vat_id' => $this->sub_vat_id ?? null,
                        'currency' => $type['currency'],
                        'created_at' => Carbon::now(),
                        'status' => 'current-used'
                    ]);
                }

                DB::commit();
            } catch (Exception $exception){
                DB::rollBack();
                Log::error($exception);
                $this->alert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }
        }

        if ($this->checkTransition('director_of_trai_review')) {
            try {
                DB::beginTransaction();

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
                        $this->alert('error', 'Something went wrong, please contact the administrator for help.');
                        return;
                    }
                } else {
                    $this->alert('error', 'Something went wrong, please contact the administrator for help.');
                    return;
                }

                if (!$location->business->taxTypes->where('code', 'vat')->isEmpty()) {
                    $location->generateVrn();
                }

                $location->status = BusinessStatus::APPROVED;
                $location->approved_on = Carbon::now()->toDateTimeString();
                $location->save();

                $this->subject->verified_at = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::APPROVED;

                DB::commit();
            } catch (Exception $exception){
                DB::rollBack();
                Log::error($exception);
                $this->alert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }
        }

        try {
            DB::beginTransaction();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
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
