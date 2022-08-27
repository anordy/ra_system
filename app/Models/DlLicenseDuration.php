<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlLicenseDuration
 * 
 * @property int $id
 * @property int $number_of_years
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|DlDriversLicense[] $dl_drivers_licenses
 * @property Collection|DlLicenseApplication[] $dl_license_applications
 *
 * @package App\Models
 */
class DlLicenseDuration extends Model
{
	protected $table = 'dl_license_durations';

	protected $casts = [
		'number_of_years' => 'int'
	];

	protected $fillable = [
		'number_of_years',
		'description'
	];

	public function dl_drivers_licenses()
	{
		return $this->hasMany(DlDriversLicense::class);
	}

	public function dl_license_applications()
	{
		return $this->hasMany(DlLicenseApplication::class);
	}
}
