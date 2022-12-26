<?php

namespace App\Http\Livewire\Taxpayers\Details;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\v1\ZanIDController;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Zanid extends Component
{
    use LivewireAlert;

    public $kyc;
    public $is_verified_triggered = false;
    public $zanid_data;
    public $matchesText = 'Match';
    public $notValidText = 'Mismatch';

    protected $listeners = [
        'approve',
        'reject'
    ];

    public function mount($kyc)
    {
        $this->kyc = $kyc;
    }

    public function validateZanID()
    {
         $this->is_verified_triggered = true;
         $zanid_controller = new ZanIDController;
         $this->zanid_data = $zanid_controller->getZanIDData($this->kyc->zanid_no);
    }

    public function compareProperties($kyc_property, $zanid_property)
    {
        $kyc_property = strtolower($kyc_property ?? '');
        $zanid_property = strtolower($zanid_property ?? '');

        return $kyc_property === $zanid_property ? true : false;
    }

    public function acceptIncomingData()
    {
        if (!Gate::allows('kyc_complete')) {
            abort(403);
        }

        $this->alert('warning', 'Are you sure you want to approve taxpayers details ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'approve',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
        ]);
    }

    public function rejectIncomingData()
    {
        if (!Gate::allows('kyc_complete')) {
            abort(403);
        }

        $this->alert('warning', 'Are you sure you want to reject this KYC ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'reject',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'input' => 'textarea',
            'timer' => null,
        ]);
    }

    public function approve()
    {
        try {
            $this->kyc->update([
                'first_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_FIRST_NAME']),
                'middle_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_MIDLE_NAME']),
                'last_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_LAST_NAME']),
                'zanid_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            $this->alert('success', 'Taxpayer details have been approved!');
            return redirect()->route('taxpayers.enroll-fingerprint', [encrypt($this->kyc->id)]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help!');
        }

    }

    /**
     * Delete the KYC if data does not match from zanid, 
     * The person will be required to apply for reference number again
     */
    public function reject($value)
    {
        $comments = $value['value'];
        DB::beginTransaction();
        try {
            $this->kyc->comments = $comments;
            $this->kyc->save();
            $kyc = $this->kyc;
            $this->kyc->delete();
            DB::commit();
            event(new SendMail('kyc-reject', $kyc));
            event(new SendSms('kyc-reject', $kyc));
            $this->alert('success', 'KYC has been rejected!');
            return redirect()->route('taxpayers.registrations.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help!');
        }
    }

    public function convertStringToCamelCase($string) {
        return ucfirst(strtolower($string));
    }

    public function render()
    {
        return view('livewire.taxpayers.details.zanid');
    }
}
