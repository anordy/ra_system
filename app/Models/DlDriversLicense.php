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
 * @property Collection|DlDriversLicenseClass[] $drivers_license_classes
 *
 * @package App\Models
 */
class DlDriversLicense extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    const STATUS_DAMAGED_OR_LOST ='LOST/DAMAGED';
    const STATUS_EXPIRED ='EXPIRED';
    const ACTIVE ='ACTIVE';

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

	protected $guarded = [];

    public static function getNextLicenseNumber()
    {
        $last = self::query()->orderBy('license_number','DESC')->first();
        if (empty($last)){
            return '1000000001';
        }
        return $last->license_number+1;
    }

    public function drivers_license_owner()
	{
		return $this->belongsTo(DlDriversLicenseOwner::class,'dl_drivers_license_owner_id');
	}

	public function license_duration()
	{
		return $this->belongsTo(DlLicenseDuration::class,'dl_license_duration_id');
	}

	public function drivers_license_classes()
	{
		return $this->hasMany(DlDriversLicenseClass::class,'dl_drivers_license_id');
	}
}
