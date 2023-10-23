<?php

namespace App\Http\Controllers\MVR;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\TaxType;
use App\Services\Api\TraInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\MotorVehicleSearchTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class DeRegistrationController extends Controller
{
    use MotorVehicleSearchTrait;

    public function index()
    {
        if (!Gate::allows('motor-vehicle-deregistration')) {
            abort(403);
        }
        return view('mvr.de-register-requests-index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-deregistration')) {
            abort(403);
        }
        $id = decrypt($id);
        /** @var MvrRegistrationChangeRequest $change_req */
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);
        $motor_vehicle = $request->motor_vehicle;
        return view('mvr.de-registration-req-show', compact('motor_vehicle', 'request'));
    }

    public function search($type, $number)
    {
        $motor_vehicle = $this->searchDeRegistered($type, $number);
        $search_type = ucwords(preg_replace('/-/', ' ', $type));
        $action = 'mvr.de-registration-request';
        $result_route = 'mvr.internal-search-dr';
        return view('mvr.internal-search', compact('motor_vehicle', 'search_type', 'number', 'action', 'result_route'));
    }

    public function zbsSubmit($id)
    {
        $id = decrypt($id);
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);
        $request->update(['mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id]);
        //Notify Agent here through SMS
        return redirect()->route('mvr.de-register-requests.show', encrypt($id));
    }


    public function approve($id)
    {
        $id = decrypt($id);
        //Generate control number
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);
        $fee_type = MvrFeeType::query()->firstOrCreate(['type' => 'De-Registration']);
        $fee = MvrFee::query()->where([
            'mvr_registration_type_id' => $request->motor_vehicle->current_registration->mvr_registration_type_id,
            'mvr_fee_type_id' => $fee_type->id,
        ])->first();

        if (empty($fee)) {
            session()->flash('error', "Fee for selected registration type (de-registration) is not configured");
            return redirect()->route('mvr.de-register-requests.show', encrypt($id));
        }
        $exchange_rate = 1;
        $amount = $fee->amount;

        try {
            $taxType = TaxType::where('code', TaxType::PUBLIC_SERVICE)->firstOrFail();
            DB::beginTransaction();
            $bill = ZmCore::createBill(
                $request->id,
                get_class($request),
                $taxType->id,
                $request->agent->id,
                get_class($request->agent),
                $request->agent->taxpayer->fullname(),
                $request->agent->taxpayer->email,
                ZmCore::formatPhone($request->agent->taxpayer->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->description,
                ZmCore::PAYMENT_OPTION_EXACT,
                'TZS',
                1,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $request->id,
                        'billable_type' => get_class($request),
                        'fee_id' => $fee->id,
                        'fee_type' => get_class($fee),
                        'tax_type_id' => $taxType->id,
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' =>  $fee->gfs_code
                    ]
                ]
            );
            $request->update(['mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])->id]);

            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($bill);
                if ($response->status != ZmResponse::SUCCESS) {
                    session()->flash("success", 'Request Approved!');
                    session()->flash("error", 'Control Number request failed');
                } else {
                    session()->flash("success", 'Request Approved, Control Number request sent');
                }
            } else {
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = rand(2000070001000, 2000070009999);
                $bill->save();
                session()->flash("success", 'Request Approved, Control Number request sent');
            }

            DB::commit();
        } catch (\Exception $e) {
            session()->flash("error", 'Approval failed,  could not update data!');
            DB::rollBack();
            report($e);
        }
        return redirect()->route('mvr.de-register-requests.show', encrypt($id));
    }

    public function reject($id)
    {
        $id = decrypt($id);
        //Generate control number
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);

        $request->update(['mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_REJECTED])->id]);
        event(new SendSms('mvr-de-registration-approval', $request->id));
        event(new SendMail('mvr-de-registration-approval', $request->id));

        session()->flash('warning', 'Request has been rejected');

        return redirect()->route('mvr.de-register-requests.show', encrypt($id));
    }

    public function simulatePayment($id)
    {
        $id = decrypt($id);
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);

        $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_RETIRED]);
        try {
            DB::beginTransaction();
            $bill = $request->get_latest_bill();
            $bill->update(['status' => 'paid']);
            $request->update(['certificate_date' => date('Y-m-d'), 'mvr_request_status_id' => MvrRequestStatus::query()->firstOrCreate(['name' => MvrRequestStatus::STATUS_RC_ACCEPTED])->id]);
            $request->motor_vehicle->update(['mvr_registration_status_id' => MvrRegistrationStatus::query()->firstOrCreate(['name' => MvrRegistrationStatus::STATUS_DE_REGISTERED])->id]);
            $mvr_reg = $request->motor_vehicle->current_registration;
            $mvr_reg->update(['mvr_plate_number_status_id' => $plate_status->id]);
            DB::commit();
            // TODO: Send deregistration status as a job
            $traService = new TraInternalService();
            $traService->postPlateNumber($request->motor_vehicle->chassis_number, $mvr_reg->plate_number, 'deregistration');
            return redirect()->route('mvr.de-register-requests.show', encrypt($id));
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Could not update status');
            DB::rollBack();
            return redirect()->route('mvr.de-register-requests.show', encrypt($id));
        }
    }
}
