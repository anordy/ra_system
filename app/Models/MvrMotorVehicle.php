<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrMotorVehicle
 * 
 * @property int $id
 * @property string $registration_number
 * @property int $mvr_plate_size_id
 * @property string $plate_color
 * @property int $number_of_axle
 * @property string $chassis_number
 * @property int $year_of_manufacture
 * @property string $engine_number
 * @property float $gross_weight
 * @property string $engine_capacity
 * @property int $seating_capacity
 * @property int $mvr_vehicle_status_id
 * @property int $imported_from_country_id
 * @property int $mvr_color_id
 * @property int $mvr_class_id
 * @property int $mvr_model_id
 * @property int $mvr_fuel_type_id
 * @property int $mvr_transmission_id
 * @property int $mvr_body_type_id
 * @property string $inspection_report_path
 * @property string $certificate_of_worth_path
 * @property int $mvr_registration_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Country $country
 * @property MvrBodyType $mvr_body_type
 * @property MvrClass $mvr_class
 * @property MvrColor $mvr_color
 * @property MvrFuelType $mvr_fuel_type
 * @property MvrModel $mvr_model
 * @property MvrPlateSize $mvr_plate_size
 * @property MvrRegistrationStatus $mvr_registration_status
 * @property MvrTransmissionType $mvr_transmission_type
 * @property MvrVehicleStatus $vehicle_status
 *
 * @package App\Models
 */
class MvrMotorVehicle extends Model implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_motor_vehicles';

	protected $casts = [
		'mvr_plate_size_id' => 'int',
		'number_of_axle' => 'int',
		'year_of_manufacture' => 'int',
		'gross_weight' => 'float',
		'seating_capacity' => 'int',
		'mvr_vehicle_status_id' => 'int',
		'mvr_agent_id' => 'int',
		'imported_from_country_id' => 'int',
		'mvr_color_id' => 'int',
		'mvr_class_id' => 'int',
		'mvr_model_id' => 'int',
		'mvr_fuel_type_id' => 'int',
		'mvr_transmission_id' => 'int',
		'mvr_body_type_id' => 'int',
		'mvr_registration_status_id' => 'int',
		'request_date' => 'datetime:Y-m-d',
	];

	protected $fillable = [
		'registration_date',
		'registration_number',
		'number_of_axle',
		'chassis_number',
		'year_of_manufacture',
		'engine_number',
		'gross_weight',
		'engine_capacity',
		'seating_capacity',
		'mvr_vehicle_status_id',
		'imported_from_country_id',
		'mvr_color_id',
		'mvr_class_id',
		'mvr_model_id',
		'mvr_fuel_type_id',
		'mvr_transmission_id',
		'mvr_body_type_id',
		'inspection_report_path',
		'certificate_of_worth_path',
		'mvr_agent_id',
		'mileage',
		'inspection_date',
		'certificate_number',
		'mvr_registration_status_id'
	];

	public function imported_from_country()
	{
		return $this->belongsTo(Country::class, 'imported_from_country_id');
	}

	public function body_type()
	{
		return $this->belongsTo(MvrBodyType::class,'mvr_body_type_id');
	}

	public function class()
	{
		return $this->belongsTo(MvrClass::class,'mvr_class_id');
	}

	public function color()
	{
		return $this->belongsTo(MvrColor::class,'mvr_color_id');
	}

	public function fuel_type()
	{
		return $this->belongsTo(MvrFuelType::class,'mvr_fuel_type_id');
	}

	public function model()
	{
		return $this->belongsTo(MvrModel::class,'mvr_model_id');
	}

	public function registration_status()
	{
		return $this->belongsTo(MvrRegistrationStatus::class,'mvr_registration_status_id');
	}

    public function motor_vehicle_registrations()
    {
        return $this->hasMany(MvrMotorVehicleRegistration::class,'mvr_motor_vehicle_id');
    }

    public function current_registration()
    {
        return $this->hasOne(MvrMotorVehicleRegistration::class,'mvr_motor_vehicle_id')->latest();
    }

	public function transmission_type()
	{
		return $this->belongsTo(MvrTransmissionType::class, 'mvr_transmission_id');
	}

	public function vehicle_status()
	{
		return $this->belongsTo(MvrVehicleStatus::class,'mvr_vehicle_status_id');
	}

    public function motor_vehicle_owners()
    {
        return $this->hasMany(MvrMotorVehicleOwner::class,'mvr_motor_vehicle_id');
    }

    public function current_owner()
    {
        return $this->hasOne(MvrMotorVehicleOwner::class,'mvr_motor_vehicle_id')
            ->where(['mvr_ownership_status_id'=>MvrOwnershipStatus::query()->firstOrCreate(['name'=>MvrOwnershipStatus::STATUS_CURRENT_OWNER])->id]);
    }

    public function last_owner()
    {
        return $this->hasOne(MvrMotorVehicleOwner::class,'mvr_motor_vehicle_id')
            ->latest();
    }

    public function agent()
    {
        return $this->belongsTo(MvrAgent::class,'mvr_agent_id');
    }
}
