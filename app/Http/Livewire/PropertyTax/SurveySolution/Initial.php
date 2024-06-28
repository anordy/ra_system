<?php

namespace App\Http\Livewire\PropertyTax\SurveySolution;


use App\Enum\BillStatus;
use App\Enum\GeneralConstant;
use App\Enum\PropertyOwnershipTypeStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Enum\SurveySolutionType;
use App\Enum\UnitUsageTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Business;
use App\Models\Country;
use App\Models\Currency;
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
use App\Models\Taxpayer;
use App\Services\Api\SurveySolutionInternalService;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PropertyTaxTrait;
use App\Traits\VerificationTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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


    public function submit()
    {
        $this->validate([
            'ownershipType' => 'required|alpha_gen',
            'institutionName' => ['nullable', 'strip_tag', 'alpha_space', 'required_if:ownershipType,' . PropertyOwnershipTypeStatus::RELIGIOUS . ',' . PropertyOwnershipTypeStatus::GOVERNMENT],
        ]);

        try {
            DB::beginTransaction();

            // Check if existing owner details exist, If yes associate with existing account otherwise create an account
            // Check via email, mobile, nida, zanid
            $taxPayer = Taxpayer::where('mobile', str_replace('-', '', $this->properties[0]['owner']['phone_no']) ?? '')->first();

            if (!is_null($this->properties[0]['owner']['tin']) || $this->properties[0]['owner']['tin'] != 0) {
                $business = Business::where('tin', $this->properties[0]['owner']['tin'])->first();
                if ($business) {
                    $taxPayer = $business->responsiblePerson;
                }
            }

            $isTaxpayerNew = true;
            $permitted_chars = '23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ!@#%';
            $password = substr(str_shuffle($permitted_chars), 0, 8);

            if (!$taxPayer) {
                $kyc = $this->createKYC();

                if (!$kyc) {
                    $this->customAlert('warning', 'Property Tax Account Could not be created, missing data');
                    return;
                }

                $data = $kyc->makeHidden(['id', 'created_at', 'updated_at', 'deleted_at', 'verified_by', 'comments'])->toArray();
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

            } else {
                $isTaxpayerNew = false;
            }

            // Save property information
            foreach ($this->properties as $index => $property) {
                $index++;
                $hotelStarId = null;
                if ($property['property_type'] === PropertyTypeStatus::HOTEL) {
                    if (is_null($property['hotel_star'])) {
                        $this->customAlert('warning', 'Missing Number of Stars for Hotel');
                        return;
                    }
                    $hotelStarId = PropertyTaxHotelStar::where('no_of_stars', $property['hotel_star'])->firstOrFail()->id;
                }

                $doesInterviewExist = Property::where('interview_id', $property['interview__id'])->first();

                if ($doesInterviewExist) {
                    continue;
                } else {
                    if (!is_null($property['property_type'])) {
                        if ($property['property_type'] === PropertyTypeStatus::STOREY_BUSINESS || $property['property_type'] === PropertyTypeStatus::RESIDENTIAL_STOREY) {
                            if ($property['number_of_storey'] > 0) {
                                $this->generateProperty($property, $taxPayer, $index, $hotelStarId);
                            }
                        } else {
                            $this->generateProperty($property, $taxPayer, $index, $hotelStarId);
                        }

                    }
                }

            }

            DB::commit();

            // sign taxpayer
            $this->sign($taxPayer);

            if ($taxPayer) {
                // Send email and password for OTP
                if ($isTaxpayerNew) {
                    event(new SendSms('taxpayer-registration', $taxPayer->id, ['code' => $password]));
                    if ($taxPayer->email) {
                        event(new SendMail('taxpayer-registration', $taxPayer->id, ['code' => $password]));
                    }
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

    private function generateProperty($property, $taxPayer, $index, $hotelStarId) {
        $generatedProperty = Property::create([
            'name' => $this->name, // Inserted manually
            'hotel_stars_id' => $hotelStarId ?? null,
            'interview_id' => $property['interview__id'],
            'house_number' => $property['house_number'],
            'region_id' => $property['region'],
            'district_id' => $property['district'],
            'ward_id' => $property['locality'],
            'type' => $property['property_type'],
            'usage_type' => UnitUsageTypeStatus::RESIDENTIAL,
            'taxpayer_id' => $taxPayer->id,
            'size' => $this->size, // Inserted manually
            'property_value' => $this->propertyValue, // Inserted manually
            'purchase_value' => $this->purchaseValue, // Inserted manually
            'acquisition_date' => $this->acquisitionDate, // Inserted manually
            'features' => $property['property_feature'],
            'ownership_type_id' => $this->ownershipTypes->where('name', $this->ownershipType)->firstOrFail()->id, // Required
            'institution_name' => $this->institutionName, // Required of ownership type is not private
            'staff_id' => Auth::id()
        ]);

        $owner = explode(' ', $property['owner']['fullName']);
        PropertyOwner::create([
            'first_name' => $owner[0] ?? '',
            'middle_name' => $owner[1] ?? '',
            'last_name' => $owner[2] ?? $owner[1],
            'mobile' => $property['owner']['phone_no'] ?? '',
            'email' => $property['owner']['email_address'],
            'address' => 'N/A',
            'id_type' => $taxPayer->id_type,
            'id_number' => $this->identifierNumber,
            'property_id' => $generatedProperty->id
        ]);

        $agentName = explode(' ', $property['agent']['name_of_person']);

        if (!is_null($property['agent']['name_of_person']) || !is_null($property['agent']['name_of_company'])) {
            PropertyAgent::create([
                'first_name' => $agentName[0] ?? '',
                'middle_name' => $agentName[1] ?? '',
                'last_name' => $agentName[2] ?? $agentName[1],
                'company_name' => $property['agent']['name_of_company'] ?? '',
                'mobile' => $property['agent']['phone_no_1'] ?? '',
                'alt_mobile' => $property['agent']['phone_no_2'] ?? '',
                'email' => $property['agent']['email'] ?? '',
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

        if (!$amount || $amount < GeneralConstant::ZERO_INT) {
            $this->customAlert('warning', "Invalid property amount on property number {$index}");
            return;
        }

        // Generate Bill
        PropertyPayment::create([
            'property_id' => $generatedProperty->id,
            'financial_year_id' => FinancialYear::select('id')->where('code', Carbon::now()->year)->firstOrFail()->id,
            'currency_id' => Currency::select('id')->where('iso', 'TZS')->firstOrFail()->id,
            'amount' => $amount,
            'interest' => 0,
            'total_amount' => $amount,
            'payment_date' => Carbon::now()->addMonths(3),
            'curr_payment_date' => Carbon::now()->addMonths(3),
            'payment_status' => BillStatus::SUBMITTED,
            'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
        ]);
    }

    private function createKYC()
    {
        if (!$this->properties[0]['owner']['phone_no']) {
            $this->customAlert('warning', 'Owner must have a phone number');
            return;
        }

        if (!$this->properties[0]['owner']['fullName'] || $this->properties[0]['owner']['fullName'] == GeneralConstant::ZERO_INT) {
            $this->customAlert('warning', 'Owner does not have a name');
            return;
        }

        $owner = explode(' ', $this->properties[0]['owner']['fullName']);
        $data = [
            'first_name' => $owner[0] ?? '',
            'middle_name' => $owner[1] ?? '',
            'last_name' => $owner[2] ?? '',
            'mobile' => str_replace('-', '', $this->properties[0]['owner']['phone_no']),
            'email' => $this->properties[0]['owner']['email_address'] == GeneralConstant::ZERO_INT ? null : $this->properties[0]['owner']['email_address'],
            'physical_address' => $this->properties[0]['post_code'] ?? 'N/A',
            'is_citizen' => !is_null($this->properties[0]['owner']['passport']),
            'permit_number' => $this->permitNumber ?? ''
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

        $data['id_type'] = $idType;

        return KYC::create($data);
    }

    public function removeProperty($i)
    {
        unset($this->additionalProperties[$i]);
    }


    public function search()
    {
        $this->validate(
            [
                'identifierType' => 'required|alpha_gen',
                'identifierNumber' => 'required|alpha_gen'
            ]
        );

        $this->properties = [];

        if ($this->identifierType === SurveySolutionType::MOBILE) {
            if(!preg_match('/^(06|07)\d{8}$/', $this->identifierNumber) && !ctype_digit($this->identifierNumber)) {
                $this->customAlert('warning', 'Invalid mobile number provided');
                return;
            }
            $part1 = substr($this->identifierNumber, 0, 4);
            $part2 = substr($this->identifierNumber, 4, 3);
            $part3 = substr($this->identifierNumber, 7, 3);
            $this->identifierNumber = $part1 . '-' . $part2 . '-' . $part3;
        }

        $ssService = new SurveySolutionInternalService();
        $response = $ssService->getPropertyInformation($this->identifierType, $this->identifierNumber);

        if ($response && isset($response['totalItems'])) {
            if ($response['totalItems'] > 0) {
                $datas = $response['propertyInforList'];
            } else {
                $this->customAlert('warning', 'No Data Found');
                return;
            }
        }  else if ($response && isset($response['error'])) {
            $this->customAlert('warning', $response['error'] ?? 'Something went wrong getting properties data, Please try again later');
            return;
        } else {
            $this->customAlert('warning', 'Something went wrong, Please try again');
            return;
        }

        foreach ($datas as $property) {
            $exists = Property::where('interview_id', $property['interview__id'])->exists();
            if (!$exists) {
                $this->properties[] = $property;
            }
        }

    }


    public function render()
    {
        return view('livewire.property-tax.survey-solution.initial');
    }
}
