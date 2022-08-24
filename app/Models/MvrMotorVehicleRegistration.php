<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class MvrMotorVehicleRegistration
 * 
 * @property int $id
 * @property int $mvr_plate_size_id
 * @property int $mvr_plate_color_id
 * @property string|null $plate_number
 * @property int $mvr_plate_number_status_id
 * @property int $mvr_motor_vehicle_id
 * @property int $mvr_registration_type_id
 * 
 * @property MvrColor $mvr_color
 * @property MvrMotorVehicle $mvr_motor_vehicle
 * @property MvrPlateNumberStatus $mvr_plate_number_status
 * @property MvrPlateSize $mvr_plate_size
 * @property MvrRegistrationType $mvr_registration_type
 *
 * @package App\Models
 */
class MvrMotorVehicleRegistration extends Model
{
	protected $table = 'mvr_motor_vehicle_registration';
	public $timestamps = true;

	protected $casts = [
		'id' => 'int',
		'mvr_plate_size_id' => 'int',
		'mvr_plate_color_id' => 'int',
		'mvr_plate_number_status_id' => 'int',
		'mvr_motor_vehicle_id' => 'int',
		'mvr_registration_type_id' => 'int'
	];

	protected $fillable = [
		'mvr_plate_size_id',
		'mvr_plate_color_id',
		'plate_number',
		'mvr_plate_number_status_id',
		'mvr_motor_vehicle_id',
		'registration_date',
		'mvr_registration_type_id'
	];

	public function motor_vehicle()
	{
		return $this->belongsTo(MvrMotorVehicle::class,'mvr_motor_vehicle_id');
	}

	public function plate_number_status()
	{
		return $this->belongsTo(MvrPlateNumberStatus::class,'mvr_plate_number_status_id');
	}

	public function plate_size()
	{
		return $this->belongsTo(MvrPlateSize::class,'mvr_plate_size_id');
	}

	public function registration_type()
	{
		return $this->belongsTo(MvrRegistrationType::class,'mvr_registration_type_id');
	}

    public function current_personalized_registration()
    {
        return $this->hasOne(MvrPersonalizedPlateNumberRegistration::class,'mvr_motor_vehicle_registration_id')->latest();
    }

    public function current_active_personalized_registration()
    {
        return $this->hasOne(MvrPersonalizedPlateNumberRegistration::class,'mvr_motor_vehicle_registration_id')->where(['status'=>'ACTIVE'])->latest();
    }

    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class,'billable')->latest()->first();
        return $bill_item->bill ??  null;
    }


    /**
     * @param mixed $reg_type
     * @return array|mixed|string|string[]|null
     */
    public static function getNexPlateNumber(mixed $reg_type,$class): mixed
    {
        $last_reg = MvrMotorVehicleRegistration::query()
            ->join('mvr_motor_vehicles','mvr_motor_vehicles.id','=','mvr_motor_vehicle_id')
            ->where(['mvr_registration_type_id' => $reg_type->id])
            ->where(['mvr_class_id' => $class->id])
            ->whereNotNull('plate_number')
            ->orderBy('plate_number', 'desc')
            ->lockForUpdate()
            ->first();
        if ($reg_type->name == MvrRegistrationType::TYPE_CORPORATE){
            if (empty($last_reg)) {
                $number = str_pad( '1', 4, '0', STR_PAD_LEFT);
                $plate_number = 'SLS' . $number.$class->name;
            } else {
                $number = preg_replace('/SLS(\d{4}){}/', '$1', $last_reg->plate_number);
                $plate_number = str_pad($number + 1, 4, '0', STR_PAD_LEFT);
                $plate_number = preg_replace('/SLS(.+)(.)$/', 'SMZ' . $plate_number . '$2', $last_reg->plate_number);
            }
        }elseif ($reg_type->name == MvrRegistrationType::TYPE_GOVERNMENT){
            if (empty($last_reg)) {
                $number = str_pad( '1', 4, '0', STR_PAD_LEFT);
                $plate_number = 'SMZ' . $number.$class->name;
            } else {
                $number = preg_replace('/SMZ(\d{4}){}/', '$1', $last_reg->plate_number);
                $plate_number = str_pad($number + 1, 4, '0', STR_PAD_LEFT);
                $plate_number = preg_replace('/SMZ(.+)(.)$/', 'SMZ' . $plate_number . '$2', $last_reg->plate_number);
            }
        }else{
            $reg_type_ids = MvrRegistrationType::query()->whereIn('name',[
                MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED,
                MvrRegistrationType::TYPE_PRIVATE_ORDINARY,
                MvrRegistrationType::TYPE_COMMERCIAL_GOODS_VEHICLE,
                MvrRegistrationType::TYPE_COMMERCIAL_PRIVATE_HIRE,
                MvrRegistrationType::TYPE_COMMERCIAL_SCHOOL_BUS,
                MvrRegistrationType::TYPE_COMMERCIAL_STAFF_BUS,
                MvrRegistrationType::TYPE_COMMERCIAL_TAXI,
            ])->pluck('id')
                ->toArray();
            $last_reg = MvrMotorVehicleRegistration::query()
                ->join('mvr_motor_vehicles','mvr_motor_vehicles.id','=','mvr_motor_vehicle_id')
                ->whereIn('mvr_registration_type_id',$reg_type_ids)
                ->whereNotNull('plate_number')
                ->orderBy(DB::raw('concat(substring(plate_number,5,2),substring(plate_number,2,3))'), 'desc')
                ->lockForUpdate()
                ->first();


            if (empty($last_reg)) {
                $plate_number = $reg_type->initial_plate_number;
            } else {
                $number = preg_replace('/Z(\d{3})([A-Z]{2})/', '$1', $last_reg->plate_number);
                $alpha = preg_replace('/Z(\d{3})([A-Z]{2})/', '$2', $last_reg->plate_number);
                $alpha = !empty($alpha) ? $alpha : 'AA';
                if ($number==998 || $number==999){
                    $number = 1;
                    $alpha = preg_match('/[A-Z]Z/',$alpha) ? chr(ord(substr($alpha,0,1))+1).'A' : substr($alpha,0,1).chr(ord(substr($alpha,1,1))+1);
                }else{
                    $number++;
                }
                //check special number
                if ($number%111==0) $number++;

                $number = str_pad($number, 3, '0', STR_PAD_LEFT);
                $plate_number = 'Z'.$number.$alpha;
            }
        }
        return $plate_number;
    }


    public static function getGoldenPlateNumbers(){
        $last_reg = MvrMotorVehicleRegistration::query()
            ->join('mvr_motor_vehicles','mvr_motor_vehicles.id','=','mvr_motor_vehicle_id')
            ->whereRaw('plate_number REGEXP "Z([\d])(?=(?:.*?\1){2}).{2}[a-zA-Z]{2}"')
            ->orderBy(DB::raw('concat(substring(plate_number,5,2),substring(plate_number,2,3))'), 'desc')
            ->lockForUpdate()
            ->first();
        if (empty($last_reg)){
            $last_special = 'Z000AA';
        }else{
            $last_special = $last_reg->plate_number;
        }

        $plate_numbers = [];
        for ($i=0;$i<20;++$i){
            $number = preg_replace('/Z(\d{3})([A-Z]{2})/', '$1', $last_special);
            $alpha = preg_replace('/Z(\d{3})([A-Z]{2})/', '$2', $last_special);
            if ($number==999){
                $number = 0;
                $alpha = preg_match('/[A-Z]Z/',$alpha) ? chr(ord(substr($alpha,0,1))+1).'A' : substr($alpha,0,1).chr(ord(substr($alpha,1,1))+1);
            }else{
                $number+=111;
            }
            $number = str_pad($number, 3, '0', STR_PAD_LEFT);
            $last_special = 'Z'.$number.$alpha;
            $plate_numbers[] = $last_special;
        }


        return $plate_numbers;
    }

}
