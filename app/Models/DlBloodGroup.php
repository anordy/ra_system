<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlBloodGroup
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|DlDriversLicenseOwner[] $dl_drivers_license_owners
 * @property Collection|DlLicenseApplication[] $dl_license_applications
 *
 * @package App\Models
 */
class DlBloodGroup extends Model
{
	protected $table = 'dl_blood_groups';

	protected $fillable = [
		'name'
	];

	public function dl_drivers_license_owners()
	{
		return $this->hasMany(DlDriversLicenseOwner::class);
	}

	public function dl_license_applications()
	{
		return $this->hasMany(DlLicenseApplication::class);
	}
}
