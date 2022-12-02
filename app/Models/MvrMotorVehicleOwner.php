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
 * Class MvrMotorVehicleOwner
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $mvr_motor_vehicle_id
 * @property int $taxpayer_id
 * @property Carbon|null $date
 * @property int $mvr_ownership_status_id
 * @property string|null $deleted_at
 * 
 * @property MvrMotorVehicle $mvr_motor_vehicle
 * @property MvrOwnershipStatus $mvr_ownership_status
 * @property Taxpayer $taxpayer
 *
 * @package App\Models
 */
class MvrMotorVehicleOwner extends Model implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_vehicle_owners';

	protected $casts = [
		'mvr_motor_vehicle_id' => 'int',
		'taxpayer_id' => 'int',
		'mvr_ownership_status_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'mvr_motor_vehicle_id',
		'taxpayer_id',
		'date',
		'mvr_ownership_status_id'
	];

	public function motor_vehicle()
	{
		return $this->belongsTo(MvrMotorVehicle::class);
	}

	public function ownership_status()
	{
		return $this->belongsTo(MvrOwnershipStatus::class);
	}

	public function taxpayer()
	{
		return $this->belongsTo(Taxpayer::class);
	}
}
