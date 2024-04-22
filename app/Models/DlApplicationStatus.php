<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlApplicationStatus
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|DlLicenseApplication[] $dl_license_applications
 *
 * @package App\Models
 */
class DlApplicationStatus extends Model
{
    const STATUS_COMPLETED = 'Completed';
    const RENEW = 'RENEW';
    const ACTIVE = 'ACTIVE';
    protected $table = 'dl_application_status';
    const STATUS_PENDING_APPROVAL = 'Pending Approval';
    const STATUS_INITIATED = 'Initiated';
    const STATUS_DETAILS_CORRECTION = 'Details Correction';
    const STATUS_PENDING_PAYMENT = 'Pending Payment';
    const STATUS_TAKING_PICTURE = 'Taking Picture';
    const STATUS_LICENSE_PRINTING = 'License Printing';
    const CORRECTION = 'Correction';

    protected $fillable = [
		'name'
	];

	public function dl_license_applications()
	{
		return $this->hasMany(DlLicenseApplication::class);
	}
}
