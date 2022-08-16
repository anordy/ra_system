<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
	public $incrementing = false;
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


    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class,'billable')->latest()->first();
        return $bill_item->bill ??  null;
    }


    /**
     * @param mixed $reg_type
     * @return array|mixed|string|string[]|null
     */
    public static function getNexPlateNumber(mixed $reg_type): mixed
    {
        $last_reg = MvrMotorVehicleRegistration::query()
            ->where(['mvr_registration_type_id' => $reg_type->id])
            ->whereNotNull('plate_number')
            ->orderBy('plate_number', 'desc')
            ->lockForUpdate()
            ->first();

        if (empty($last_reg)) {
            $plate_number = $reg_type->initial_plate_number;
        } else {
            $plate_number = preg_replace('/' . $reg_type->plate_number_pattern . '/', '$1', $last_reg->plate_number);
            $plate_number = str_pad($plate_number + 1, 4, '0', STR_PAD_LEFT);
            $plate_number = preg_replace('/SMZ(.+)(.)$/', 'SMZ' . $plate_number . '$2', $last_reg->plate_number);
        }
        return $plate_number;
    }


    public static function getGoldenPlateNumbers($reg_type_id){
        return ['SMZ1111A','SMZ222A','SMZ222A','SMZ4444A']; //TODO: Logic for generation of golden numbers
    }
}
