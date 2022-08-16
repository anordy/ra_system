<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrRequestStatus
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|MvrRegistrationChangeRequest[] $mvr_registration_change_requests
 *
 * @package App\Models
 */
class MvrRequestStatus extends Model
{
    const STATUS_RC_INITIATED = 'Initiated';
    const STATUS_RC_PENDING_PAYMENT = 'Pending Payment';
    const STATUS_RC_MANAGER_APPROVAL = 'Registration Manager Approval';
    const STATUS_RC_PENDING_APPROVAL = 'Pending Approval';
    const STATUS_RC_ACCEPTED = 'Accepted';
    protected $table = 'mvr_request_status';

	protected $fillable = [
		'name'
	];

	public function mvr_registration_change_requests()
	{
		return $this->hasMany(MvrRegistrationChangeRequest::class);
	}
}
