<?php

namespace App\Http\Livewire\Taxpayers\Details;

use App\Http\Controllers\v1\ZanIDController;
use Livewire\Component;

class Zanid extends Component
{

    public $kyc;
    public $zanid_data;
    public $matchText = 'Match';
    public $notValidText = 'Mismatch';

    public function mount($kyc)
    {
        $this->kyc = $kyc;
    }

    public function validateZanID()
    {
         $zanid_controller = new ZanIDController;
         $this->zanid_data = $zanid_controller->getZanIDData($this->kyc->id_number);
    }

    public function compareProperties($kyc_property, $zanid_property)
    {
        $kyc_property = strtolower($kyc_property ?? '');
        $zanid_property = strtolower($zanid_property ?? '');

        return $kyc_property === $zanid_property ? true : false;
    }

    public function approve()
    {

    }

    public function reject()
    {
        
    }

    public function render()
    {
        return view('livewire.taxpayers.details.zanid');
    }
}
