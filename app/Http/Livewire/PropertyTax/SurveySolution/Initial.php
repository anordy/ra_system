<?php

namespace App\Http\Livewire\PropertyTax\SurveySolution;


use App\Enum\PropertyOwnershipTypeStatus;
use App\Enum\PropertyTypeStatus;
use App\Enum\UnitUsageTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Country;
use App\Models\IDType;
use App\Models\KYC;
use App\Models\PropertyTax\Property;
use App\Models\PropertyTax\PropertyAgent;
use App\Models\PropertyTax\PropertyOwner;
use App\Models\PropertyTax\PropertyOwnershipType;
use App\Models\PropertyTax\PropertyStorey;
use App\Models\PropertyTax\PropertyTaxHotelStar;
use App\Models\Taxpayer;
use App\Traits\CustomAlert;
use App\Traits\VerificationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Initial extends Component
{
    use CustomAlert, VerificationTrait;

    public $identifierType, $identifierNumber;
    public $properties, $nationality, $permitNumber;
    public $size, $name, $features, $propertyValue, $purchaseValue, $acquisitionDate, $ownershipType, $institutionName;
    public $ownershipTypes = [];
    public $countries = [];


    public function mount()
    {
        $this->ownershipTypes = PropertyOwnershipType::select('id', 'name')->get();
        $this->countries = DB::table('countries')
            ->select('id', 'nationality')
            ->where('name', '!=', Country::TANZANIA)
            ->where('is_approved', 1)
            ->get();
    }

    public function submit()
    {
        $this->validate(
            [
                'ownershipType' => 'required',
                'institutionName' => ['nullable', 'strip_tag' ,'required_if:ownershipType,' . PropertyOwnershipTypeStatus::RELIGIOUS . ',' . PropertyOwnershipTypeStatus::GOVERNMENT],
            ]
        );

        if (isset($this->properties[0]['owner']['passport']) && !is_null($this->properties[0]['owner']['passport'])) {
            $this->validate(
                [
                    'nationality' => 'required',
                    'permitNumber' => 'required|numeric'
                ]
            );
        }

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
            foreach ($this->properties as $property) {
                if ($property['property_type'] === PropertyTypeStatus::HOTEL) {
                    if (is_null($property['hotel_star'])) {
                        throw new \Exception('Missing Number of Stars for Hotel');
                    }
                    $hotelStarId = PropertyTaxHotelStar::where('no_of_stars', $property['hotel_star'])->firstOrFail()->id;
                }

                $generatedProperty = Property::create([
                    'name' => $this->name, // Inserted manually
                    'hotel_stars_id' => $hotelStarId ?? null,
                    'interview_id' => $property['interview__id'],

                    'house_number' => $property['house_number'],
                    'region_id' => $property['region'],
                    'district_id' => $property['district'],
                    'ward_id' => $property['locality'],
                    'street_id' => null,
                    'type' => PropertyTypeStatus::OTHER, // $property['property_type']
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
                    'gender' => '',
                    'date_of_birth' => '',
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
                            'number' => $i+1,
                            'property_id' => $generatedProperty->id
                        ]);
                    }
                }
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
            'mobile' => $this->properties[0]['owner']['phone_no'] ?? '',
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


    public function search()
    {
        $this->validate(
            [
                'identifierType' => 'required',
                'identifierNumber' => 'required'
            ]
        );

        // Query from API

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
            [
                "interview__id" => "d3bce707-72ac-4bcd-b585-cbb6309b91e0",
                "location" => [
                    "Accuracy" => 16.13362693786621,
                    "Altitude" => 53.77685546875,
                    "Latitude" => -5.91054921,
                    "Longitude" => 39.2989792,
                    "Timestamp" => "2023-10-25T09:22:52.901+00:00"
                ],
                "owner" => [
                    "zra_ref_no" => "ZU0000000",
                    "zra_number" => "Z000000000",
                    "fullName" => "HASSAN MAHMOUD JUMA",
                    "mail_address" => "0",
                    "phone_no" => "0715-402-273",
                    "email_address" => "0",
                    "tin" => 0,
                    "nida" => "00000000000000000000",
                    "zanID" => "000000000",
                    "passport" => "0"
                ],
                "agent" => [
                    "name_of_person" => "HASSAN MAHMOUD JUMA",
                    "name_of_company" => "710024138",
                    "phone_no_1" => "0715-402-273",
                    "phone_no_2" => "0773-631-211",
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
                "meter_no" => "54172271717",
                "house_number" => "5",
                "postcode" => "5/6",
                "road_name" => "GAMBA",
                "property_type" => "Jengo la Kondominiamu",
                "number_of_storey" => "0",
                "type_of_business" => "Nyumba za wageni",
                "hotel_star" => null,
                "property_feature" => null
            ],
            [
                "interview__id" => "7a12e4b1-2cbc-4038-bf07-9ef7109ce883",
                "location" => [
                    "Accuracy" => 13.469212532043457,
                    "Altitude" => 42.612060546875,
                    "Latitude" => -5.91050087,
                    "Longitude" => 39.2991197,
                    "Timestamp" => "2023-10-25T09:29:03.908+00:00"
                ],
                "owner" => [
                    "zra_ref_no" => "ZU0000000",
                    "zra_number" => "Z000000000",
                    "fullName" => "ALI SHAABAN SHAIB",
                    "mail_address" => "0",
                    "phone_no" => "0778-734-142",
                    "email_address" => "0",
                    "tin" => 0,
                    "nida" => "00000000000000000000",
                    "zanID" => "000000000",
                    "passport" => "0"
                ],
                "agent" => [
                    "name_of_person" => "ALI SHAABAN SHAIB",
                    "name_of_company" => "070010224",
                    "phone_no_1" => "0778-734-142",
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
                "meter_no" => "00000000000",
                "house_number" => "5",
                "postcode" => "5/5",
                "road_name" => "GAMBA",
                "property_type" => "Jengo la Kondominiamu",
                "number_of_storey" => "0",
                "type_of_business" => "Nyumba za wageni",
                "hotel_star" => null,
                "property_feature" => null
            ],
            [
                "interview__id" => "94c127af-6515-4ca9-92c0-db8622918cf1",
                "location" => [
                    "Accuracy" => 9.047110557556152,
                    "Altitude" => 46.3594970703125,
                    "Latitude" => -5.91018537,
                    "Longitude" => 39.2990662,
                    "Timestamp" => "2023-10-25T09:41:02+00:00"
                ],
                "owner" => [
                    "zra_ref_no" => "ZU0000000",
                    "zra_number" => "Z000000000",
                    "fullName" => "MUSSA MAKAME BAKARI",
                    "mail_address" => "0",
                    "phone_no" => "0719-503-008",
                    "email_address" => "0",
                    "tin" => 0,
                    "nida" => "00000000000000000000",
                    "zanID" => "000000000",
                    "passport" => "0"
                ],
                "agent" => [
                    "name_of_person" => "MUSSA MAKAME BAKARI",
                    "name_of_company" => "600011530",
                    "phone_no_1" => "0719-503-008",
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
                "property_address" => "GAMBA MAJENZI",
                "meter_no" => "00000000000",
                "house_number" => "5",
                "postcode" => "5/8",
                "road_name" => "GAMBA",
                "property_type" => "Jengo la Kondominiamu",
                "number_of_storey" => "0",
                "type_of_business" => "Nyumba za wageni",
                "hotel_star" => null,
                "property_feature" => null
            ],
            [
                "interview__id" => "43dc0b92-a51e-406c-bffe-d0572d0d9aef",
                "location" => [
                    "Accuracy" => 9.95927619934082,
                    "Altitude" => 41.8427734375,
                    "Latitude" => -5.910242,
                    "Longitude" => 39.29906195,
                    "Timestamp" => "2023-10-25T09:48:42.913+00:00"
                ],
                "owner" => [
                    "zra_ref_no" => "ZU0000000",
                    "zra_number" => "Z000000000",
                    "fullName" => "SHEHA JABIR MAKAME",
                    "mail_address" => "0",
                    "phone_no" => "0777-324-253",
                    "email_address" => "0",
                    "tin" => 0,
                    "nida" => "00000000000000000000",
                    "zanID" => "000000000",
                    "passport" => "0"
                ],
                "agent" => [
                    "name_of_person" => "SHEHA JABIR MAKAME",
                    "name_of_company" => "060004617",
                    "phone_no_1" => "0777-324-253",
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
                "property_address" => "GAMBA MAJENZI",
                "meter_no" => "00000000000",
                "house_number" => "5",
                "postcode" => "5/9",
                "road_name" => "GAMBA",
                "property_type" => "Jengo la Kondominiamu",
                "number_of_storey" => "1",
                "type_of_business" => "Nyumba za wageni",
                "hotel_star" => null,
                "property_feature" => null
            ],
        ];


        foreach ($datas as $property) {
            $this->properties[] = $property;
        }

    }

    public function render()
    {
        return view('livewire.property-tax.survey-solution.initial');
    }
}
