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

    public $selectedStep = 'biometric';
    public $userVerified = false;
    public $verifyingUser = false;

    public function changeStep($step)
    {
        $this->selectedStep = $step;
    }

    public function mount()
    {
        if (!$this->kyc->authorities_verified_at) {
            $this->userVerified = false;
            $this->verifyingUser = true;
        } else {
            $this->userVerified = true;
        }
    }

    public function verifyUser()
    {
        if ($this->userVerified === false) {
            $this->verifyingUser = true;

            $response = $this->updateUser($this->kyc);

            $this->verifyingUser = false;

            if (!$response) {
                $this->error = "Could not verify user from the authorities. Try again later.";
            } else {
                $this->error = '';
                $this->userVerified = true;
            }
        }
    }

    public function enrolled($hand, $finger)
    {
        $check = Biometric::where('hand', $hand)
            ->where('finger', $finger)
            ->where('reference_no', $this->kyc->reference_no)
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
