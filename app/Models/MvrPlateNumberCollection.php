<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrPlateNumberCollection
 * 
 * @property int $id
 * @property int $mvr_registration_id
 * @property Carbon $collection_date
 * @property string $collector_name
 * @property string $collector_phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MvrMotorVehicleRegistration $mvr_motor_vehicle_registration
 *
 * @package App\Models
 */
class MvrPlateNumberCollection extends Model
{
	protected $table = 'mvr_plate_number_collections';

	protected $casts = [
		'mvr_registration_id' => 'int'
	];

	protected $dates = [
		'collection_date'
	];

	protected $fillable = [
		'mvr_registration_id',
		'collection_date',
		'collector_name',
		'collector_phone'
	];

	public function mvr_motor_vehicle_registration()
	{
		return $this->belongsTo(MvrMotorVehicleRegistration::class, 'mvr_registration_id');
	}
}
