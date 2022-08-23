<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlDriversLicenseClass
 * 
 * @property int $id
 * @property int $dl_drivers_license_id
 * @property int $dl_license_class_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DlDriversLicense $dl_drivers_license
 * @property DlLicenseClass $dl_license_class
 *
 * @package App\Models
 */
class DlDriversLicenseClass extends Model
{
	protected $table = 'dl_drivers_license_classes';

	protected $casts = [
		'dl_drivers_license_id' => 'int',
		'dl_license_class_id' => 'int'
	];

	protected $fillable = [
		'dl_drivers_license_id',
		'dl_license_class_id'
	];

	public function dl_drivers_license()
	{
		return $this->belongsTo(DlDriversLicense::class);
	}

	public function dl_license_class()
	{
		return $this->belongsTo(DlLicenseClass::class);
	}
}
