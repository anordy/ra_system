<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Services\ZanMalipo\ZmCore;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class DlLicenseApplication
 * 
 * @property int $id
 * @property int $taxpayer_id
 * @property int|null $dl_drivers_license_owner_id
 * @property int $dl_blood_group_id
 * @property int|null $dl_license_duration_id
 * @property Carbon|null $dob
 * @property string|null $certificate_path
 * @property string|null $certificate_number
 * @property string|null $confirmation_number
 * @property string|null $photo_path
 * @property string|null $license_restrictions
 * @property string $type
 * @property int $dl_application_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DlApplicationStatus $dl_application_status
 * @property DlBloodGroup $dl_blood_group
 * @property DlDriversLicenseOwner|null $dl_drivers_license_owner
 * @property DlLicenseDuration|null $license_duration
 * @property Taxpayer $taxpayer
 * @property Collection|DlApplicationLicenseClass[] $application_license_classes
 *
 * @package App\Models
 */
class DlLicenseApplication extends Model implements Auditable
{

    use WorkflowTrait, \OwenIt\Auditing\Auditable;

	protected $table = 'dl_license_applications';

	protected $casts = [
		'taxpayer_id' => 'int',
		'dl_drivers_license_owner_id' => 'int',
		'dl_blood_group_id' => 'int',
		'dl_license_duration_id' => 'int',
		'dl_application_status_id' => 'int'
	];

	protected $dates = [
		'dob'
	];

    protected $guarded = [];

	public function application_status()
	{
		return $this->belongsTo(DlApplicationStatus::class,'dl_application_status_id');
	}

	public function blood_group()
	{
		return $this->belongsTo(DlBloodGroup::class,'dl_blood_group_id');
	}

	public function drivers_license_owner()
	{
		return $this->belongsTo(DlDriversLicenseOwner::class,'dl_drivers_license_owner_id');
	}

        public function license_duration()
	{
		return $this->belongsTo(DlLicenseDuration::class,'dl_license_duration_id');
	}

	public function taxpayer()
	{
		return $this->belongsTo(Taxpayer::class);
	}

    public function drivers_license()
    {
        return $this->hasOne(DlDriversLicense::class,'dl_license_application_id');
    }

	public function application_license_classes()
	{
		return $this->hasMany(DlApplicationLicenseClass::class,'dl_license_application_id');
	}

    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class,'billable')->latest()->first();
        return $bill_item->bill ??  null;
    }

    public function licenseRestrictions()
    {
        return $this->hasMany(DlLicenseRestriction::class,'dl_license_application_id');
    }

    public function certificates(){
        return $this->hasMany(DlApplicationCertificate::class, 'dl_license_application_id');
    }

    public function previousApplication(){
        return $this->belongsTo(DlLicenseApplication::class, 'previous_application_id');
    }
}
