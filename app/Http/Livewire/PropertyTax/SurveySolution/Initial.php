<?php

namespace App\Http\Livewire\PropertyTax\SurveySolution;


use App\Enum\BillStatus;
use App\Enum\PropertyOwnershipTypeStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Enum\UnitUsageTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Country;
use App\Models\Currency;
use App\Models\District;
use App\Models\FinancialYear;
use App\Models\IDType;
use App\Models\KYC;
use App\Models\PropertyTax\Property;
use App\Models\PropertyTax\PropertyAgent;
use App\Models\PropertyTax\PropertyOwner;
use App\Models\PropertyTax\PropertyOwnershipType;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\PropertyTax\PropertyStorey;
use App\Models\PropertyTax\PropertyTaxHotelStar;
use App\Models\Region;
use App\Models\Street;
use App\Models\Taxpayer;
use App\Models\Ward;
use App\Services\Api\SurveySolutionInternalService;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PropertyTaxTrait;
use App\Traits\VerificationTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Initial extends Component
{
    use CustomAlert, VerificationTrait, WorkflowProcesssingTrait, PropertyTaxTrait, PaymentsTrait;

    public $identifierType, $identifierNumber;
    public $properties, $nationality, $permitNumber;
    public $size, $name, $features, $propertyValue, $purchaseValue, $acquisitionDate, $ownershipType, $institutionName;
    public $ownershipTypes = [];
    public $countries = [];
    public $streets = [];
    public $wards = [];
    public $districts = [];
    public $regions = [];
    public $stars = [];
    public $additionalProperties = [];
    public $type;
    public $propertyTypes = [];
    public $addRegionId, $addDistrictId, $addWardId;

    public function mount()
    {
        $this->ownershipTypes = PropertyOwnershipType::select('id', 'name')->get();
        $this->countries = DB::table('countries')
            ->select('id', 'nationality')
            ->where('name', '!=', Country::TANZANIA)
            ->where('is_approved', 1)
            ->get();
        $this->stars = PropertyTaxHotelStar::select('id', 'name')->get();
        $regions = DB::table('regions')
            ->select('id', 'name')
            ->where('is_approved', 1)
            ->get();
        $this->regions = json_decode($regions, true);
        $this->propertyTypes = PropertyTypeStatus::getConstants();
        $this->propertyTypes = array_values($this->propertyTypes);
    }

    public function addProperty()
    {
        $this->additionalProperties[] = [
            'name' => '',
            'hotel_stars_id' => '',
            'house_number' => '',
            'type' => PropertyTypeStatus::OTHER,
            'usage_type' => '',
            'size' => '',
            'property_value' => '',
            'purchase_value' => '',
            'acquisition_date' => '',
            'features' => '',
            'number_of_storeys' => ''
        ];
    }

    public function submit()
    {
        $this->validate([
            'ownershipType' => 'required',
            'institutionName' => ['nullable', 'strip_tag', 'required_if:ownershipType,' . PropertyOwnershipTypeStatus::RELIGIOUS . ',' . PropertyOwnershipTypeStatus::GOVERNMENT],
            'nationality' => isset($this->properties[0]['owner']['passport']) && !is_null($this->properties[0]['owner']['passport']) ? 'required' : '',
            'permitNumber' => isset($this->properties[0]['owner']['passport']) && !is_null($this->properties[0]['owner']['passport']) ? 'required|numeric' : '',
            'addWardId' => count($this->additionalProperties) > 0 ? 'required' : '',
            'addDistrictId' => count($this->additionalProperties) > 0 ? 'required' : '',
            'addRegionId' => count($this->additionalProperties) > 0 ? 'required' : '',
            'additionalProperties.*.type' => count($this->additionalProperties) > 0 ? 'required' : '',
//            'additionalProperties.*.number_of_storeys' => $this->additionalProperties[0]['type']) > 0 ? 'required' : '',
        ]);

        try {
            DB::beginTransaction();

            // Check if existing owner details exist, If yes associate with existing account otherwise create an account
            // Check via email, mobile, nida, zanid
            $taxPayer = Taxpayer::where('email', $this->properties[0]['owner']['email_address'] ?? '')
                ->orWhere('mobile', $this->properties[0]['owner']['phone_no'] ?? '')
                ->orWhere('nida_no', $this->properties[0]['owner']['nida'] ?? '')
                ->orWhere('zanid_no', $this->properties[0]['owner']['zanID'] ?? '')
                ->orWhere('passport_no', $this->properties[0]['owner']['passport'] ?? '')
                ->first();

            if (!$taxPayer) {
                $kyc = $this->createKYC();

                $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by', 'comments'])->toArray();
                $permitted_chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#%';
                $password = substr(str_shuffle($permitted_chars), 0, 8);
                $data['password'] = Hash::make($password);

                if (config('app.env') == 'local') {
                    $data['password'] = Hash::make('password');
                }

                $taxPayer = Taxpayer::create([
                    'id_type' => $data['id_type'],
                    'nida_no' => $data['nida_no'] ?? null,
                    'zanid_no' => $data['zanid_no'] ?? null,
                    'tin_no' => $data['tin_no'] ?? null,
                    'passport_no' => $data['passport_no'] ?? null,
                    'permit_number' => $data['permit_number'] ?? null,
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'last_name' => $data['last_name'],
                    'password' => $data['password'],
                    'physical_address' => $data['physical_address'] ?? 'N/A',
                    'street_id' => $data['street_id'],
                    'district_id' => $data['district_id'],
                    'ward_id' => $data['ward_id'],
                    'email' => $data['email'] ?? '',
                    'mobile' => $data['mobile'],
                    'alt_mobile' => $data['alt_mobile'] ?? '',
                    'region_id' => $data['region_id'],
                    'is_citizen' => $data['is_citizen'],
                    'country_id' => $data['country_id'],
                ]);

                $taxPayer->generateReferenceNo();

            }

            // Save property information
            foreach ($this->properties as $i => $property) {
                $i++;
                if ($property['property_type'] === PropertyTypeStatus::HOTEL) {
                    if (is_null($property['hotel_star'])) {
                        $this->customAlert('warning', 'Missing Number of Stars for Hotel');
                        return;
                    }
                    $hotelStarId = PropertyTaxHotelStar::where('no_of_stars', $property['hotel_star'])->firstOrFail()->id;
                }

                $doesInterviewExist = Property::where('interview_id', $property['interview__id'])->first();

                if ($doesInterviewExist) {
                    $this->customAlert('warning', "Property Number {$i} has already been registered");
                    return;
                }

                $generatedProperty = Property::create([
                    'name' => $this->name, // Inserted manually
                    'hotel_stars_id' => $hotelStarId ?? null,
                    'interview_id' => $property['interview__id'],

                    'house_number' => $property['house_number'],
                    'region_id' => $property['region'],
                    'district_id' => $property['district'],
                    'ward_id' => $property['locality'],
                    'type' => $property['property_type'],
                    'usage_type' => UnitUsageTypeStatus::RESIDENTIAL, // Requires mapping
                    'taxpayer_id' => $taxPayer->id,

                    'size' => $this->size, // Inserted manually
                    'property_value' => $this->propertyValue, // Inserted manually
                    'purchase_value' => $this->purchaseValue, // Inserted manually
                    'acquisition_date' => $this->acquisitionDate, // Inserted manually
                    'features' => $property['property_feature'],

                    'ownership_type_id' => $this->ownershipTypes->where('name', $this->ownershipType)->firstOrFail()->id, // Required
                    'institution_name' => $this->institutionName, // Required of ownership type is not private
                ]);

                $owner = explode(' ', $property['owner']['fullName']);
                PropertyOwner::create([
                    'first_name' => $owner[0] ?? '',
                    'middle_name' => $owner[1] ?? '',
                    'last_name' => $owner[2] ?? '',
                    'mobile' => $property['owner']['phone_no'] ?? '',
                    'email' => $property['owner']['email_address'],
                    'address' => 'N/A',
                    'id_type' => $taxPayer->id_type,
                    'id_number' => $this->identifierNumber,
                    'property_id' => $generatedProperty->id
                ]);

                $agentName = explode(' ', $property['agent']['name_of_person']);
                if ($property['agent']['name_of_person'] || $property['agent']['name_of_company']) {
                    PropertyAgent::create([
                        'first_name' => $agentName[0] ?? '',
                        'middle_name' => $agentName[1] ?? '',
                        'last_name' => $agentName[2] ?? '',
                        'company_name' => $property['agent']['name_of_company'] ?? '',
                        'mobile' => $property['agent']['phone_no_1'] ?? '',
                        'alt_mobile' => $property['agent']['phone_no_2'] ?? '',
                        'email' => $property['agent']['email'],
                        'property_id' => $generatedProperty->id
                    ]);
                }

                if ($property['property_type'] === PropertyTypeStatus::STOREY_BUSINESS || $property['property_type'] === PropertyTypeStatus::RESIDENTIAL_STOREY) {
                    for ($i = 0; $i < $property['number_of_storey']; $i++) {
                        PropertyStorey::create([
                            'number' => $i + 1,
                            'property_id' => $generatedProperty->id
                        ]);
                    }
                }

                // Update Status
                $generatedProperty->status = PropertyStatus::APPROVED;
                $generatedProperty->urn = $this->generateURN($generatedProperty);
                $generatedProperty->save();

                $amount = $this->getPayableAmount($generatedProperty);

                // Generate Bill
                $propertyPayment = PropertyPayment::create([
                    'property_id' => $generatedProperty->id,
                    'financial_year_id' => FinancialYear::where('code', Carbon::now()->year)->firstOrFail()->id,
                    'currency_id' => Currency::where('iso', 'TZS')->firstOrFail()->id,
                    'amount' => $amount,
                    'interest' => 0,
                    'total_amount' => $amount,
                    'payment_date' => Carbon::now()->addMonths(3),
                    'curr_payment_date' => Carbon::now()->addMonths(3),
                    'payment_status' => BillStatus::SUBMITTED,
                    'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
                ]);

                $this->generatePropertyTaxControlNumber($propertyPayment);

            }

            foreach ($this->additionalProperties as $additionalProperty) {
                if ($additionalProperty['type'] === PropertyTypeStatus::HOTEL) {
                    if (is_null($additionalProperty['starId'])) {
                        $this->customAlert('warning', 'Missing Number of Stars for Hotel');
                        return;
                    }
                    $hotelStarId = PropertyTaxHotelStar::where('no_of_stars', $additionalProperty['starId'])->firstOrFail()->id;
                }

                $generatedPropertyII = Property::create([
                    'name' => $additionalProperty['name'] ?? null, // Inserted manually
                    'hotel_stars_id' => $hotelStarId ?? null,

                    'house_number' => $additionalProperty['house_number'],
                    'region_id' => Region::findOrFail($this->addRegionId)->name,
                    'district_id' => District::findOrFail($this->addDistrictId)->name,
                    'ward_id' => Ward::findOrFail($this->addWardId)->name,
                    'type' => $additionalProperty['type'],
                    'usage_type' => UnitUsageTypeStatus::RESIDENTIAL, // Requires mapping
                    'taxpayer_id' => $taxPayer->id,

                    'size' => $additionalProperty['size'], // Inserted manually
                    'property_value' => $additionalProperty['propertyValue'], // Inserted manually
                    'purchase_value' => $additionalProperty['purchaseValue'], // Inserted manually
                    'acquisition_date' => $additionalProperty['acquisitionDate'], // Inserted manually
                    'features' => $additionalProperty['property_feature'],

                    'ownership_type_id' => $this->ownershipTypes->where('name', $this->ownershipType)->firstOrFail()->id, // Required
                ]);

                if ($additionalProperty['type'] === PropertyTypeStatus::STOREY_BUSINESS || $additionalProperty['type'] === PropertyTypeStatus::RESIDENTIAL_STOREY) {
                    for ($i = 0; $i < $additionalProperty['number_of_storey']; $i++) {
                        PropertyStorey::create([
                            'number' => $i + 1,
                            'property_id' => $generatedPropertyII->id
                        ]);
                    }
                }

                // Update Status
                $generatedPropertyII->status = PropertyStatus::APPROVED;
                $generatedPropertyII->urn = $this->generateURN($generatedPropertyII);
                $generatedPropertyII->save();

                $amount = $this->getPayableAmount($generatedPropertyII);

                // Generate Bill
                $propertyPayment = PropertyPayment::create([
                    'property_id' => $generatedPropertyII->id,
                    'financial_year_id' => FinancialYear::where('code', Carbon::now()->year)->firstOrFail()->id,
                    'currency_id' => Currency::where('iso', 'TZS')->firstOrFail()->id,
                    'amount' => $amount,
                    'interest' => 0,
                    'total_amount' => $amount,
                    'payment_date' => Carbon::now()->addMonths(3),
                    'curr_payment_date' => Carbon::now()->addMonths(3),
                    'payment_status' => BillStatus::SUBMITTED,
                    'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
                ]);

                $this->generatePropertyTaxControlNumber($propertyPayment);
            }

            DB::commit();

            // sign taxpayer
            $this->sign($taxPayer);

            if (!$taxPayer) {
                // Send email and password for OTP
                event(new SendSms('taxpayer-registration', $taxPayer->id, ['code' => $password]));
                if ($taxPayer->email) {
                    event(new SendMail('taxpayer-registration', $taxPayer->id, ['code' => $password]));
                }
            }

            $this->customAlert('success', 'Owner properties have been registered successful');
            return redirect()->route('property-tax.index');
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong, Please try again');
            return;
        }

    }

    private function createKYC()
    {
        $owner = explode(' ', $this->properties[0]['owner']['fullName']);
        $data = [
            'first_name' => $owner[0],
            'middle_name' => $owner[1] ?? '',
            'last_name' => $owner[2],
            'mobile' => $this->properties[0]['owner']['phone_no'],
            'email' => $this->properties[0]['owner']['email_address'] ?? '',
            'region_id' => '1',
            'district_id' => '1',
            'ward_id' => '1',
            'street_id' => '1',
            'physical_address' => 'N/A',
            'is_citizen' => !is_null($this->properties[0]['owner']['passport']),
        ];

        if (!is_null($this->properties[0]['owner']['nida']) && !is_null($this->properties[0]['owner']['zanID'])) {
            $idType = IDType::where('name', IDType::NIDA_ZANID)->first()->id;
            $data['zanid_no'] = $this->properties[0]['owner']['zanID'];
            $data['nida_no'] = $this->properties[0]['owner']['nida'];
        }

        if (!is_null($this->properties[0]['owner']['nida'])) {
            $idType = IDType::where('name', IDType::NIDA)->first()->id;
            $data['nida_no'] = $this->properties[0]['owner']['nida'];
        }

        if (!is_null($this->properties[0]['owner']['zanID'])) {
            $idType = IDType::where('name', IDType::ZANID)->first()->id;
            $data['zanid_no'] = $this->properties[0]['owner']['zanID'];
        }

        if (!is_null($this->properties[0]['owner']['tin'])) {
            $data['tin_no'] = $this->properties[0]['owner']['tin'];
            $idType = IDType::where('name', IDType::TIN)->first()->id;
        }

        $countryId = Country::where('nationality', 'Tanzanian')->first()->id;
        $data['country_id'] = $countryId;

        // If owner has passport a country input must be shown + permit number
        if (!is_null($this->properties[0]['owner']['passport'])) {
            $data['passport_no'] = $this->properties[0]['owner']['passport'];
            $data['permit_number'] = $this->permitNumber; // Enter manually
            $data['country_id'] = $this->nationality;
            $idType = IDType::where('name', IDType::PASSPORT)->first()->id;
        }


        $data['id_type'] = $idType;

        return KYC::create($data);
    }

    public function removeProperty($i)
    {
        unset($this->additionalProperties[$i]);
    }


    public function search()
    {
//        $this->validate(
//            [
//                'identifierType' => 'required',
//                'identifierNumber' => 'required'
//            ]
//        );

        // Query from API
//        $ssService = new SurveySolutionInternalService();
//        $response = $ssService->getPropertyInformation($this->identifierType, $this->identifierNumber);
//
//        if ($response && isset($response['totalItems'])) {
//            if ($response['totalItems'] > 0) {
//                $datas = $response['propertyInforList'];
//            } else {
//                $this->customAlert('warning', 'No Data Found');
//                return;
//            }
//        } else {
//            $this->customAlert('warning', 'Something went wrong, Please try again');
//            return;
//        }
        $datas = [
            [
                "interview__id" => "5fc4a773-ea76-4bee-b190-c4816a2b8fc1",
                "location" => [
                    "Accuracy" => 8.34294319152832,
                    "Altitude" => 46.92864990234375,
                    "Latitude" => -5.91033177,
                    "Longitude" => 39.29912219,
                    "Timestamp" => "2023-10-25T09:10:53.908+00:00"
                ],
                "owner" => [
                    "zra_ref_no" => "ZU0000000",
                    "zra_number" => "Z000000000",
                    "fullName" => "SIHABA ALI HAJI",
                    "mail_address" => "0",
                    "phone_no" => "0772-882-756",
                    "email_address" => "0",
                    "tin" => 0,
                    "nida" => null,
                    "zanID" => "000000000",
                    "passport" => "0"
                ],
                "agent" => [
                    "name_of_person" => "SIHABA ALI HAJI",
                    "name_of_company" => "881429566",
                    "phone_no_1" => "0772-882-756",
                    "phone_no_2" => "0000-000-000",
                    "email" => "0"
                ],
                "valuation" => [
                    "property_id" => null,
                    "property_feature" => null
                ],
                "region" => "Kaskazini Unguja",
                "district" => "Kaskazini A",
                "locality" => "Gamba",
                "property_address" => "GAMBA MAJENZINI",
                "meter_no" => "54172277003",
                "house_number" => "5",
                "postcode" => "5/4",
                "road_name" => "GAMBA",
                "property_type" => "Jengo la Kondominiamu",
                "number_of_storey" => "0",
                "type_of_business" => "Nyumba za wageni",
                "hotel_star" => null,
                "property_feature" => null
            ],
        ];

        foreach ($datas as $property) {
            $this->properties[] = $property;
        }

    }

    public function updated($propertyName)
    {
//        if ($propertyName === 'region_id') {
//            $this->reset('district_id', 'ward_id', 'wards', 'street_id', 'streets');
//            $districts = District::where('region_id', $this->region_id)
//                ->where('is_approved', 1)
//                ->select('id', 'name')
//                ->get();
//            $this->districts = json_decode($districts, true);
//        }
//
//        if ($propertyName === 'district_id') {
//            $this->reset('ward_id', 'streets', 'street_id');
//            $wards = Ward::where('district_id', $this->district_id)
//                ->where('is_approved', 1)
//                ->select('id', 'name')
//                ->get();
//            $this->wards = json_decode($wards, true);
//        }
//
//        if ($propertyName === 'ward_id') {
//            $this->reset('street_id');
//            $streets = Street::where('ward_id', $this->ward_id)
//                ->where('is_approved', 1)
//                ->select('id', 'name')
//                ->get();
//            $this->streets = json_decode($streets, true);
//        }

        if ($propertyName === 'addRegionId') {
            $this->reset('addDistrictId', 'addWardId', 'wards', 'streets');
            $districts = District::where('region_id', $this->addRegionId)
                ->where('is_approved', 1)
                ->select('id', 'name')
                ->get();
            $this->districts = json_decode($districts, true);
        }

        if ($propertyName === 'addDistrictId') {
            $this->reset('addWardId', 'streets');
            $wards = Ward::where('district_id', $this->addDistrictId)
                ->where('is_approved', 1)
                ->select('id', 'name')
                ->get();
            $this->wards = json_decode($wards, true);
        }


    }


    public function render()
    {
        return view('livewire.property-tax.survey-solution.initial');
    }
}
