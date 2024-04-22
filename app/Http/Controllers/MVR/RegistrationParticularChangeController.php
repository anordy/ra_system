<?php

namespace App\Http\Controllers\MVR;

use App\Enum\Currencies;
use App\Enum\GeneralConstant;
use App\Http\Controllers\Controller;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationParticularChange;
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

class RegistrationParticularChangeController extends Controller
{

    use MotorVehicleSearchTrait, ExchangeRateTrait;

    public function index()
    {
        return view('mvr.particular.index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $id = decrypt($id);
        $change_req = MvrRegistrationParticularChange::query()->findOrFail($id);
        $motorVehicle = MvrRegistration::findOrFail($change_req->current_registration_id);
        return view('mvr.particular.show', compact('motorVehicle', 'change_req'));
    }

}
