<?php

namespace App\Http\Livewire\DriversLicense\Initiation;

use App\Models\DlBloodGroup;
use App\Models\DlLicenseClass;
use App\Models\DlRestriction;
use Carbon\Carbon;
use Livewire\Component;

class LicenseInitiation extends Component
{
    public $bloodGroups = [], $restrictions = [], $classes = [], $licenseClasses = [];

    public $bloodGroupId, $restrictionId, $dob, $firstName, $lastName, $middleName, $confirmationNumber;

    public function mount()
    {
        $this->bloodGroups = DlBloodGroup::select('id', 'name')->get();
        $this->restrictions = DlRestriction::select('id', 'symbol', 'description')->get();
        $this->addClass();
    }

    public function updatedDob($value) {
        $age = Carbon::create($value)->diff(Carbon::now())->format('%y');

        $this->classes = DlLicenseClass::select('id', 'from_age', 'to_age', 'name', 'description')
            ->where('from_age', '<=', $age)
            ->where('to_age', '>=', $age)
            ->get();
    }

    public function addClass() {
        $this->licenseClasses[] = [
            'classId' => null,
            'certificateNumber' => null,
            'certificateDate' => null
        ];
    }


    public function removeClass($i) {
        unset($this->licenseClasses[$i]);
    }

    public function render()
    {
        return view('livewire.drivers-license.initiation.license-initiation');
    }
}
