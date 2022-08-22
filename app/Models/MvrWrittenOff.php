<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrWrittenOff
 * 
 * @property int $id
 * @property int $mvr_motor_vehicle_id
 * @property Carbon $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MvrMotorVehicle $mvr_motor_vehicle
 *
 * @package App\Models
 */
class MvrWrittenOff extends Model
{
	protected $table = 'mvr_written_off';

	protected $casts = [
		'mvr_motor_vehicle_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'mvr_motor_vehicle_id',
		'date'
	];

	public function motor_vehicle()
	{
		return $this->belongsTo(MvrMotorVehicle::class,'mvr_motor_vehicle_id');
	}
}
