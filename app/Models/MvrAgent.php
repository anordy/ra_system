<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrAgent
 * 
 * @property int $id
 * @property string $tin
 * @property int $taxpayer_id
 * @property string $agent_number
 * @property Carbon $registration_date
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Taxpayer $taxpayer
 * @property Collection|MvrMotorVehicle[] $mvr_motor_vehicles
 * @property Collection|MvrOwnershipTransfer[] $mvr_ownership_transfers
 * @property Collection|MvrRegistrationChangeRequest[] $mvr_registration_change_requests
 *
 * @package App\Models
 */
class MvrAgent extends Model implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_agents';

	protected $casts = [
		'taxpayer_id' => 'int'
	];

	protected $dates = [
		'registration_date'
	];

	protected $fillable = [
		'taxpayer_id',
		'agent_number',
		'registration_date',
		'status',
        'company_name'
	];

	public function taxpayer()
	{
		return $this->belongsTo(Taxpayer::class);
	}

	public function mvr_motor_vehicles()
	{
		return $this->hasMany(MvrMotorVehicle::class);
	}

	public function mvr_ownership_transfers()
	{
		return $this->hasMany(MvrOwnershipTransfer::class);
	}

	public function mvr_registration_change_requests()
	{
		return $this->hasMany(MvrRegistrationChangeRequest::class);
	}
}
