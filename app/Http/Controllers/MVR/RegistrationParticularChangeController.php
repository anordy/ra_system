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


    public function approve($id)
    {
        Gate::authorize('mvr_approve_registration_change');
        try {

            $id = decrypt($id);
            //Generate control number
            $change_req = MvrRegistrationChangeRequest::query()->find($id);
            $fee_type = MvrFeeType::query()->firstOrCreate(['type' => 'Registration Change']);

            $fee = MvrFee::query()
                ->select([
                    'id',
                    'amount',
                    'gfs_code',
                    'description'
                ])
                ->where([
                    'mvr_registration_type_id' => $change_req->requested_registration_type_id,
                    'mvr_fee_type_id' => $fee_type->id,
                ])
                ->first();

            if (empty($fee)) {
                session()->flash(GeneralConstant::ERROR, __("Fee for selected registration type (change) is not configured"));
                return redirect()->route('mvr.reg-change-requests.show', encrypt($id));
            }
            $exchange_rate = self::getExchangeRate(Currencies::TZS);
            $amount = $fee->amount;
            $gfs_code = $fee->gfs_code;
            $taxType = TaxType::query()->select('id')->where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();


            DB::beginTransaction();

            $bill = ZmCore::createBill(
                $change_req->id,
                get_class($change_req),
                $taxType->id,
                $change_req->agent->id,
                get_class($change_req->agent),
                $change_req->agent->taxpayer->getFullNameAttribute(),
                $change_req->agent->taxpayer->email,
                ZmCore::formatPhone($change_req->agent->taxpayer->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->description,
                ZmCore::PAYMENT_OPTION_EXACT,
                Currencies::TZS,
                $exchange_rate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $change_req->id,
                        'billable_type' => get_class($change_req),
                        'fee_id' => $fee->id,
                        'fee_type' => get_class($fee),
                        'tax_type_id' => $taxType->id,
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' => $gfs_code
                    ]
                ]
            );
            $change_req->update(['mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])->id]);

            $response = ZmCore::sendBill($bill);
            if ($response->status != ZmResponse::SUCCESS) {
                session()->flash(GeneralConstant::SUCCESS, 'Request Approved!');
            } else {
                session()->flash(GeneralConstant::ERROR, __('Control Number request failed'));
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash(GeneralConstant::ERROR, __('Approval failed, could not update data'));
        }


        return redirect()->route('mvr.reg-change-requests.show', encrypt($id));
    }

}
