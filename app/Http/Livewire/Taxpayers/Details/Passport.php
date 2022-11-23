<?php

namespace App\Http\Livewire\Taxpayers\Details;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Http\Controllers\v1\ImmigrationController;

class Passport extends Component
{
    use LivewireAlert;

    public $kyc;
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
            $this->passport = $immigration_controller->getPassportData($this->kyc->id_number, $this->kyc->permit_number);
        } catch (Exception $e) {
            Log::error($e);
            return $this->alert('error', 'Something went wrong');
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
            'timer' => null,
        ]);
    }

    public function approve()
    {
        try {
            $this->kyc->update([
                'first_name' =>  $this->convertStringToCamelCase($this->passport['FirstName']),
                'middle_name' => $this->convertStringToCamelCase($this->passport['MiddleName']),
                'last_name' => $this->convertStringToCamelCase($this->passport['SurName']),
                'authorities_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            $this->alert('success', 'Taxpayers details has been approved successful!');
            return redirect()->route('taxpayers.enroll-fingerprint', [encrypt($this->kyc->id)]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong!');
        }
    }

    /**
     * Delete the KYC if data does not match from immigration, The person will be required to apply for reference number * again
     */
    public function reject()
    {
        try {
            $this->kyc->delete();
            $this->alert('success', 'KYC has been rejected successful!');
            return redirect()->route('taxpayers.registrations.index');
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong!');
        }
    }

    public function convertStringToCamelCase($string) {
        return ucfirst(strtolower($string));
    }


    public function render()
    {
        return view('livewire.taxpayers.details.passport');
    }
}
