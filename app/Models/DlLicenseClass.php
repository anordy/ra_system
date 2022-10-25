<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class DlLicenseClass
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|DlApplicationLicenseClass[] $dl_application_license_classes
 * @property Collection|DlDriversLicenseClass[] $dl_drivers_license_classes
 *
 * @package App\Models
 */
class DlLicenseClass extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'dl_license_classes';

	protected $fillable = [
		'name',
		'description'
	];

	public function dl_application_license_classes()
	{
		return $this->hasMany(DlApplicationLicenseClass::class);
	}

	public function dl_drivers_license_classes()
	{
		return $this->hasMany(DlDriversLicenseClass::class);
	}
}
