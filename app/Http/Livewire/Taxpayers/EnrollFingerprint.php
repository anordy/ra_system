<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Biometric;
use App\Traits\Taxpayer\KYCTrait;
use Livewire\Component;

class EnrollFingerprint extends Component
{
    use KYCTrait;

    public $kyc;
    public $error;

    public $selectedStep = 'details';
    public $userVerified = false;
    public $verifyingUser = false;

    public function changeStep($step)
    {
        $this->selectedStep = $step;
    }

    public function mount()
    {
        if ($this->kyc->zanid_verified_at || $this->kyc->passport_verified_at) {
            $this->userVerified = true;
        } else {
            $this->userVerified = false;
            $this->verifyingUser = true;
        }
    }

    public function enrolled($hand, $finger)
    {
        $check = Biometric::where('hand', $hand)
            ->where('finger', $finger)
            ->where('reference_no', $this->kyc->id)
            ->where('template', '!=', null)
            ->get();
        if ($check->count() >= 1) {
            return true;
        } else {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.taxpayers.enroll-vendor-fingerprint');
    }
}
