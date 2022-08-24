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
 * Class MvrRegistrationStatus
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
class MvrRegistrationStatus extends Model
{
	use SoftDeletes;

    const STATUS_REGISTERED = 'REGISTERED';
    const STATUS_PLATE_NUMBER_PRINTING = 'PLATE NUMBER PRINTING';
    const STATUS_PENDING_PAYMENT = 'REGISTRATION FEE PAYMENT';
    const STATUS_REVENUE_OFFICER_APPROVAL = 'REVENUE OFFICER APPROVAL';
    const STATUS_INSPECTION = 'INSPECTION';
    const STATUS_DE_REGISTERED = 'DE REGISTERED';
    protected $table = 'mvr_registration_status';

	protected $fillable = [
		'name'
	];

	public function mvr_motor_vehicles()
	{
		return $this->hasMany(MvrMotorVehicle::class);
	}
}
