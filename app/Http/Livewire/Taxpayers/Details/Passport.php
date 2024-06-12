<?php

namespace App\Http\Livewire\Taxpayers\Details;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Traits\CustomAlert;
use App\Http\Controllers\v1\ImmigrationController;

class Passport extends Component
{
    use CustomAlert;

    public $kyc;
    public $is_verified_triggered = false;
    public $passport;
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

    public function validatePassport()
    {
        $immigration_controller = new ImmigrationController;
        try {
            $this->is_verified_triggered = true;
            $this->passport = $immigration_controller->getPassportData($this->kyc->passport_no, $this->kyc->permit_number);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function compareProperties($kyc_property, $immigration_property)
    {
        $kyc_property = strtolower($kyc_property);
        $immigration_property = strtolower($immigration_property);

        return $kyc_property === $immigration_property ? true : false;
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
            'timer' => null,
            'input' => 'textarea',
        ]);
    }

    public function approve()
    {
        try {
            $this->kyc->update([
                'first_name' =>  $this->convertStringToCamelCase($this->passport['data']['FirstName']),
                'middle_name' => $this->convertStringToCamelCase($this->passport['data']['MiddleName']),
                'last_name' => $this->convertStringToCamelCase($this->passport['data']['SurName']),
                'passport_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            $this->customAlert('success', 'Taxpayers details has been approved successful!');
            return redirect()->route('taxpayers.enroll-fingerprint', [encrypt($this->kyc->id)]);
        } catch (Exception $e) {
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help!');
        }
    }

    /**
     * Delete the KYC if data does not match from immigration, The person will be required to apply for reference number * again
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
            $this->customAlert('success', 'KYC has been rejected!');
            return redirect()->route('taxpayers.registrations.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help!');
        }
    }

    public function convertStringToCamelCase($string)
    {
        return ucfirst(strtolower($string));
    }


    public function render()
    {
        return view('livewire.taxpayers.details.passport');
    }
}
