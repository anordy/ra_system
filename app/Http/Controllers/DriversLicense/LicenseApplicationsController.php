<?php

namespace App\Http\Controllers\DriversLicense;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlDriversLicenseOwner;
use App\Models\DlFee;
use App\Models\DlLicenseApplication;
use App\Models\MvrMotorVehicle;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Traits\CustomAlert;

class LicenseApplicationsController extends Controller
{
    use WorkflowProcesssingTrait, CustomAlert;

    public function index()
    {
        if (!Gate::allows('driver-licences-view')) {
            abort(403);
        }
        return view('driver-license.license-applications-index');
    }

    public function create()
    {
        if (!Gate::allows('driver-licences-create')) {
            abort(403);
        }
        return view('driver-license.license-applications-create');
    }


    public function submit($id)
    {
        if (!Gate::allows('driver-licences-create')) {
            abort(403);
        }
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->find($id);
        if (strtolower($application->type) == 'fresh') {
            $comment = $application->application_status->name == DlApplicationStatus::STATUS_DETAILS_CORRECTION ? 'Initiated' : 'Resubmitted';
            $transition = $application->application_status->name == DlApplicationStatus::STATUS_DETAILS_CORRECTION ? 'application_corrected' : 'application_submitted';
            try {
                DB::beginTransaction();
                $application->update(['dl_application_status_id' => DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_APPROVAL])->id]);
                $this->registerWorkflow(get_class($application), $application->id);
                $this->doTransition($transition, ['status' => '', 'comment' => $comment]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                report($e);
                session()->flash('error', 'Could not update application');
            }
        } else {

            try {
                DB::beginTransaction();
                $application->update(['dl_application_status_id' => DlApplicationStatus::query()->firstOrCreate(['name' => DlApplicationStatus::STATUS_PENDING_PAYMENT])->id]);
                $fee = DlFee::query()->where(['type' => $application->type])->first();
                if (empty ($fee)) {
                    session()->flash('error', "Fee for Drivers license application ({$application->type}) is not configured");
                    return redirect()->back();
                }
                $exchange_rate = 1;
                $amount = $fee->amount;
                $zmBill = ZmCore::createBill(
                    $application->id,
                    get_class($application),
                    6,
                    $application->taxpayer->id,
                    get_class($application->taxpayer),
                    $application->taxpayer->fullname(),
                    $application->taxpayer->email,
                    ZmCore::formatPhone($application->taxpayer->mobile),
                    Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                    $fee->name,
                    ZmCore::PAYMENT_OPTION_EXACT,
                    'TZS',
                    1,
                    auth()->user()->id,
                    get_class(auth()->user()),
                    [
                        [
                            'billable_id' => $application->id,
                            'billable_type' => get_class($application),
                            'fee_id' => $fee->id,
                            'fee_type' => get_class($fee),
                            'tax_type_id' => 6,
                            'amount' => $amount,
                            'currency' => 'TZS',
                            'exchange_rate' => $exchange_rate,
                            'equivalent_amount' => $exchange_rate * $amount,
                            'gfs_code' => $fee->gfs_code
                        ]
                    ]
                );

                if (config('app.env') != 'local') {
                    $response = ZmCore::sendBill($zmBill->id);
                    if ($response->status === ZmResponse::SUCCESS) {
                        session()->flash('success', __('A control number request was sent successful.'));
                    } else {
                        session()->flash('error', __('Control number generation failed, try again later'));
                    }
                } else {
                    $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                    $zmBill->zan_status = 'pending';
                    $zmBill->control_number = random_int(2000070001000, 2000070009999);
                    $zmBill->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                report($e);
                session()->flash('error', __('Could not update application'));
            }
        }

        return redirect()->route('drivers-license.applications.show', encrypt($id));
    }

    public function show($id)
    {
        if (!Gate::allows('driver-licences-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $application = DlLicenseApplication::query()->findOrFail($id);
        $applicant = DlDriversLicenseOwner::query()->findOrFail($application->dl_drivers_license_owner_id);
        $title = ['fresh' => 'New License Application', 'renew' => 'License Renewal Application', 'duplicate' => 'License Duplicate Application'][strtolower($application->type)];
        return view('driver-license.license-applications-show', compact('application', 'title', 'applicant'));
    }


    public function printed($id)
    {
        $id = decrypt($id);

        $application = DlLicenseApplication::query()->findOrFail($id);
        try {
            $application->status = DlApplicationStatus::STATUS_COMPLETED; // 
            $application->save();
        } catch (Exception $e) {
            report($e);
            session()->flash('error', 'Could not update application');
        }
        return redirect()->route('drivers-license.applications.show', encrypt($id));
    }

    public function license($id)
    {
        $id = decrypt($id);
        $license = DlDriversLicense::query()->findOrFail($id);

        header('Content-Type: application/pdf');

        $pdf = PDF::loadView('driver-license.pdfs.drivers-license', compact('license'));
        $pdf->setPaper('legal', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }

    public function getFile($location)
    {
        if ($location) {
            try {
                return Storage::disk('local')->response(decrypt($location));
            } catch (Exception $e) {
                report($e);
                abort(404);
            }
        }
        return abort(404);
    }



    public function showLicense($id)
    {
        if (!Gate::allows('driver-licences-view')) {
            abort(403);
        }
        $license = DlDriversLicense::query()->findOrFail(decrypt($id));
        return view('driver-license.licenses-show', compact('license'));
    }

    public function indexLicense()
    {
        if (!Gate::allows('driver-licences-view')) {
            abort(403);
        }
        return view('driver-license.licenses-index');
    }

}
