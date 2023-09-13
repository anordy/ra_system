<?php

namespace App\Http\Livewire\Approval;

use App\Enum\InternalInfoChangeStatus;
use App\Enum\InternalInfoType;
use App\Models\Business;
use App\Models\BusinessHotel;
use App\Models\BusinessLocation;
use App\Models\BusinessType;
use App\Models\Currency;
use App\Models\HotelStar;
use App\Models\InternalBusinessUpdate;
use App\Models\Returns\LumpSum\LumpSumConfig;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxRegion;
use App\Models\TaxType;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;

class InternalBusinessInfoChangeProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public $info, $infoType, $newHotelStar;
    public $hotelStars = [];

    public $currentEffectiveDate, $newEffectiveDate;

    public $selectedTaxTypes = [], $taxTypes = [], $lumpsumPayment;
    public $subVatOptions = [];
    public $showLumpsumOptions = false;
    public $vat_id;
    public $oldTaxes = [];
    public $ltoStatus = false, $currentltoStatus, $currentElectricStatus, $electricStatus = false, $taxRegionId, $businessCurrencyId;
    public $currencies = [], $taxRegions = [];
    public $currentCurrency, $newCurrency, $currentCurrencyId;
    public $currentTaxRegion, $newTaxRegion, $currentTaxRegionId;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->info = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);

        $this->infoType = $this->info->type;

        // Load Hotel stars data
        if ($this->infoType === InternalInfoType::HOTEL_STARS) {
            $this->hotelStars = HotelStar::select('id', 'no_of_stars', 'name')->orderBy('no_of_stars', 'asc')->get();
            $this->newHotelStar = json_decode($this->info->new_values)->id;
        }

        if ($this->infoType === InternalInfoType::EFFECTIVE_DATE) {
            $this->currentEffectiveDate = json_decode($this->info->old_values)->effective_date;
            $this->newEffectiveDate = json_decode($this->info->new_values)->effective_date;
        }

        if ($this->infoType === InternalInfoType::TAX_TYPE) {
            $this->taxTypes  = TaxType::main()->get();
            $this->vat_id = TaxType::query()->select('id')->where('code', TaxType::VAT)->firstOrFail()->id;
            $this->selectedTaxTypes = json_decode($this->info->new_values, TRUE)['selectedTaxTypes'];
            $this->lumpsumPayment = json_decode($this->info->new_values, TRUE)['lumpsumPayment'] ?? null;
        }

        if ($this->infoType === InternalInfoType::ELECTRIC) {
            $this->currentElectricStatus = boolval($this->info->old_values);
            $this->electricStatus = boolval($this->info->new_values);
        }

        if ($this->infoType === InternalInfoType::LTO) {
            $this->currentltoStatus = boolval($this->info->old_values);
            $this->ltoStatus = boolval($this->info->new_values);
        }

        if ($this->infoType === InternalInfoType::CURRENCY) {
            $this->currencies = Currency::select('id', 'name')->get();
            $this->currentCurrency = json_decode($this->info->old_values, TRUE);
            $this->newCurrency = json_decode($this->info->new_values, TRUE);
            $this->currentCurrencyId = $this->currentCurrency['currency_id'];
            $this->businessCurrencyId = $this->newCurrency['currency_id'];
        }

        if ($this->infoType === InternalInfoType::TAX_REGION) {
            $this->taxRegions = TaxRegion::select('id', 'name')->get();
            $this->currentTaxRegion = json_decode($this->info->old_values, TRUE);
            $this->newTaxRegion = json_decode($this->info->new_values, TRUE);
            $this->currentTaxRegionId = $this->currentTaxRegion['tax_region_id'];
            $this->taxRegionId = $this->newTaxRegion['tax_region_id'];
        }

        if ($this->infoType === InternalInfoType::ISIC) {

        }
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('registration_manager_review')) {
               $this->validate([
                   'newHotelStar' => 'required_if:infoType,hotel_stars',
                   'newEffectiveDate' => 'required_if:infoType,effective_date',
                   'ltoStatus' => 'required_if:informationType,lto',
                   'electricStatus' => 'required_if:informationType,electric',
                   'taxRegionId' => 'required_if:informationType,tax_region',
                   'businessCurrencyId' => 'required_if:informationType,currency',
               ]);

               if ($this->infoType === InternalInfoType::EFFECTIVE_DATE) {
                   $this->info->update(['new_values' => json_encode(['effective_date' => $this->newEffectiveDate])]);
               }

               if ($this->infoType === InternalInfoType::TAX_TYPE) {
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

                       // Save after final approval
                       $lumpsumPayment = [
                           'filed_by_id' => auth()->user()->id,
                           'business_id' => $this->info->business_id,
                           'business_location_id' => $this->info->location_id,
                           'annual_estimate' => $annualEstimate[0],
                           'payment_quarters' => $quarters[0],
                           'currency' => $currency[0],
                       ];
                   }

                   $newTaxes = [
                       'selectedTaxTypes' => $this->selectedTaxTypes,
                       'lumpsumPayment' => $lumpsumPayment ?? null
                   ];

                   $this->info->update([
                       'new_values' => json_encode($newTaxes),
                   ]);
               }

               if ($this->infoType === InternalInfoType::ELECTRIC) {
                   $this->info->update(['new_values' => $this->electricStatus]);
               }

                if ($this->infoType === InternalInfoType::LTO) {
                    $this->info->update(['new_values' => $this->ltoStatus]);
                }

                if ($this->infoType === InternalInfoType::CURRENCY) {
                    $this->info->update(['new_values' => json_encode(['currency_id' => $this->businessCurrencyId, 'name' => Currency::findOrFail($this->businessCurrencyId)->name])]);
                }

                if ($this->infoType === InternalInfoType::TAX_REGION) {
                    $this->info->update(['new_values' => json_encode(['tax_region_id' => $this->taxRegionId, 'name' => TaxRegion::findOrFail($this->taxRegionId)->name])]);
                }
            }

            if ($this->checkTransition('director_of_trai_review')) {
                
                // Update Hotel Star Rating
                if ($this->subject->type === InternalInfoType::HOTEL_STARS) {
                    $businessHotel = BusinessHotel::where('location_id', $this->subject->location_id)->firstOrFail();
                    $businessHotel->update(['hotel_star_id' => json_decode($this->subject->new_values)->id]);
                }

                // Future: Update ISIC Codes

                // Update Effective Date
                if ($this->subject->type === InternalInfoType::EFFECTIVE_DATE) {
                    BusinessLocation::findOrFail($this->info->location_id)->update(['effective_date' => $this->newEffectiveDate]);
                }

                // Update Tax Types
                if ($this->subject->type === InternalInfoType::TAX_TYPE) {
                    $this->info->business->taxTypes()->detach();

                    if ($this->showLumpsumOptions == true) {
                        DB::table('lump_sum_payments')->insert([
                            'filed_by_id' => auth()->user()->id,
                            'business_id' => $this->info->business_id,
                            'business_location_id' => $this->info->location_id,
                            'annual_estimate' => $this->lumpsumPayment['annualEstimate'][0],
                            'payment_quarters' => $this->lumpsumPayment['quarters'][0],
                            'currency' => $this->lumpsumPayment['currency'][0],
                        ]);
                    }

                    foreach ($this->selectedTaxTypes as $type) {
                        DB::table('business_tax_type')->insert([
                            'business_id' => $this->info->business_id,
                            'tax_type_id' => $type['tax_type_id'],
                            'sub_vat_id' => $type['sub_vat_id'] ?? null,
                            'currency' => $type['currency'],
                            'created_at' => Carbon::now(),
                            'status' => 'current-used'
                        ]);
                    }
                }

                if ($this->subject->type === InternalInfoType::ELECTRIC) {
                    $business = $this->subject->business;
                    $business->update([
                        'business_type' => $this->electricStatus ? BusinessType::ELECTRICITY : BusinessType::OTHER
                    ]);
                }

                if ($this->subject->type === InternalInfoType::LTO) {
                    $business = $this->subject->business;
                    $business->update([
                        'is_business_lto' => $this->ltoStatus
                    ]);
                }

                if ($this->subject->type === InternalInfoType::CURRENCY) {
                    $business = $this->subject->business;
                    $business->update([
                        'currency_id' => $this->businessCurrencyId
                    ]);
                }

                if ($this->subject->type === InternalInfoType::TAX_REGION) {
                    $location = $this->subject->location;
                    $location->update([
                        'tax_region_id' => $this->taxRegionId
                    ]);
                }

                $this->subject->status = InternalInfoChangeStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            $this->flash('success', 'Application Approved Successful', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('director_of_trai_reject')) {

            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                $this->flash('success', 'Application sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
    }


    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
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
                    'quarters'        => 4,
                ];

                $this->annualSales = LumpSumConfig::select('id', 'min_sales_per_year', 'max_sales_per_year', 'payments_per_year', 'payments_per_installment')->get()->toArray();

            } else {
                $this->showLumpsumOptions = false;
            }

            if (in_array($vatId, $this->Ids)) {
                $this->subVatOptions  = SubVat::select('id', 'name')->where('is_approved', 1)->get();
                $this->defaultSubVatOptions = $this->subVatOptions;
            }
        }
    }

    public function addTaxtype()
    {
        $this->selectedTaxTypes[] = [
            'tax_type_id' => '',
            'currency'    => '',
            'sub_vat_id'  => '',
            'sub_vat_name'  => '',
            'show_hide_options' => true
        ];
    }

    public function removeTaxType($index)
    {
        unset($this->selectedTaxTypes[$index]);
    }

    public function checkArrayKey($array, $column, $value, $givenKey) {
        $keys = array_keys(array_column($array, $column), $value);
        $checkedKey = (count($keys) > 0) ? $keys[0] : false;
        return $checkedKey == $givenKey;
    }

    public function subCategorySearchUpdate($key, $value){
        $this->selectedTaxTypes[$key]['show_hide_options'] = true;
        if (strlen($value) >= 3){
            $this->subVatOptions  = SubVat::select('id', 'name')->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$value}%"])->get();
        } else{
            $this->subVatOptions  = $this->defaultSubVatOptions;
        }
    }

    public function selectSubVat($key, $subVat){
        $sameKey = $this->checkArrayKey($this->selectedTaxTypes, 'sub_vat_id', $subVat['id'], $key);
        if (in_array($subVat['id'], array_column($this->selectedTaxTypes, 'sub_vat_id')) && !$sameKey){
            $this->alert('warning', 'Sub Vat is already selected');
            return;
        }

        $this->selectedTaxTypes[$key]['sub_vat_id'] = $subVat['id'];
        $this->selectedTaxTypes[$key]['sub_vat_name'] = $subVat['name'];
        $this->selectedTaxTypes[$key]['show_hide_options'] = false;
    }

    public function render()
    {
        return view('livewire.approval.internal-business-change-processing');
    }
}
