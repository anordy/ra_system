<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\SMSController;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleOwner;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrOwnershipStatus;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\MvrTransferFee;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\MotorVehicleSearchTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class OwnershipTransferController extends Controller
{

    use MotorVehicleSearchTrait;

    public function index(){
		return view('mvr.ownership-transfer-index');
	}

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $id = decrypt($id);
        /** @var MvrRegistrationChangeRequest $change_req */
        $request = MvrOwnershipTransfer::query()->find($id);
        $motor_vehicle = $request->motor_vehicle;
        return view('mvr.ownership-transfer-show',compact('motor_vehicle','request'));
    }

    public function search($type,$number){
        $motor_vehicle = $this->searchRegistered($type,$number);
        $search_type = ucwords(preg_replace('/-/',' ',$type));
        $action = 'mvr.ownership-transfer-request';
        $result_route = 'mvr.internal-search-ot';
        return view('mvr.internal-search',compact('motor_vehicle','search_type','number','action','result_route'));
    }


    public function approve($id){
        $id = decrypt($id);
        //Generate control number
        $request = MvrOwnershipTransfer::query()->find($id);
        $fee = MvrTransferFee::query()
            ->where(['mvr_transfer_category_id' => $request->mvr_transfer_category_id])
            ->first();

        if (empty($fee)) {
            session()->flash('error', "Fee for selected transfer category is not configured");
            return redirect()->route('mvr.transfer-ownership.show',encrypt($id));
        }
        $exchange_rate = 1;
        $amount = $fee->amount;
        $gfs_code = $fee->gfs_code;
        try{
            DB::beginTransaction();
            $bill = ZmCore::createBill(
                $request->id,
                $request->id,
                1,//todo: remove
                $request->agent->id,
                get_class($request->agent),
                $request->agent->taxpayer->fullname(),
                $request->agent->taxpayer->email,
                ZmCore::formatPhone($request->agent->taxpayer->mobile),
                Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
                $fee->description,
                ZmCore::PAYMENT_OPTION_EXACT,
                'TZS', $exchange_rate,
                auth()->user()->id,
                get_class(auth()->user()),
                [
                    [
                        'billable_id' => $request->id,
                        'billable_type' => get_class($request),
                        'fee_id' => $fee->id,
                        'tax_type_id' => 1, //todo: remove
                        'fee_type' => get_class($fee),
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' => $gfs_code
                    ]
                ]
            );
            $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])->id]);

            $response = ZmCore::sendBill($bill);
            if ($response->status != ZmResponse::SUCCESS){
                session()->flash("success",'Request Approved!');
                session()->flash("error",'Control Number request failed');
            }else{
                session()->flash("success",'Request Approved, Control Number request sent');
            }
            DB::commit();
        }catch (\Exception $e){
            report($e);
            session()->flash('error','Approval failed, could not update data');
            DB::rollBack();
        }
        return redirect()->route('mvr.transfer-ownership.show',encrypt($id));
    }

    public function reject($id){
        $id = decrypt($id);
        //Generate control number
        $request = MvrOwnershipTransfer::query()->find($id);

        $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_REJECTED])->id]);
        (new SMSController())->sendSMS('255753387833','ZanMalipo','Request Rejected');

        session()->flash('warning','Request has been rejected');

        return redirect()->route('mvr.transfer-ownership.show',encrypt($id));
    }

    public function simulatePayment($id){
        $id = decrypt($id);
        $request = MvrOwnershipTransfer::query()->find($id);
        try {
            DB::beginTransaction();
            $bill = $request->get_latest_bill();
            $bill->update(['status'=>'Paid']);
            $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_ACCEPTED])->id]);
            $request->motor_vehicle->current_owner
                ->update(['mvr_ownership_status_id'=>MvrOwnershipStatus::query()->firstOrCreate(['name'=>MvrOwnershipStatus::STATUS_PREVIOUS_OWNER])->id]);
            MvrMotorVehicleOwner::query()->create([
                'mvr_motor_vehicle_id'=>$request->mvr_motor_vehicle_id,
                'taxpayer_id'=>$request->owner_taxpayer_id,
                'mvr_ownership_status_id'=>MvrOwnershipStatus::query()->firstOrCreate(['name'=>MvrOwnershipStatus::STATUS_CURRENT_OWNER])->id
            ]);
            DB::commit();
            return redirect()->route('mvr.transfer-ownership.show',encrypt($id));
        }catch (\Exception $e){
            session()->flash('error', 'Could not update status');
            DB::rollBack();
            report($e);
            return redirect()->route('mvr.transfer-ownership.show',encrypt($id));
        }
    }
}
