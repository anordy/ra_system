<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RioRegister
 * 
 * @property int $id
 * @property int $dl_drivers_license_owner_id
 * @property int $mvr_motor_vehicle_registration_id
 * @property Carbon $date
 * @property string $block_type
 * @property string|null $block_status
 * @property Carbon|null $block_removed_at
 * @property int|null $block_removed_by
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property DlDriversLicenseOwner $dl_drivers_license_owner
 * @property MvrMotorVehicleRegistration $mvr_motor_vehicle_registration
 * @property Collection|RioRegisterOffence[] $rio_register_offences
 *
 * @package App\Models
 */
class RioRegister extends Model
{
	protected $table = 'rio_register';

	protected $casts = [
		'dl_drivers_license_owner_id' => 'int',
		'mvr_motor_vehicle_registration_id' => 'int',
		'block_removed_by' => 'int',
		'created_by' => 'int'
	];

	protected $dates = [
		'date',
		'block_removed_at'
	];

	protected $fillable = [
		'dl_drivers_license_owner_id',
		'mvr_motor_vehicle_registration_id',
		'date',
		'block_type',
		'block_status',
		'block_removed_at',
		'block_removed_by',
		'created_by'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function drivers_license_owner()
	{
		return $this->belongsTo(DlDriversLicenseOwner::class,'dl_drivers_license_owner_id');
	}

	public function motor_vehicle_registration()
	{
		return $this->belongsTo(MvrMotorVehicleRegistration::class,'mvr_motor_vehicle_registration_id');
	}

	public function register_offences()
	{
		return $this->hasMany(RioRegisterOffence::class,'rio_register_id');
	}
}
