<?php

namespace App\Http\Livewire\DriversLicense\Initiation;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Models\DlApplicationStatus;
use App\Models\DlBloodGroup;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlLicenseApplication;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseRestriction;
use App\Models\DlRestriction;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Livewire\Component;

class FreshApplication extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $bloodGroups = [], $restrictions = [], $classes = [], $licenseClasses = [];

    public $bloodGroupId, $restrictionIds, $dob, $firstName, $lastName, $middleName, $confirmationNumber, $age;

    protected $rules = [
        'bloodGroupId' => 'required|exists:dl_blood_groups,id',
        'restrictionIds' => 'nullable|array',
        'dob' => 'required|date|before:today',
        'firstName' => 'required|string|max:255',
        'lastName' => 'required|string|max:255',
        'middleName' => 'nullable|string|max:255',
        'confirmationNumber' => 'required|string|max:255',
        'licenseClasses.*.classId' => 'required|integer|exists:dl_license_classes,id',
        'licenseClasses.*.certificateNumber' => 'required|alpha_num|max:255',
        'licenseClasses.*.certificateDate' => 'required|date'
    ];

    public function mount()
    {
        $this->bloodGroups = DlBloodGroup::select('id', 'name')->get();
        $this->restrictions = DlRestriction::select('id', 'symbol', 'description')->get();
        $this->addClass();
    }

    public function updatedDob($value) {
        $this->age = Carbon::create($value)->diff(Carbon::now())->format('%y');

        if ($this->age < 16) {
            $this->customAlert(GeneralConstant::WARNING, 'The age must be greater than or equal 16 years old.');
            return;
        }

        $this->classes = DlLicenseClass::select('id', 'from_age', 'to_age', 'name', 'description')
            ->where('from_age', '<=', $this->age)
            ->where('to_age', '>=', $this->age)
            ->get();

        $this->licenseClasses = [];
        $this->addClass();
    }

    public function addClass() {
        $this->licenseClasses[] = [
            'classId' => null,
            'certificateNumber' => null,
            'certificateDate' => null
        ];
    }
    
    public function submit() {
        $this->validate();

        try {
            \DB::beginTransaction();

            $application = DlLicenseApplication::create([
                'blood_group_id' => $this->bloodGroupId,
                'dob' => $this->dob,
                'confirmation_number' => $this->confirmationNumber,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'middle_name' => $this->middleName,
                'status' => DlApplicationStatus::STATUS_INITIATED,
                'certificate_of_competence' => 'TEST',
                'license_type' => 'FRESH'
            ]);


            if (!$application) throw new \Exception('Error creating application');

            $license = DlDriversLicense::create([
                'dl_license_application_id' => $application->id,
            ]);

            if (!$license) throw new \Exception('Error creating license');

            foreach ($this->licenseClasses ?? [] as $class) {
                $class = DlDriversLicenseClass::create([
                    'dl_license_application_id' => $application->id,
                    'dl_drivers_license_id' => $license->id,
                    'dl_license_class_id' => $class['classId'],
                    'certificate_number' => $class['certificateNumber'],
                    'certificate_date' => $class['certificateDate'],
                ]);

                if (!$class) throw new \Exception('Error creating license class');
            }

            foreach ($this->restrictionIds ?? [] as $restrictionId) {
                $restriction = DlLicenseRestriction::create([
                    'dl_license_application_id' => $application->id,
                    'dl_restriction_id' => $restrictionId,
                    'dl_license_id' => $license->id
                ]);

                if (!$restriction) throw new \Exception('Error creating license restriction');
            }

            $this->registerWorkflow(get_class($application), $application->id);
            $this->doTransition('application_initiated', ['status' => 'agree', 'comment' => 'approved']);

            \DB::commit();

            $this->flash('success', 'Application added successfully', [], redirect()->back()->getTargetUrl());

        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::error('LICENSE-INITIATION: ', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
       
    }


    public function removeClass($i) {
        unset($this->licenseClasses[$i]);
    }

    public function render()
    {
        return view('livewire.drivers-license.initiation.fresh-application');
    }
}
