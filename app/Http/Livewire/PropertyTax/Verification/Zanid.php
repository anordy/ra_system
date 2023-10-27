<?php

namespace App\Http\Livewire\PropertyTax\Verification;

use App\Http\Controllers\v1\ZanIDController;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Zanid extends Component
{
    use CustomAlert;

    public $responsiblePerson;
    public $is_verified_triggered = false;
    public $zanid_data;

    public function mount($responsiblePerson)
    {
        $this->responsiblePerson = $responsiblePerson;
    }

    public function validateZanID()
    {
         $this->is_verified_triggered = true;
         $zanid_controller = new ZanIDController;
         $this->zanid_data = $zanid_controller->getZanIDData($this->responsiblePerson->id_number);
         try {
             $this->responsiblePerson->update([
                 'first_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_FIRST_NAME']),
                 'middle_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_MIDLE_NAME']),
                 'last_name' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_LAST_NAME']),
                 'email' => $this->zanid_data['data']['PRSN_EMAILS'],
                 'address' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_RES_ADDRESS']),
                 'date_of_birth' => Carbon::create($this->zanid_data['data']['PRSN_BIRTH_DATE'])->format('Y-m-d'),
                 'gender' => $this->convertStringToCamelCase($this->zanid_data['data']['PRSN_SEX']),
                 'id_verified_at' => Carbon::now()->toDateTimeString(),
             ]);

             $this->customAlert('success', 'ZanID Data has been saved');
         } catch (Exception $exception) {
             Log::error($exception);
             $this->customAlert('error', 'Failed to save ZanID data, Please try again later');
         }

    }

    public function compareProperties($kyc_property, $zanid_property)
    {
        $kyc_property = strtolower($kyc_property ?? '');
        $zanid_property = strtolower($zanid_property ?? '');

        return $kyc_property === $zanid_property ? true : false;
    }

    public function convertStringToCamelCase($string) {
        return ucfirst(strtolower($string));
    }

    public function render()
    {
        return view('livewire.property-tax.verification.zanid');
    }
}
