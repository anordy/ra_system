<?php

namespace App\Http\Livewire\DriversLicense\Initiation;

use App\Enum\AlertType;
use App\Enum\CustomMessage;
use App\Enum\DlFeeType;
use App\Enum\GeneralConstant;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlLicenseClass;
use App\Models\DlLicenseRestriction;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use DB;
use Livewire\Component;
use Log;

class AddClass extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $licenseNumber, $age, $licenseInfo;
    public $classes = [], $licenseClasses = [];

    protected $rules = [
        'licenseClasses.*.classId' => 'required|integer|exists:dl_license_classes,id|distinct',
        'licenseClasses.*.certificateNumber' => 'required|alpha_num|max:255',
        'licenseClasses.*.certificateDate' => 'required|date'
    ];

    protected $messages = [
        'licenseClasses.*.classId.distinct' => 'Duplicate class found in the selected license classes.',
        'licenseClasses.*.certificateDate.date' => 'Invalid date format.',
        'licenseClasses.*.certificateDate.required' => 'Certificate date is required.',
        'licenseClasses.*.certificateNumber.required' => 'Certificate number is required.',
    ];


    public function mount()
    {
        $this->addClass();
    }

    public function submit()
    {
        $this->validate();

        try {
            // Get previous application
            $newApplication = $this->licenseInfo->application->replicate();

            DB::beginTransaction();

            $newApplication->status = DlApplicationStatus::STATUS_INITIATED;
            $newApplication->payment_status = DlApplicationStatus::STATUS_INITIATED;
            $newApplication->type = DlFeeType::ADD_CLASS;
            $newApplication->previous_application_id = $this->licenseInfo->application->id;
            $newApplication->marking = null;

            if (!$newApplication->save()) throw new \Exception('Failed to save application');

            $license = DlDriversLicense::create([
                'dl_license_application_id' => $newApplication->id,
                'issued_date' => $this->licenseInfo->issued_date,
                'license_number' => $this->licenseInfo->license_number,
                'expiry_date' => $this->licenseInfo->expiry_date,
                'taxpayer_id' => $this->licenseInfo->taxpayer_id,
            ]);

            foreach ($this->licenseClasses ?? [] as $class) {
                $class = DlDriversLicenseClass::create([
                    'dl_license_application_id' => $newApplication->id,
                    'dl_drivers_license_id' => $license->id,
                    'dl_license_class_id' => $class['classId'],
                    'certificate_number' => $class['certificateNumber'],
                    'certificate_date' => $class['certificateDate'],
                    'is_initiation_accepted' => $class['disabled']
                ]);

                if (!$class) throw new \Exception('Error creating license class');
            }


            foreach ($this->licenseInfo->application->licenseRestrictions ?? [] as $restriction) {
                $restriction = DlLicenseRestriction::create([
                    'dl_license_application_id' => $newApplication->id,
                    'dl_restriction_id' => $restriction->dl_restriction_id,
                    'dl_license_id' => $license->id
                ]);

                if (!$restriction) throw new \Exception('Error creating license restriction');
            }

            $this->registerWorkflow(get_class($newApplication), $newApplication->id);
            $this->doTransition('application_initiated_for_class', ['status' => 'agree', 'comment' => 'approved']);

            DB::commit();

            $this->flash('success', 'Application added successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('LICENSE-INITIATION: ', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
    }

    public function searchLicense()
    {
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

    public function fetchClasses()
    {
        $taxpayer = $this->licenseInfo->taxpayer;

        if (!$taxpayer) {
            $this->addError('licenseNumber', 'Taxpayer is invalid.');
        }

        try {
            $dateOfBirth = $taxpayer->date_of_birth;
            $this->age = Carbon::create($dateOfBirth)->diff(Carbon::now())->format('%y');
            $this->age = 21;// TODO: remove after testing
            $this->classes = DlLicenseClass::select('id', 'from_age', 'to_age', 'name', 'description')
                ->where('from_age', '<=', $this->age)
                ->where('to_age', '>=', $this->age)
                ->get();
        } catch (\Exception $e) {
            Log::error('DRIVER-LICENSE-FETCH-CLASS', [$e]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }
    }


    public function addClass()
    {
        try {
            $this->licenseClasses[] = [
                'disabled' => false,
                'classId' => null,
                'certificateNumber' => null,
                'certificateDate' => null
            ];
        } catch (\Exception $e) {
            Log::error('DRIVER-LICENSE-ADD-CLASS', [$e]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }
    }

    public function removeClass($i)
    {
       try {
            unset($this->licenseClasses[$i]);
        } catch (\Exception $e) {
            Log::error('DRIVER-LICENSE-REMOVE-CLASS', [$e]);
            $this->customAlert(AlertType::ERROR, CustomMessage::ERROR);
        }
    }

    public function render()
    {
        return view('livewire.drivers-license.initiation.add-class');
    }
}
