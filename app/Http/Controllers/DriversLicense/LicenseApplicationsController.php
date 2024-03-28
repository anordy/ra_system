<?php

namespace App\Http\Controllers\DriversLicense;

use App\Http\Controllers\Controller;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseOwner;
use App\Models\DlFee;
use App\Models\DlLicenseApplication;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function show($id)
    {
        if (!Gate::allows('driver-licences-view')) {
            abort(403);
        }
        $id = decrypt($id);
        $application = DlLicenseApplication::with(['application_license_classes', 'drivers_license'])->findOrFail($id,
            [
                'id', 'status', 'payment_status', 'type', 'created_at', 'loss_report_path',
                'certificate_path', 'certificate_number', 'confirmation_number', 'license_duration',
                'dl_drivers_license_owner_id', 'photo_path'
            ]);
        $applicant = DlDriversLicenseOwner::query()->findOrFail($application->dl_drivers_license_owner_id,
            [
                'id', 'first_name', 'middle_name', 'last_name', 'tin', 'email', 'mobile', 'alt_mobile', 'dob'
            ]);
        $title = ['fresh' => 'New License Application', 'renew' => 'License Renewal Application', 'duplicate' => 'License Duplicate Application'][strtolower($application->type)];
        return view('driver-license.license-applications-show', compact('application', 'title', 'applicant'));
    }


    public function printed($id)
    {
        $id = decrypt($id);

        $application = DlLicenseApplication::query()->findOrFail($id);
        try {
            $application->status = DlApplicationStatus::STATUS_COMPLETED;
            $application->save();
        } catch (Exception $e) {
            Log::error('DRIVERS-LICENSE-LICENSE-APPLICATION-CONTROLLER-PRINTED', [$e]);
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
                Log::error('DRIVERS-LICENSE-LICENSE-APPLICATION-CONTROLLER-GET-FILE', [$e]);
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
        $license = DlDriversLicense::with(['drivers_license_owner', 'drivers_license_classes'])
            ->findOrFail(decrypt($id), ['id', 'license_number', 'issued_date', 'expiry_date']);
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
