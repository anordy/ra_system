<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrVehicleStatus
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|MvrMotorVehicle[] $mvr_motor_vehicles
 *
 * @package App\Models
 */
class MvrVehicleStatus extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_vehicle_status';

	protected $fillable = [
		'name'
	];

	public function mvr_motor_vehicles()
	{
		return $this->hasMany(MvrMotorVehicle::class);
	}
}
