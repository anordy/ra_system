<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrGoldenPlateNumberRegistration
 * 
 * @property int $id
 * @property string $plate_number
 * @property string $status
 * @property int $mvr_motor_vehicle_registration_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MvrMotorVehicleRegistration $mvr_motor_vehicle_registration
 *
 * @package App\Models
 */
class MvrPersonalizedPlateNumberRegistration extends Model
{
	protected $table = 'mvr_personalized_plate_number_registration';

	protected $casts = [
		'mvr_motor_vehicle_registration_id' => 'int'
	];

	protected $fillable = [
		'plate_number',
		'status',
		'mvr_motor_vehicle_registration_id'
	];

	public function mvr_motor_vehicle_registration()
	{
		return $this->belongsTo(MvrMotorVehicleRegistration::class);
	}
}
