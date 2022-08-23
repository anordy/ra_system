<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlDriversLicense
 * 
 * @property int $id
 * @property int $dl_drivers_license_owner_id
 * @property string $license_number
 * @property int $dl_license_duration_id
 * @property Carbon $issued_date
 * @property Carbon $expiry_date
 * @property int $dl_license_class_id
 * @property string $license_restrictions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DlDriversLicenseOwner $dl_drivers_license_owner
 * @property DlLicenseDuration $dl_license_duration
 * @property Collection|DlDriversLicenseClass[] $dl_drivers_license_classes
 *
 * @package App\Models
 */
class DlDriversLicense extends Model
{
	protected $table = 'dl_drivers_licenses';

	protected $casts = [
		'dl_drivers_license_owner_id' => 'int',
		'dl_license_duration_id' => 'int',
		'dl_license_class_id' => 'int'
	];

	protected $dates = [
		'issued_date',
		'expiry_date'
	];

	protected $fillable = [
		'dl_drivers_license_owner_id',
		'license_number',
		'dl_license_duration_id',
		'issued_date',
		'expiry_date',
		'dl_license_class_id',
		'license_restrictions'
	];

	public function dl_drivers_license_owner()
	{
		return $this->belongsTo(DlDriversLicenseOwner::class);
	}

	public function dl_license_duration()
	{
		return $this->belongsTo(DlLicenseDuration::class);
	}

	public function dl_drivers_license_classes()
	{
		return $this->hasMany(DlDriversLicenseClass::class);
	}
}
