<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Models\Street;
use App\Models\Taxpayer;
use App\Models\TaxpayerAmendmentRequest;
use App\Models\Ward;
use App\Rules\ValidPhoneNo;
use App\Traits\PhoneUtil;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class DetailsAmendmentRequestAddModal extends Component
{
    use CustomAlert, PhoneUtil, WorkflowProcesssingTrait;

    public $taxpayer;
    public $taxpayer_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $mobile;
    public $alt_mobile;
    public $physical_address;
    public $old_values;
    public $region, $regions=[];
    public $district, $districts=[];
    public $ward, $wards=[];
    public $street, $streets=[];

    public function mount($id)
    {
        try {
            $this->taxpayer = Taxpayer::find(decrypt($id));
            if (is_null($this->taxpayer)) {
                abort(404);
            }
            $this->taxpayer_id = $this->taxpayer->id;
            $this->first_name = $this->taxpayer->first_name;
            $this->middle_name = $this->taxpayer->middle_name;
            $this->last_name = $this->taxpayer->last_name;
            $this->email = $this->taxpayer->email;
            $this->mobile = $this->taxpayer->mobile;
            $this->alt_mobile = $this->taxpayer->alt_mobile;
            $this->physical_address = $this->taxpayer->physical_address;
            $this->region = $this->taxpayer->region_id;
            $this->district = $this->taxpayer->district_id;
            $this->ward = $this->taxpayer->ward_id;
            $this->street = $this->taxpayer->street_id;
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
            ];
        } catch (\Exception $exception){
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator.');
        }
    }

    public function updated($propertyName)
    {

        if (!isset($property)) {
            Log::error('Missing property definition');
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }

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
        return view('livewire.taxpayers.details-amendment-request-add-modal');
    }


    protected function rules()
    {
        return  [
            'first_name' => 'required|alpha|max:30',
            'middle_name' => 'nullable|alpha|max:30',
            'last_name' => 'required|alpha|max:30',
            'email' => 'nullable|email|unique:taxpayers,email,' . $this->taxpayer->id . ',id',
            'mobile' => ['required', 'string', 'unique:taxpayers,mobile,'. $this->taxpayer->id . ',id', new ValidPhoneNo()],
            'alt_mobile' => ['nullable', 'string', new ValidPhoneNo()],
            'physical_address' => 'required',
            'region' => 'required|numeric',
            'district' => 'required|numeric',
            'ward' => 'required|numeric',
            'street' => 'required|strip_tag',
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
                'mobile' => $this->getNationalFormat($this->mobile),
                'alt_mobile' => $this->getNationalFormat($this->alt_mobile),
                'physical_address' => $this->physical_address,
                'region_id' => $this->region,
                'district_id' => $this->district,
                'ward_id' => $this->ward,
                'street_id' => $this->street,
            ];

            $taxpayer_amendment = TaxpayerAmendmentRequest::create([
                'taxpayer_id' => $this->taxpayer_id,
                'old_values' => json_encode($this->old_values),
                'new_values' => json_encode($new_values),
                'status' => TaxpayerAmendmentRequest::PENDING,
                'created_by' => auth()->user()->id,
                'marking' => null,
            ]);

            if ($taxpayer_amendment->status === TaxpayerAmendmentRequest::PENDING) {
                $this->registerWorkflow(get_class($taxpayer_amendment), $taxpayer_amendment->id);
                $this->doTransition('application_submitted', ['status' => 'approved', 'comment' => null]);
            }

            DB::commit();

            $message = 'We are writing to inform you that some of your ZIDRAS taxpayer personal information has been requested to be changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
            $this->sendEmailToUser($this->taxpayer, $message);

            session()->flash('success', 'Amendment details submitted. Waiting approval.');
            $this->redirect(route('taxpayers.taxpayer.index'));

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong');
        }
    }

    public function sendEmailToUser($data, $message)
    {
        if ($data && $message){
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
}
