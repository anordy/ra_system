<?php

namespace App\Http\Controllers\DriversLicense;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlFee;
use App\Models\DlLicenseApplication;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class LicenseApplicationsController extends Controller
{
    use WorkflowProcesssingTrait;

    public function index(){

        return view('driver-license.license-applications-index');
    }

    public function create(){

        return view('driver-license.license-applications-create');
    }

    public function show($id){
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->find($id);
        $title = ['fresh'=>'New License Application','renew'=>'License Renewal Application','duplicate'=>'License Duplicate Application'][strtolower($application->type)];
        return view('driver-license.license-applications-show',compact('application','title'));
    }

    public function submit($id){
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->find($id);
        if (strtolower($application->type)=='fresh'){
            $comment = $application->application_status->name == DlApplicationStatus::STATUS_DETAILS_CORRECTION?'Initiated':'Resubmitted';
            $transition = $application->application_status->name == DlApplicationStatus::STATUS_DETAILS_CORRECTION?'application_corrected':'application_submitted';
            try{
                DB::beginTransaction();
                $application->update(['dl_application_status_id'=>DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_PENDING_APPROVAL])->id]);
                $this->registerWorkflow(get_class($application), $application->id);
                $this->doTransition($transition, ['status'=>'','comment'=>$comment]);
                if ($transition == 'application_submitted'){
                    event(new SendSms('license-application-submitted', $application->id));
                    event(new SendMail('license-application-submitted', $application->id));
                }
                DB::commit();
            }catch (\Exception $e){
                DB::rollBack();
                report($e);
                session()->flash('error','Could not update application');
            }
        }else{
            try {
                DB::beginTransaction();
                $application->update(['dl_application_status_id'=>DlApplicationStatus::query()->firstOrCreate(['name'=>DlApplicationStatus::STATUS_PENDING_PAYMENT])->id]);
                $bill = $application->generateBill();
                $response = ZmCore::sendBill($bill->id);
                DB::commit();
                if ($response->status === ZmResponse::SUCCESS) {
                    session()->flash('success', 'A control number request was sent successful.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                }
            }catch (\Exception $e){
                DB::rollBack();
                report($e);
                session()->flash('error','Could not update application');
            }
        }

        return redirect()->route('drivers-license.applications.show',encrypt($id));
    }

    public function simulatePayment($id){
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->find($id);
        try {
            DB::beginTransaction();
            if (strtolower($application->type) == 'fresh' || strtolower($application->type) == 'renew'){
                $application->dl_application_status_id =  DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_TAKING_PICTURE])->id;
            }else{
                $application->dl_application_status_id =  DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_LICENSE_PRINTING])->id;
                $latest_license = DlDriversLicense::query()
                    ->where(['dl_drivers_license_owner_id'=>$application->dl_drivers_license_owner_id])
                    ->latest()
                    ->first();
                /** @var DlDriversLicense $license */
                $latest_license->update(['status'=>DlDriversLicense::STATUS_DAMAGED_OR_LOST]);
                $latest_license->save();
                $license = DlDriversLicense::query()->create([
                    'dl_drivers_license_owner_id'=>$application->dl_drivers_license_owner_id,
                    'license_number'=>$latest_license->license_number,
                    'dl_license_duration_id'=>$application->dl_license_duration_id,
                    'issued_date'=>date('Y-m-d'),
                    'expiry_date'=>date('Y-m-d',strtotime("+{$application->license_duration->number_of_years} years")),
                    'license_restrictions'=>$latest_license->license_restrictions,
                    'dl_license_application_id'=>$application->id,
                    'status'=>'ACTIVE'
                ]);

                $latest_license = DlDriversLicense::query()
                    ->where(['dl_drivers_license_owner_id'=>$application->dl_drivers_license_owner_id])
                    ->latest()
                    ->first();
                foreach ($latest_license->drivers_license_classes as $class){
                    DlDriversLicenseClass::query()->create(
                        [
                            'dl_drivers_license_id'=>$license->id,
                            'dl_license_class_id'=>$class->dl_license_class_id
                        ]
                    );
                }
            }
            $application->save();
            $bill = $application->get_latest_bill();
            $bill->update(['status'=>'Paid']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e);
        }
        return redirect()->route('drivers-license.applications.show',encrypt($id));
    }

    public function printed($id){
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->find($id);
        try {
            $application->dl_application_status_id =  DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_COMPLETED])->id;
            $application->save();
        } catch (Exception $e) {
            report($e);
            session()->flash('error','Could not update application');
        }
        return redirect()->route('drivers-license.applications.show',encrypt($id));
    }

}
