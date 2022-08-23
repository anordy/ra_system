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
	protected $table = 'dl_application_status';

	protected $fillable = [
		'name'
	];

	public function dl_license_applications()
	{
		return $this->hasMany(DlLicenseApplication::class);
	}
}
