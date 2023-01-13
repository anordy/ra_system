<?php

namespace App\Http\Livewire\Kyc;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Country;
use App\Models\District;
use App\Models\DualControl;
use App\Models\IDType;
use App\Models\KYC;
use App\Models\KycAmendmentRequest;
use App\Models\Region;
use App\Models\Street;
use App\Models\Ward;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class KycAmendmentRequestAddModal extends Component
{
    use LivewireAlert, WorkflowProcesssingTrait;

    public $kyc;
    public $kyc_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $mobile;
    public $alt_mobile;
    public $physical_address;
    public $old_values;
    public $is_citizen;
    public $nida;
    public $countries;
    public $passportNo;
    public $permitNumber;
    public $nationality;
    public $id_type;
    public $zanid;
    public $region, $regions=[];
    public $district, $districts=[];
    public $ward, $wards=[];
    public $street, $streets=[];

    public function mount($id)
    {
        $this->kyc = KYC::find($id);
        $this->kyc_id = $this->kyc->id;
        $this->first_name = $this->kyc->first_name;
        $this->middle_name = $this->kyc->middle_name;
        $this->last_name = $this->kyc->last_name;
        $this->email = $this->kyc->email;
        $this->mobile = $this->kyc->mobile;
        $this->alt_mobile = $this->kyc->alt_mobile;
        $this->physical_address = $this->kyc->physical_address;
        $this->is_citizen = $this->kyc->is_citizen;
        $this->nida = $this->kyc->nida_no;
        $this->passportNo = $this->kyc->passport_no;
        $this->permitNumber = $this->kyc->permit_number;
        $this->nationality = $this->kyc->country_id;
        $this->zanid = $this->kyc->zanid_no;
        $this->region = $this->kyc->region_id;
        $this->district = $this->kyc->district_id;
        $this->ward = $this->kyc->ward_id;
        $this->street = $this->kyc->street_id;
        $this->id_type = $this->kyc->id_type;
        $this->countries = Country::select('id', 'nationality')->where('name', '!=', 'Tanzania')->get();
        $this->regions = Region::where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
        $this->districts = District::where('region_id', $this->region)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
        $this->wards = Ward::where('district_id', $this->district)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
        $this->streets = Street::where('ward_id', $this->ward)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();

        $this->old_values = [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'alt_mobile' => $this->alt_mobile,
            'physical_address' => $this->physical_address,
            'region_id' => $this->region,
            'district_id' => $this->district,
            'ward_id' => $this->ward,
            'street_id' => $this->street,
            'is_citizen' => $this->is_citizen,
            'nida_no' => $this->nida,
            'zanid_no' => $this->zanid,
            'permit_number' => $this->permitNumber,
            'passport_no' => $this->passportNo,
            'country_id' => $this->nationality,
            'id_type' => $this->id_type,
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'region') {
            $this->districts = District::where('region_id', $this->region)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
            $this->reset('district','ward','wards','street','streets');
        }

        if ($propertyName === 'district') {
            $this->wards = Ward::where('district_id', $this->district)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
            $this->reset('ward','streets','street');
        }

        if ($propertyName === 'ward') {
            $this->streets = Street::where('ward_id', $this->ward)->where('is_approved', DualControl::APPROVE)->select('id', 'name')->get();
            $this->reset('street');
        }
    }

    public function render()
    {
        return view('livewire.kyc.kyc-amendment-request-add-modal');
    }


    protected function rules()
    {
        return  [
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'email' => 'nullable:email|unique:kycs,email,' . $this->kyc->id . ',id',
            'mobile' => 'required|unique:kycs,mobile,'. $this->kyc->id . ',id|size:10',
            'alt_mobile' => 'nullable|size:10',
            'physical_address' => 'required',
            'region' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'street' => 'required',
            'nida' => 'exclude_if:is_citizen,0|required_without:zanid|nullable|digits:20|unique:kycs,nida_no,' . $this->kyc->id . ',id|unique:taxpayers,nida_no',
            'zanid' => 'exclude_if:is_citizen,0|required_without:nida|nullable|digits:9|unique:kycs,zanid_no,' . $this->kyc->id . ',id|unique:taxpayers,zanid_no',
            'nationality' => 'required_if:is_citizen,0',
            'passportNo' => 'nullable|required_if:is_citizen,0|exclude_if:is_citizen,1|unique:kycs,passport_no,' . $this->kyc->id . ',id|unique:taxpayers,passport_no|digits_between:8,15',
            'permitNumber' => 'nullable|required_if:is_citizen,0|exclude_if:is_citizen,1|unique:taxpayers,permit_number,' . $this->kyc->id . ',id|string|min:10|max:20',
            'nida.required_without' => 'Please provide your NIDA number',
            'zanid.required_without' => 'Please provide your ZANID number',
        ];
    }
    public function submit()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $new_values = [
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'alt_mobile' => $this->alt_mobile,
                'physical_address' => $this->physical_address,
                'region_id' => $this->region,
                'district_id' => $this->district,
                'ward_id' => $this->ward,
                'street_id' => $this->street,
                'is_citizen' => $this->is_citizen,
                'country_id' => $this->nationality,
                'nida_no' => $this->nida,
                'zanid_no' => $this->zanid,
                'id_type' => $this->id_type,
                'permit_number' => $this->permitNumber,
                'passport_no' => $this->passportNo,
            ];

            if ($this->is_citizen) {
                if ($this->nida && $this->zanid) {
                    $idType = IDType::where('name', IDType::NIDA_ZANID)->first()->id;
                    $new_values['zanid_no'] = $this->zanid;
                    $new_values['nida_no'] = $this->nida;
                }

                if ($this->nida && !$this->zanid) {
                    $idType = IDType::where('name', IDType::NIDA)->first()->id;
                    $new_values['nida_no'] = $this->nida;
                }

                if (!$this->nida && $this->zanid) {
                    $idType = IDType::where('name', IDType::ZANID)->first()->id;
                    $new_values['zanid_no'] = $this->zanid;
                }

                $countryId = Country::where('nationality', 'Tanzanian')->first()->id;
                $new_values['id_type'] = $idType;
                $new_values['country_id'] = $countryId;
            } else {
                $idType = IDType::where('name', IDType::PASSPORT)->first()->id;
                $new_values['id_type'] = $idType; // Get Tanzania ID
                $new_values['passport_no'] = $this->passportNo;
                $new_values['permit_number'] = $this->permitNumber;
                $new_values['country_id'] = $this->nationality;
            }

            $kyc_amendment = KycAmendmentRequest::create([
                'kyc_id' => $this->kyc_id,
                'old_values' => json_encode($this->old_values),
                'new_values' => json_encode($new_values),
                'status' => KycAmendmentRequest::PENDING,
                'created_by' => auth()->user()->id,
                'marking' => null,
            ]);

            if ($kyc_amendment->status === KycAmendmentRequest::PENDING) {
                $this->registerWorkflow(get_class($kyc_amendment), $kyc_amendment->id);
                $this->doTransition('application_submitted', ['status' => 'approved', 'comment' => null]);
            }

            DB::commit();

            $message = 'We are writing to inform you that some of your ZIDRAS kyc personal information has been requested to be changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
            $this->sendEmailToUser($this->kyc, $message);

            session()->flash('success', 'Amendment details submitted. Waiting approval.');
            $this->redirect(route('kycs-amendment.index'));

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function sendEmailToUser($data, $message)
    {
        $smsPayload = [
            'phone' => $data->phone,
            'message' => 'Hello, {$data->first_name}. {$message}',
        ];

        $emailPayload = [
            'email' => $data->email,
            'userName' => $data->first_name,
            'message' => $message,
        ];

        event(new SendSms('taxpayer-amendment-notification', $smsPayload));
        event(new SendMail('taxpayer-amendment-notification', $emailPayload));
    }
}
