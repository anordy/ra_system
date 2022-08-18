<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\SMSController;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DeRegistrationController extends Controller
{

	public function index(){
		return view('mvr.de-register-requests-index');
	}

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id): View|Factory|Application
    {
        $id = decrypt($id);
        /** @var MvrRegistrationChangeRequest $change_req */
        $request = MvrDeRegistrationRequest::query()->find($id);
        $motor_vehicle = $request->motor_vehicle;
        return view('mvr.de-registration-req-show',compact('motor_vehicle','request'));
    }

    public function search($type,$number){
        if ($type=='chassis'){
            $motor_vehicle = MvrMotorVehicle::query()->where(['chassis_number'=>$number])->first();
        }else{
            $motor_vehicle = MvrMotorVehicleRegistration::query()
                    ->where(['plate_number'=>$number])
                    ->first()->motor_vehicle ?? null;
        }
        $search_type = ucwords(preg_replace('/-/',' ',$type));
        $action = 'mvr.de-registration-request';
        $result_route = 'mvr.internal-search-dr';
        return view('mvr.internal-search',compact('motor_vehicle','search_type','number','action','result_route'));
    }

    public function zbsSubmit($id){
        $id = decrypt($id);
        $request = MvrDeRegistrationRequest::query()->findOrFail($id);
        $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_APPROVAL])->id]);
        //Notify Agent here through SMS
        return redirect()->route('mvr.de-register-requests.show',encrypt($id));
    }


    public function approve($id){
        $id = decrypt($id);
        //Generate control number
        $request = MvrDeRegistrationRequest::query()->find($id);
        $fee_type = MvrFeeType::query()->firstOrCreate(['type' => 'De-Registration']);
        $fee = MvrFee::query()->where([
            'mvr_registration_type_id' => $request->motor_vehicle->current_registration->mvr_registration_type_id,
            'mvr_fee_type_id' => $fee_type->id,
        ])->first();

        if (empty($fee)) {
            session()->flash('error', "Fee for selected registration type (de-registration) is not configured");
            return redirect()->route('mvr.de-register-requests.show',encrypt($id));
        }
        $exchange_rate = 1;
        $amount = $fee->amount;

        try{
            DB::beginTransaction();
            $bill = ZmCore::createBill(
                $request->id,
                get_class($request),
                null,
                $request->agent->id,
                get_class($request->agent),
                $request->agent->fullname(),
                $request->agent->email,
                ZmCore::formatPhone($request->agent->mobile),
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
                        'tax_type_id' => null,
                        'amount' => $amount,
                        'currency' => 'TZS',
                        'exchange_rate' => $exchange_rate,
                        'equivalent_amount' => $exchange_rate * $amount,
                        'gfs_code' =>  $fee->gfs_code
                    ]
                ]
            );
            $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])->id]);
            DB::commit();

            $response = ZmCore::sendBill($bill);
            if ($response->status != ZmResponse::SUCCESS){
                session()->flash("success",'Request Approved!');
                session()->flash("error",'Control Number request failed');
            }else{
                session()->flash("success",'Request Approved, Control Number request sent');
            }
        }catch (\Exception $e){
            session()->flash("error",'Approval failed,  could not update data!');
            DB::rollBack();
        }
        return redirect()->route('mvr.de-register-requests.show',encrypt($id));
    }


    public function simulatePayment($id){
        $id = decrypt($id);
        $request = MvrDeRegistrationRequest::query()->find($id);

        $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_RETIRED]);
        try {
            DB::beginTransaction();
            $bill = $request->get_latest_bill();
            $bill->update(['status'=>'Paid']);
            $request->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_ACCEPTED])->id]);
            $request->motor_vehicle->update(['mvr_registration_status_id'=>MvrRegistrationStatus::query()->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_DE_REGISTERED])->id]);
            $mvr_reg = $request->motor_vehicle->current_registration;
            $mvr_reg->update(['mvr_plate_number_status_id'=>$plate_status->id]);
            DB::commit();
            return redirect()->route('mvr.de-register-requests.show',encrypt($id));
        }catch (\Exception $e){
            session()->flash('error', 'Could not update status');
            DB::rollBack();
            report($e);
            return redirect()->route('mvr.de-register-requests.show',encrypt($id));
        }
    }
}
