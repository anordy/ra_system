<?php

namespace App\Http\Livewire\Taxpayers\Details;

use App\Http\Controllers\v1\ImmigrationController;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Passport extends Component
{
    use LivewireAlert;

    public $kyc;
    public $passport;
    public $matchText = 'Match';
    public $notValidText = 'Mismatch';

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

    public function approve()
    {

    }

    public function reject()
    {
        
    }


    public function render()
    {
        return view('livewire.taxpayers.details.passport');
    }
}
