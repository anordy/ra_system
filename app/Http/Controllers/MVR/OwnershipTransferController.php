<?php

namespace App\Http\Controllers\MVR;

use App\Enum\Currencies;
use App\Enum\GeneralConstant;
use App\Enum\PaymentStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrOwnershipStatus;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistration;
use App\Models\MvrRequestStatus;
use App\Models\MvrTransferFee;
use App\Models\Taxpayer;
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

class OwnershipTransferController extends Controller
{

    use MotorVehicleSearchTrait, ExchangeRateTrait;

    public function index()
    {
        if (!Gate::allows('motor-vehicle-transfer-ownership')) {
            abort(403);
        }
        return view('mvr.transfer.index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-transfer-ownership')) {
            abort(403);
        }
        $id = decrypt($id);

        $request = MvrOwnershipTransfer::query()->findOrFail($id);
        $motor_vehicle = MvrRegistration::findOrFail($request->mvr_motor_vehicle_id);
        $newOwner = Taxpayer::with('region:id,name', 'district:id,name', 'ward:id,name', 'street:id,name')->findOrFail($request->owner_taxpayer_id);
        $previousOwner = Taxpayer::with('region:id,name', 'district:id,name', 'ward:id,name', 'street:id,name')->findOrfail($request->agent_taxpayer_id);

        return view('mvr.transfer.show', compact('motor_vehicle', 'request','newOwner','previousOwner'));
    }

    public function search($type, $number)
    {
        $motor_vehicle = $this->searchRegistered($type, $number);
        $search_type = ucwords(preg_replace('/-/', ' ', $type));
        $action = 'mvr.ownership-transfer-request';
        $result_route = 'mvr.internal-search-ot';
        return view('mvr.internal-search', compact('motor_vehicle', 'search_type', 'number', 'action', 'result_route'));
    }


    public function approve($id)
    {
        try {
            $id = decrypt($id);
            //Generate control number
            $request = MvrOwnershipTransfer::query()->findOrFail($id);
            $fee = MvrTransferFee::query()
                ->where(['mvr_transfer_category_id' => $request->mvr_transfer_category_id])
                ->first();

            if (empty($fee)) {
                session()->flash(GeneralConstant::ERROR, "Fee for selected transfer category is not configured");
                return redirect()->route('mvr.transfer-ownership.show', encrypt($id));
            }
            $exchange_rate = self::getExchangeRate(Currencies::TZS);
            $amount = $fee->amount;
            $gfs_code = $fee->gfs_code;

            $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            DB::beginTransaction();
            $bill = ZmCore::createBill(
                $request->id,
                get_class($request),
                $taxType->id,
                $request->agent->id ?? $request->new_owner->id,
                get_class($request->agent ?? $request->new_owner),
                !empty($request->agent)?$request->agent->taxpayer->fullname() : $request->new_owner->fullname(),
                !empty($request->agent)? $request->agent->taxpayer->email : $request->new_owner->email,
                ZmCore::formatPhone(!empty($request->agent)? $request->agent->taxpayer->mobile : $request->new_owner->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->description,
                ZmCore::PAYMENT_OPTION_EXACT,
                Currencies::TZS,
                $exchange_rate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $request->id,
                        'billable_type' => get_class($request),
                        'fee_id' => $fee->id,
                        'tax_type_id' => $taxType->id,
                        'fee_type' => get_class($fee),
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' => $gfs_code
                    ]
                ]
            );
            $request->update([
                'mvr_request_status_id' => MvrRequestStatus::query()
                    ->select('id')
                    ->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])
                    ->id
            ]);
            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($bill);
                if ($response->status != ZmResponse::SUCCESS) {
                    session()->flash(GeneralConstant::SUCCESS, 'Request Approved!');
                } else {
                    session()->flash(GeneralConstant::ERROR, 'Control Number request failed');
                }
                event(new SendSms('mvr-ownership-transfer-approval', $request->id));
                event(new SendMail('mvr-ownership-transfer-approval', $request->id));
            } else {
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();
                session()->flash(GeneralConstant::SUCCESS, 'Request Approved, Control Number request sent');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MVR-OWNERSHIP-TRANSFER-APPROVE', [$e]);
            session()->flash(GeneralConstant::ERROR, 'Approval failed, could not update data');
        }
        return redirect()->route('mvr.transfer-ownership.show', encrypt($id));
    }

    public function reject($id)
    {
        $id = decrypt($id);
        //Generate control number
        $request = MvrOwnershipTransfer::query()->findOrFail($id);

        $request->update([
            'mvr_request_status_id' => MvrRequestStatus::query()
                ->select('id')
                ->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_REJECTED])->id
        ]);

        session()->flash(GeneralConstant::ERROR, 'Request has been rejected');

        event(new SendSms('mvr-ownership-transfer-approval', $request->id));
        event(new SendMail('mvr-ownership-transfer-approval', $request->id));

        return redirect()->route('mvr.transfer-ownership.show', encrypt($id));
    }

    public function simulatePayment($id)
    {
        $id = decrypt($id);
        $request = MvrOwnershipTransfer::query()->findOrFail($id);
        try {
            DB::beginTransaction();
            $bill = $request->get_latest_bill();
            $bill->update(['status' => PaymentStatus::PAID]);

            $request->update([
                'mvr_request_status_id' => MvrRequestStatus::query()
                    ->select('id')
                    ->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_ACCEPTED])->id
            ]);

            $request->motor_vehicle->current_owner
                ->update([
                    'mvr_ownership_status_id' => MvrOwnershipStatus::query()
                        ->select('id')
                        ->firstOrCreate(['name' => MvrOwnershipStatus::STATUS_PREVIOUS_OWNER])->id
                ]);

            MvrMotorVehicleOwner::query()->create([
                'mvr_motor_vehicle_id' => $request->mvr_motor_vehicle_id,
                'taxpayer_id' => $request->owner_taxpayer_id,
                'mvr_ownership_status_id' => MvrOwnershipStatus::query()->firstOrCreate(['name' => MvrOwnershipStatus::STATUS_CURRENT_OWNER])->id
            ]);
            DB::commit();
            return redirect()->route('mvr.transfer-ownership.show', encrypt($id));
        } catch (\Exception $e) {
            session()->flash(GeneralConstant::ERROR, 'Could not update status');
            DB::rollBack();
            Log::error('MVR-OWNERSHIP-TRANSFER-SIMULATE-PAYMENT', [$e]);
            return redirect()->route('mvr.transfer-ownership.show', encrypt($id));
        }
    }
}
