<?php

namespace App\Http\Livewire\InternalInfoChange;

use App\Enum\InternalInfoType;
use App\Models\Business;
use App\Models\BusinessHotel;
use App\Models\BusinessLocation;
use App\Models\BusinessType;
use App\Models\Currency;
use App\Models\HotelStar;
use App\Models\InternalBusinessUpdate;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\LumpSumPayment;
use App\Models\Returns\LumpSum\LumpSumConfig;
use App\Models\Returns\Vat\SubVat;
use App\Models\TaxDepartment;
use App\Models\Taxpayer;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class InitiateChangeModal extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $informationType;
    public $businessHotel, $newHotelStarId, $hotelStars = [];
    public $zin, $location;
    public $currentEffectiveDate, $newEffectiveDate;
    public $selectedTaxTypes = [], $taxTypes = [];
    public $subVatOptions = [];
    public $defaultSubVatOptions = [];
    public $showLumpsumOptions = false;
    public $vat_id;
    public $oldTaxes = [];
    public $ltoStatus = false, $electricStatus = false, $taxRegionId, $businessCurrencyId;
    public $currencies = [], $taxRegions = [];
    public $showElectric = false, $showLto = false;
    public $isiic_i, $isiic_ii, $isiic_iii, $isiic_iv;
    public $isiiciList = [], $isiiciiList = [], $isiiciiiList = [], $isiicivList = [];
    public $previousOwner;
    public $newOwnerZno;
    public $taxDepartment, $selectedDepartment, $selectedTaxRegion, $lumpsumPayment = [], $currentTaxTypes = [];

    public function mount()
    {
    }

    protected function rules()
    {
        return [
            'informationType' => 'required',
            'zin' => 'required',
            'newHotelStarId' => 'required_if:informationType,hotelStars',
            'newEffectiveDate' => 'required_if:informationType,effectiveDate',
            'ltoStatus' => 'required_if:informationType,lto',
            'electricStatus' => 'required_if:informationType,electric',
            'taxRegionId' => 'required_if:informationType,taxRegion',
            'selectedDepartment' => 'required_if:informationType,taxRegion',
            'businessCurrencyId' => 'required_if:informationType,currency',
            'isiic_i' => 'nullable|required_if:informationType,isic|numeric|exists:isic1s,id',
            'isiic_ii' => 'nullable|required_if:informationType,isic|numeric|exists:isic2s,id',
            'isiic_iii' => 'nullable|required_if:informationType,isic|numeric|exists:isic3s,id',
            'isiic_iv' => 'nullable|required_if:informationType,isic|numeric|exists:isic4s,id',
            'newOwnerZno' => [
                'nullable',
                'required_if:informationType,businessOwnership',
                'exists:taxpayers,reference_no',
                function ($attribute, $value, $fail) {
                    if ($this->previousOwner->reference_no == $value) {
                        $fail('Please provide a different taxpayer number from the existing one.');
                    }
                }
            ],

        ];
    }


    protected $messages = [
        'newHotelStarId.required_if' => 'Please select new hotel star rating',
        'newEffectiveDate.required_if' => 'Please enter effective date',
        'newOwnerZno.exists' => 'Please provide a valid taxpayer ZNO.'
    ];

    public function submit()
    {
        $this->validate();

        if ($this->informationType === 'taxType') {
            if ($this->showLumpsumOptions == true) {
                $this->validate(
                    [
                        'selectedTaxTypes.*.annual_estimate' => 'required|integer',
                        'selectedTaxTypes.*.quarters' => 'required|integer|between:1,12',
                        'selectedTaxTypes.*.currency' => 'required',
                    ],
                    [
                        'selectedTaxTypes.*.annual_estimate.required' => 'Annual estimation is required',
                        'selectedTaxTypes.*.annual_estimate.integer' => 'Please enter the valid Annual Estimate',
                        'selectedTaxTypes.*.quarters.required' => 'Please enter the valid payment Quaters',
                        'selectedTaxTypes.*.quarters.between' => 'Please enter Quaters between 1 to 12',
                        'selectedTaxTypes.*.currency.required' => 'Please select currency',
                    ]
                );
            } else {
                $this->validate([
                    'selectedTaxTypes' => 'required',
                    'selectedTaxTypes.*.currency' => 'required',
                    'selectedTaxTypes.*.tax_type_id' => 'required|distinct'
                ], [
                    'selectedTaxTypes.*.tax_type_id.distinct' => 'Duplicate value',
                    'selectedTaxTypes.*.tax_type_id.required' => 'Tax type is required',
                    'selectedTaxTypes.*.currency.required' => 'Currency is required',
                ]);
            }
        }


        try {
            DB::beginTransaction();

            // Record data to be altered in Business hotel stars
            if ($this->informationType === 'hotelStars') {
                $selectedStar = HotelStar::select('id', 'no_of_stars', 'name')->findOrFail($this->newHotelStarId);

                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::HOTEL_STARS,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['name' => $this->businessHotel->star->name ?? null, 'no_of_stars' => $this->businessHotel->star->no_of_stars ?? null, 'id' => $this->businessHotel->hotel_star_id ?? null]),
                    'new_values' => json_encode(['name' => $selectedStar->name ?? null, 'no_of_stars' => $selectedStar->no_of_stars ?? null, 'id' => $this->newHotelStarId]),
                ]);
            }

            // Record data to be altered in Business Location effective date
            if ($this->informationType === 'effectiveDate') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::EFFECTIVE_DATE,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['effective_date' => $this->currentEffectiveDate]),
                    'new_values' => json_encode(['effective_date' => $this->newEffectiveDate]),
                ]);
            }

            $lumpsumPayment = [];
            if ($this->informationType === 'taxType') {
                if ($this->showLumpsumOptions == true) {
                    $currency = Arr::pluck($this->selectedTaxTypes, 'currency');
                    $annualEstimate = Arr::pluck($this->selectedTaxTypes, 'annual_estimate');
                    $quarters = Arr::pluck($this->selectedTaxTypes, 'quarters');

                    $this->validate(
                        [
                            'selectedTaxTypes.*.annual_estimate' => 'required|integer',
                            'selectedTaxTypes.*.quarters' => 'required|integer|between:1,12',
                            'selectedTaxTypes.*.currency' => 'required',
                        ],
                        [
                            'selectedTaxTypes.*.annual_estimate.required' => 'Annual estimation is required',
                            'selectedTaxTypes.*.annual_estimate.integer' => 'Please enter the valid Annual Estimate',
                            'selectedTaxTypes.*.quarters.required' => 'Please enter the valid payment Quaters',
                            'selectedTaxTypes.*.quarters.between' => 'Please enter Quaters between 1 to 12',
                            'selectedTaxTypes.*.currency.required' => 'Please select currency',
                        ]
                    );

                    // Save after final approval
                    $lumpsumPayment = [
                        'filed_by_id' => auth()->user()->id,
                        'business_id' => $this->location->business_id,
                        'business_location_id' => $this->location->id,
                        'annual_estimate' => $annualEstimate[0],
                        'payment_quarters' => $quarters[0],
                        'currency' => $currency[0],
                    ];
                }

                // Check if SubVat is selected and If business is not hotel don't assign hotel levy
                foreach ($this->selectedTaxTypes as $type) {
                    $tax = TaxType::findOrFail($type['tax_type_id'], ['code']);

                    if ($tax->code === TaxType::VAT && empty($type['sub_vat_id'])) {
                        $this->customAlert('warning', 'Please assign VAT Category Type when VAT Tax Type is selected');
                        return;
                    }

                    if ($tax->code === TaxType::HOTEL && $this->location->business->business_type != BusinessType::HOTEL) {
                        $this->customAlert('warning', 'The business must be of Hotel type in order to assign Hotel Levy Tax Type');
                        return;
                    }
                }

                $newTaxes = [
                    'selectedTaxTypes' => $this->selectedTaxTypes,
                    'lumpsumPayment' => $lumpsumPayment
                ];

                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::TAX_TYPE,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode($this->oldTaxes),
                    'new_values' => json_encode($newTaxes),
                ]);
            }

            if ($this->informationType === 'electric') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::ELECTRIC,
                    'triggered_by' => Auth::id(),
                    'old_values' => !$this->electricStatus,
                    'new_values' => $this->electricStatus,
                ]);
            }

            if ($this->informationType === 'lto') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::LTO,
                    'triggered_by' => Auth::id(),
                    'old_values' => !$this->ltoStatus,
                    'new_values' => $this->ltoStatus,
                ]);
            }

            if ($this->informationType === 'taxRegion') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::TAX_REGION,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['tax_region_id' => $this->location->tax_region_id, 'name' => $this->location->taxRegion->name, 'department_id' => $this->location->taxRegion->department_id, 'department_name' => $this->location->taxRegion->department->name ?? null]),
                    'new_values' => json_encode(['tax_region_id' => $this->selectedTaxRegion, 'name' => TaxRegion::findOrFail($this->selectedTaxRegion)->name, 'department_id' => $this->selectedDepartment, 'department_name' => TaxDepartment::find($this->selectedDepartment)->name]),
                ]);
            }

            if ($this->informationType === 'currency') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::CURRENCY,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['currency_id' => $this->location->business->currency_id, 'name' => Currency::findOrFail($this->location->business->currency_id)->name]),
                    'new_values' => json_encode(['currency_id' => $this->businessCurrencyId, 'name' => Currency::findOrFail($this->businessCurrencyId)->name]),
                ]);
            }

            if ($this->informationType === 'isic') {
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::ISIC,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode([
                        'isiic_i' => $this->location->business->isiic_i,
                        'isiic_i_name' => ISIC1::findOrFail($this->location->business->isiic_i)->description,
                        'isiic_ii' => $this->location->business->isiic_ii,
                        'isiic_ii_name' => ISIC2::findOrFail($this->location->business->isiic_ii)->description,
                        'isiic_iii' => $this->location->business->isiic_iii,
                        'isiic_iii_name' => ISIC3::findOrFail($this->location->business->isiic_iii)->description,
                        'isiic_iv' => $this->location->business->isiic_iv,
                        'isiic_iv_name' => ISIC4::findOrFail($this->location->business->isiic_iv)->description,
                    ]),
                    'new_values' => json_encode([
                        'isiic_i' => $this->isiic_i,
                        'isiic_i_name' => ISIC1::findOrFail($this->isiic_i)->description,
                        'isiic_ii' => $this->isiic_ii,
                        'isiic_ii_name' => ISIC2::findOrFail($this->isiic_ii)->description,
                        'isiic_iii' => $this->isiic_iii,
                        'isiic_iii_name' => ISIC3::findOrFail($this->isiic_iii)->description,
                        'isiic_iv' => $this->isiic_iv,
                        'isiic_iv_name' => ISIC4::findOrFail($this->isiic_iv)->description,
                    ]),
                ]);
            }


            if ($this->informationType === 'businessOwnership') {
                $newOwner = Taxpayer::where('reference_no', $this->newOwnerZno)->firstOrFail();
                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => InternalInfoType::BUSINESS_OWNERSHIP,
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['reference_no' => $this->previousOwner->reference_no, 'name' => $this->previousOwner->fullName]),
                    'new_values' => json_encode(['reference_no' => $this->newOwnerZno, 'name' => $newOwner->fullName]),
                ]);
            }

            DB::commit();

            $this->registerWorkflow(get_class($internalChange), $internalChange->id);
            $this->doTransition('registration_manager_review', ['status' => 'agree']);

            $this->flash('success', 'Data forwarded for approval', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function getZin()
    {
        $this->location = BusinessLocation::select('id', 'business_id', 'name', 'effective_date', 'tax_region_id')->with('business')->where('zin', trim($this->zin))->first();

        if ($this->location) {
            // Load hotel stars & Business hotel if hotelStars info type is selected
            if ($this->informationType === 'hotelStars') {
                if ($this->location->business->business_type === 'hotel') {
                    $this->hotelStars = HotelStar::select('id', 'no_of_stars', 'name')->orderBy('id', 'asc')->get();
                    $this->businessHotel = BusinessHotel::select('id', 'location_id', 'hotel_star_id')->with('star')->where('location_id', $this->location->id)->first();;
                } else {
                    $this->customAlert('error', 'Business Location is not of Hotel Type');
                }
            } else if ($this->informationType === 'effectiveDate') {
                $this->currentEffectiveDate = Carbon::create($this->location->effective_date)->format('d-M-Y');
            } else if ($this->informationType === 'taxType') {
                $this->selectedTaxTypes = [];
                $this->taxTypes = TaxType::main()->get();
                $businessTaxes = Business::findOrFail($this->location->business_id)->taxTypes;
                $this->vat_id = TaxType::query()->select('id')->where('code', TaxType::VAT)->firstOrFail()->id;
                foreach ($businessTaxes as $value) {
                    $subVat = $value->pivot->sub_vat_id ? SubVat::where('id', $value->pivot->sub_vat_id)->where('is_approved', 1)->firstOrFail('name') : null;
                    $this->selectedTaxTypes[] = [
                        'currency' => $value->pivot->currency ?? '',
                        'tax_type_id' => $value->id,
                        'sub_vat_id' => $value->pivot->sub_vat_id,
                        'sub_vat_name' => $value->pivot->sub_vat_id ? $subVat['name'] : null,
                        'show_hide_options' => false,
                    ];
                }

                if (count($this->selectedTaxTypes) < 1) {
                    $this->selectedTaxTypes[] = [
                        'tax_type_id' => '',
                        'currency' => '',
                        'sub_vat_id' => '',
                        'sub_vat_name' => '',
                        'show_hide_options' => true
                    ];
                }

                $lumpsum = LumpSumPayment::where('business_location_id', $this->location->id)->first();

                if ($lumpsum) {
                    $this->lumpsumPayment = [
                        'annual_estimate' => $lumpsum->annual_estimate ?? 0,
                        'payment_quarters' => $lumpsum->payment_quarters ?? 0,
                        'currency' => $lumpsum->currency ?? Currency::TZS,
                    ];
                } else {
                    $this->lumpsumPayment = [];
                }

                $this->oldTaxes = $this->selectedTaxTypes;
            } else if ($this->informationType === 'lto') {
                $this->showLto = true;
                $this->ltoStatus = boolval($this->location->business->is_business_lto);
            } else if ($this->informationType === 'electric') {
                $businessType = $this->location->business->business_type;
                $this->showElectric = true;
                if ($businessType == BusinessType::ELECTRICITY) {
                    $this->electricStatus = true;
                }
            } else if ($this->informationType === 'currency') {
                $this->currencies = Currency::select('id', 'name')->get();
                $this->businessCurrencyId = $this->location->business->currency_id;
            } else if ($this->informationType === 'taxRegion') {
                $this->taxRegions = TaxRegion::select('id', 'name')->get();
                $this->taxRegionId = $this->location->tax_region_id;
                $this->taxDepartment = TaxDepartment::all();
                $this->selectedTaxRegion = $this->location->tax_region_id;
                $this->selectedDepartment = $this->location->taxRegion->department_id;
            } else if ($this->informationType === 'isic') {
                $this->isiiciList = ISIC1::all();
            } else if ($this->informationType === 'businessOwnership') {
                $this->previousOwner = $this->location->business->taxpayer;
            }
        } else {
            $this->customAlert('error', 'Invalid ZIN Number provided');
        }
    }

    public function updated($property)
    {

        $property = explode('.', $property);

        if (end($property) === 'tax_type_id') {
            $this->lumpsumPayment = [];

            // Pluck id
            $this->Ids = Arr::pluck($this->selectedTaxTypes, 'tax_type_id');

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
                $this->selectedTaxTypes = [];
                $this->selectedTaxTypes[] = [
                    'tax_type_id' => $lumpSumId,
                    'currency' => '',
                    'annual_estimate' => '',
                    'quarters' => 4,
                ];

                $this->annualSales = LumpSumConfig::select('id', 'min_sales_per_year', 'max_sales_per_year', 'payments_per_year', 'payments_per_installment')->get()->toArray();
            } else {
                $this->showLumpsumOptions = false;
            }

            if (in_array($vatId, $this->Ids)) {
                $this->subVatOptions = SubVat::select('id', 'name')->where('is_approved', 1)->get();
                $this->defaultSubVatOptions = $this->subVatOptions;
            }
        }
    }

    public function addTaxtype()
    {
        $this->selectedTaxTypes[] = [
            'tax_type_id' => '',
            'currency' => '',
            'sub_vat_id' => '',
            'sub_vat_name' => '',
            'show_hide_options' => true
        ];
    }

    public function removeTaxType($index)
    {
        unset($this->selectedTaxTypes[$index]);
    }

    public function checkArrayKey($array, $column, $value, $givenKey)
    {
        $keys = array_keys(array_column($array, $column), $value);
        $checkedKey = (count($keys) > 0) ? $keys[0] : false;
        return $checkedKey == $givenKey;
    }

    public function subCategorySearchUpdate($key, $value)
    {
        $this->selectedTaxTypes[$key]['show_hide_options'] = true;
        if (strlen($value) >= 3) {
            $this->subVatOptions = SubVat::select('id', 'name')->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$value}%"])->get();
        } else {
            $this->subVatOptions = $this->defaultSubVatOptions;
        }
    }

    public function selectSubVat($key, $subVat)
    {
        $sameKey = $this->checkArrayKey($this->selectedTaxTypes, 'sub_vat_id', $subVat['id'], $key);
        if (in_array($subVat['id'], array_column($this->selectedTaxTypes, 'sub_vat_id')) && !$sameKey) {
            $this->alert('warning', 'Sub Vat is already selected');
            return;
        }

        $this->selectedTaxTypes[$key]['sub_vat_id'] = $subVat['id'];
        $this->selectedTaxTypes[$key]['sub_vat_name'] = $subVat['name'];
        $this->selectedTaxTypes[$key]['show_hide_options'] = false;
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

    public function selectedDepartment($value)
    {
        if (!is_null((int)$value)) {
            $this->taxRegions = TaxRegion::where('department_id', $value)->get();
        } else {
            $this->taxRegions = [];
        }
    }

    public function render()
    {
        return view('livewire.internal-info-change.initiate');
    }
}
