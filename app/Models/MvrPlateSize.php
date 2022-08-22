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
 * Class MvrPlateSize
 * 
 * @property int $id
 * @property string $size
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|MvrMotorVehicle[] $mvr_motor_vehicles
 *
 * @package App\Models
 */
class MvrPlateSize extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_plate_sizes';

	protected $fillable = [
		'name'
	];

	public function mvr_motor_vehicles()
	{
		return $this->hasMany(MvrMotorVehicle::class);
	}
}
