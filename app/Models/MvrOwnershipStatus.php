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
 * Class MvrOwnershipStatus
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|MvrMotorVehicleOwner[] $mvr_motor_vehicle_owners
 *
 * @package App\Models
 */
class MvrOwnershipStatus extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_ownership_status';

	protected $fillable = [
		'name'
	];

	public function mvr_motor_vehicle_owners()
	{
		return $this->hasMany(MvrMotorVehicleOwner::class);
	}
}
