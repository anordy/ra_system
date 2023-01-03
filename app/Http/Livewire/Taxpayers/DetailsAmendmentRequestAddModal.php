<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use App\Models\TaxpayerAmendmentRequest;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DetailsAmendmentRequestAddModal extends Component
{
    use LivewireAlert, WorkflowProcesssingTrait;

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

    public function mount($id)
    {
        $this->taxpayer = Taxpayer::find($id);
        $this->taxpayer_id = $this->taxpayer->id;
        $this->first_name = $this->taxpayer->first_name;
        $this->middle_name = $this->taxpayer->middle_name;
        $this->last_name = $this->taxpayer->last_name;
        $this->email = $this->taxpayer->email;
        $this->mobile = $this->taxpayer->mobile;
        $this->alt_mobile = $this->taxpayer->alt_mobile;
        $this->physical_address = $this->taxpayer->physical_address;

        $this->old_values = [
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'alt_mobile' => $this->alt_mobile,
            'physical_address' => $this->physical_address,
        ];
    }

    public function render()
    {
        return view('livewire.taxpayers.details-amendment-request-add-modal');
    }

    protected $rules = [
        'first_name' => 'required',
        'middle_name' => 'required',
        'last_name' => 'required',
        'email' => 'required',
        'mobile' => 'required',
        'alt_mobile' => 'required',
        'physical_address' => 'required',
    ];

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
