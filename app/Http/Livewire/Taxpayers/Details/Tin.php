<?php

namespace App\Http\Livewire\Taxpayers\Details;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\v1\ZanIDController;
use App\Services\Api\TraInternalService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class Tin extends Component
{
    use CustomAlert;

    public $kyc;
    public $is_verified_triggered = false;
    public $zanid_data; // TODO: Remove
    public $tin;

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

    public function verifyTIN()
    {
        try {
            $this->is_verified_triggered = true;
            $traService = new TraInternalService();
            $response = $traService->getTinNumber($this->kyc->tin_no);
            if ($response && $response['data']) {
                $this->tin = $response['data'];
            } else if ($response && $response['data'] == null) {
                $this->customAlert('warning', $response['message']);
                return;
            } else {
                $this->customAlert('error', 'Something went wrong');
                return;
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
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

        $this->customAlert('warning', 'Are you sure you want to approve taxpayers details ?', [
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

        $this->customAlert('warning', 'Are you sure you want to reject this KYC ?', [
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
                'first_name' => $this->convertStringToCamelCase($this->tin['first_name']),
                'middle_name' => $this->convertStringToCamelCase($this->tin['middle_name']),
                'last_name' => $this->convertStringToCamelCase($this->tin['last_name']),
                'tin_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            $this->customAlert('success', 'Taxpayer details have been approved!');
            return redirect()->route('taxpayers.enroll-fingerprint', [encrypt($this->kyc->id)]);
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help!');
        }

    }

    /**
     * Delete the KYC if data does not match from zanid,
     * The person will be required to apply for reference number again
     */
    public function reject($value)
    {
        if (isset($value['value'])) {
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
                $this->customAlert('success', 'KYC has been rejected!');
                return redirect()->route('taxpayers.registrations.index');
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help!');
            }
        } else {
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help!');
        }

    }

    public function convertStringToCamelCase($string)
    {
        return ucfirst(strtolower($string));
    }

    public function render()
    {
        return view('livewire.taxpayers.details.tin');
    }
}
