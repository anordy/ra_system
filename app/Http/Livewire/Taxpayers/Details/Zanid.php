<?php

namespace App\Http\Livewire\Taxpayers\Details;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\v1\ZanIDController;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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
         $this->zanid_data = $zanid_controller->getZanIDData($this->kyc->id_number);
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
            'timer' => null,
        ]);
    }

    public function approve()
    {
        try {
            $this->kyc->update([
                'first_name' => $this->convertStringToCamelCase($this->zanid_data['PRSN_FIRST_NAME']),
                'middle_name' => $this->convertStringToCamelCase($this->zanid_data['PRSN_MIDLE_NAME']), // TODO: Zan id sometimes returns json on middle name. Look into this
                'last_name' => $this->convertStringToCamelCase($this->zanid_data['PRSN_LAST_NAME']),
                'authorities_verified_at' => Carbon::now()->toDateTimeString(),
            ]);
            $this->alert('success', 'Taxpayer details have been approved!');
            return redirect()->route('taxpayers.enroll-fingerprint', [encrypt($this->kyc->id)]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong!');
        }

    }

    /**
     * Delete the KYC if data does not match from zanid, The person will be required to apply for reference number * again
     */
    public function reject()
    {
        try {
            $this->kyc->delete();
            $this->alert('success', 'KYC has been rejected!');
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
        return view('livewire.taxpayers.details.zanid');
    }
}
