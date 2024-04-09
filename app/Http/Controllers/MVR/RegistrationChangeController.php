<?php

namespace App\Http\Controllers\MVR;

use App\Enum\Currencies;
use App\Enum\GeneralConstant;
use App\Http\Controllers\Controller;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRequestStatus;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\ExchangeRateTrait;
use App\Traits\MotorVehicleSearchTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class RegistrationChangeController extends Controller
{

    use MotorVehicleSearchTrait, ExchangeRateTrait;

    public function index()
    {
        if (!Gate::allows('motor-vehicle-status-change-request')) {
            abort(403);
        }
        return view('mvr.reg-change-index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-status-change-request')) {
            abort(403);
        }
        $id = decrypt($id);
        $change_req = MvrRegistrationChangeRequest::query()->with('current_registration')->findOrFail($id);
        $motor_vehicle = $change_req->current_registration->motor_vehicle;
        return view('mvr.reg-change-req-show', compact('motor_vehicle', 'change_req'));
    }

}
