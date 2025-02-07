<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DlDriversLicense extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

    const STATUS_DAMAGED_OR_LOST ='LOST/DAMAGED';
    const STATUS_EXPIRED ='EXPIRED';
    const ACTIVE ='ACTIVE';
    const BLOCKED ='BLOCKED';

	protected $table = 'dl_drivers_licenses';


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

	public function license_duration()
	{
		return $this->belongsTo(DlLicenseDuration::class,'dl_license_duration_id');
	}

	public function drivers_license_classes()
	{
		return $this->hasMany(DlDriversLicenseClass::class,'dl_drivers_license_id');
	}

    public function licenseRestrictions()
    {
        return $this->hasMany(DlLicenseRestriction::class,'dl_license_id');
    }

    public function application()
    {
        return $this->hasOne(DlLicenseApplication::class,'id', 'dl_license_application_id');
    }

    public function blacklist(){
        return $this->morphOne(MvrBlacklist::class, 'blacklist');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
}
