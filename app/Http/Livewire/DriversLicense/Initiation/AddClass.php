<?php

namespace App\Http\Livewire\DriversLicense\Initiation;

use App\Enum\GeneralConstant;
use App\Models\DlDriversLicense;
use App\Models\DlLicenseClass;
use App\Traits\CustomAlert;
use Carbon\Carbon;
use Livewire\Component;

class AddClass extends Component
{
    use CustomAlert;

    public $licenseNumber, $age, $licenseInfo;
    public $classes = [], $licenseClasses = [];

    protected $rules = [
        'licenseClasses.*.classId' => 'required|integer|exists:dl_license_classes,id',
        'licenseClasses.*.certificateNumber' => 'required|alpha_num|max:255',
        'licenseClasses.*.certificateDate' => 'required|date'
    ];

    public function mount() {
        $this->addClass();
    }

    public function searchLicense() {
        $this->resetErrorBag('licenseNumber');
        $this->reset('licenseClasses');

        if (!$this->licenseNumber) {
            $this->addError('licenseNumber', 'License number is required');
        }

        $this->licenseInfo = DlDriversLicense::select('id', 'dl_license_application_id', 'taxpayer_id', 'license_number', 'status', 'issued_date', 'expiry_date', 'is_blocked')
            ->where('license_number', $this->licenseNumber)
            ->where('status', DlDriversLicense::ACTIVE)
            ->where('is_blocked', GeneralConstant::ZERO_INT)
            ->first();

        if (!$this->licenseInfo) {
            $this->addError('licenseNumber', 'License number is invalid, expired or blocked.');
        }

        $this->fetchClasses();

        foreach ($this->licenseInfo->drivers_license_classes ?? [] as $class) {
            $this->licenseClasses[] = [
                'disabled' => $class->is_initiation_accepted,
                'classId' => $class->dl_license_class_id,
                'certificateNumber' => $class->certificate_number,
                'certificateDate' => $class->certificate_date ? Carbon::parse($class->certificate_date)->format('Y-m-d') : null,
            ];
        }
    }

    public function fetchClasses() {
        $taxpayer = $this->licenseInfo->taxpayer;

        if (!$taxpayer) {
            $this->addError('licenseNumber', 'Taxpayer is invalid.');
        }

        $dateOfBirth = $taxpayer->date_of_birth;
        $this->age = Carbon::create($dateOfBirth)->diff(Carbon::now())->format('%y');

        $this->classes = DlLicenseClass::select('id', 'from_age', 'to_age', 'name', 'description')
            ->where('from_age', '<=', $this->age)
            ->where('to_age', '>=', $this->age)
            ->get();
    }


    public function addClass() {
        $this->licenseClasses[] = [
            'disabled' => false,
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
        return view('livewire.drivers-license.initiation.add-class');
    }
}
