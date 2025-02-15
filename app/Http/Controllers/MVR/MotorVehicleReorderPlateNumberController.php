<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatusChange;
use App\Models\MvrReorderPlateNumber;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MotorVehicleReorderPlateNumberController extends Controller
{

    public function index()
    {
        // if (!Gate::allows('motor-vehicle-reorder-plate-number')) {
        //     abort(403);
        // }
        return view('mvr.reorder.index');
    }

    public function show($id)
    {
        // if (!Gate::allows('motor-vehicle-reorder-plate-number')) {
        //     abort(403);
        // }
        try {
            $id = decrypt($id);
            $change_req = MvrReorderPlateNumber::query()->findOrFail($id);
            $motorVehicle = MvrRegistration::findOrFail($change_req->current_registration_id);
            return view('mvr.reorder.show', compact('motorVehicle', 'change_req'));
        } catch (\Exception $exception) {
            Log::error('MVR-REG-REORDER-PLATE-NUMBER-SHOW', [$exception]);
            return redirect()->back();
        }
    }
}
