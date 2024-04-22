<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatusChange;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MotorVehicleRegistrationStatusChangeController extends Controller
{

    public function index()
    {
        if (!Gate::allows('motor-vehicle-status-change-request')) {
            abort(403);
        }
        return view('mvr.status.index');
    }

    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-status-change-request')) {
            abort(403);
        }
        try {
            $id = decrypt($id);
            $change_req = MvrRegistrationStatusChange::query()->findOrFail($id);
            $motorVehicle = MvrRegistration::findOrFail($change_req->current_registration_id);
            return view('mvr.status.show', compact('motorVehicle', 'change_req'));
        } catch (\Exception $exception) {
            Log::error('MVR-REG-STATUS-CHANGE-SHOW', [$exception]);
            return redirect()->back();
        }
    }
}
