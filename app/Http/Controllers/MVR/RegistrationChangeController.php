<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPersonalizedPlateNumberRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RegistrationChangeController extends Controller
{

	public function index(){
		return view('mvr.reg-change-index');
	}

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id){
        $id = decrypt($id);
        /** @var MvrRegistrationChangeRequest $change_req */
        $change_req = MvrRegistrationChangeRequest::query()->find($id);
        $motor_vehicle =$change_req->current_registration->motor_vehicle;
        return view('mvr.reg-change-req-show',compact('motor_vehicle','change_req'));
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
        $action = 'mvr.registration-change-request';
        return view('mvr.internal-search',compact('motor_vehicle','search_type','number','action'));
    }


    public function approve($id){
        Gate::authorize('mvr_approve_registration_change');
        $id = decrypt($id);
        //Generate control number
        $change_req = MvrRegistrationChangeRequest::query()->find($id);
        $fee_type = MvrFeeType::query()->firstOrCreate(['type' => 'Registration Change']);

        $fee = MvrFee::query()->where([
            'mvr_registration_type_id' => $change_req->requested_registration_type_id,
            'mvr_fee_type_id' => $fee_type->id,
        ])->first();

        if (empty($fee)) {
            session()->flash('error', "Fee for selected registration type (change) is not configured");
            return redirect()->route('mvr.reg-change-requests.show',encrypt($id));
        }
        $exchange_rate = 1;
        $amount = $fee->amount;
        $gfs_code = $fee->gfs_code;

        ZmCore::createBill(
            $change_req->id,
            get_class($change_req),
            null,
            $change_req->agent->id,
            get_class($change_req->agent),
            $change_req->agent->fullname(),
            $change_req->agent->email,
            ZmCore::formatPhone($change_req->agent->mobile),
            Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            $fee->description,
            ZmCore::PAYMENT_OPTION_EXACT,
            'TZS',
            1,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $change_req->id,
                    'billable_type' => get_class($change_req),
                    'fee_id' => $fee->id,
                    'fee_type' => get_class($fee),
                    'tax_type_id' => null,
                    'amount' => $amount,
                    'currency' => 'TZS',
                    'exchange_rate' => $exchange_rate,
                    'equivalent_amount' => $exchange_rate * $amount,
                    'gfs_code' => $gfs_code
                ]
            ]
        );
        $change_req->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_PENDING_PAYMENT])->id]);
        return redirect()->route('mvr.reg-change-requests.show',encrypt($id));
    }


    public function simulatePayment($id){
        Gate::authorize('mvr_approve_registration_change');
        $id = decrypt($id);
        $change_req = MvrRegistrationChangeRequest::query()
            ->find($id);

        $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_GENERATED]);

        try {
            DB::beginTransaction();
            $reg_type = $change_req->requested_registration_type;
            $bill = $change_req->get_latest_bill();
            $bill->update(['status'=>'Paid']);

            $change_req->update(['mvr_request_status_id'=>MvrRequestStatus::query()->firstOrCreate(['name'=>MvrRequestStatus::STATUS_RC_ACCEPTED])->id]);
            if ($reg_type->external_defined == 1){
                $plate_number = $change_req->custom_plate_number;
            }else{
                $plate_number = MvrMotorVehicleRegistration::getNexPlateNumber($reg_type,$change_req->current_registration->motor_vehicle->class);
            }
            if ($reg_type->name == MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED &&
                !empty($change_req->current_registration) &&
                ($change_req->current_registration->registration_type->name == MvrRegistrationType::TYPE_PRIVATE_GOLDEN  ||
                $change_req->current_registration->registration_type->name == MvrRegistrationType::TYPE_PRIVATE_ORDINARY  ||
                $change_req->current_registration->registration_type->name == MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED)){
                //In this case we do not need to insert a new registration
                $mvr_id = $change_req->current_registration->id;
            }else{
                $change_req->current_registration->update([
                    'mvr_plate_number_status_id'=>MvrPlateNumberStatus::query()->where(['name' => MvrPlateNumberStatus::STATUS_RETIRED])->first()->id
                ]);
                $mvr_id = MvrMotorVehicleRegistration::query()->insertGetId([
                    'plate_number'=>$plate_number,
                    'mvr_registration_type_id'=>$reg_type->id,
                    'mvr_plate_size_id' => $change_req->mvr_plate_size_id,
                    'mvr_motor_vehicle_id' => $change_req->current_registration->mvr_motor_vehicle_id,
                    'mvr_plate_number_status_id'=>$plate_status->id,
                    'registration_date'=>date('Y-m-d')
                ]);
            }


            if ($reg_type->name == MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED){
                MvrPersonalizedPlateNumberRegistration::query()->where(['mvr_motor_vehicle_registration_id'=>$mvr_id])->update(['status'=>'RETIRED']);
                MvrPersonalizedPlateNumberRegistration::query()->create([
                    'plate_number'=>$change_req->custom_plate_number,
                    'status'=>'ACTIVE',
                    'mvr_motor_vehicle_registration_id'=>$mvr_id
                ]);
            }
            DB::commit();
            return redirect()->route('mvr.reg-change-requests.show',encrypt($mvr_id));
        }catch (\Exception $e){
            session()->flash('error', 'Could not update status');
            DB::rollBack();
            report($e);
            return redirect()->route('mvr.reg-change-requests.show',encrypt($id));
        }
    }
}
