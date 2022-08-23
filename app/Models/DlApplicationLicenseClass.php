<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlApplicationLicenseClass
 * 
 * @property int $id
 * @property int $dl_license_application_id
 * @property int $dl_license_class_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DlLicenseApplication $dl_license_application
 * @property DlLicenseClass $dl_license_class
 *
 * @package App\Models
 */
class DlApplicationLicenseClass extends Model
{
	protected $table = 'dl_application_license_classes';

	protected $casts = [
		'dl_license_application_id' => 'int',
		'dl_license_class_id' => 'int'
	];

	protected $fillable = [
		'dl_license_application_id',
		'dl_license_class_id'
	];

	public function dl_license_application()
	{
		return $this->belongsTo(DlLicenseApplication::class);
	}

	public function dl_license_class()
	{
		return $this->belongsTo(DlLicenseClass::class);
	}
}
