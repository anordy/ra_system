<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlLicenseApplication
 * 
 * @property int $id
 * @property int $taxpayer_id
 * @property int|null $dl_drivers_license_owner_id
 * @property int $dl_blood_group_id
 * @property int|null $dl_license_duration_id
 * @property Carbon|null $dob
 * @property string|null $competence_number
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
 * @property DlLicenseDuration|null $dl_license_duration
 * @property Taxpayer $taxpayer
 * @property Collection|DlApplicationLicenseClass[] $dl_application_license_classes
 *
 * @package App\Models
 */
class DlLicenseApplication extends Model
{
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

	protected $fillable = [
		'taxpayer_id',
		'dl_drivers_license_owner_id',
		'dl_blood_group_id',
		'dl_license_duration_id',
		'dob',
		'competence_number',
		'certificate_number',
		'confirmation_number',
		'photo_path',
		'license_restrictions',
		'type',
		'dl_application_status_id'
	];

	public function dl_application_status()
	{
		return $this->belongsTo(DlApplicationStatus::class);
	}

	public function dl_blood_group()
	{
		return $this->belongsTo(DlBloodGroup::class);
	}

	public function dl_drivers_license_owner()
	{
		return $this->belongsTo(DlDriversLicenseOwner::class);
	}

	public function dl_license_duration()
	{
		return $this->belongsTo(DlLicenseDuration::class);
	}

	public function taxpayer()
	{
		return $this->belongsTo(Taxpayer::class);
	}

	public function dl_application_license_classes()
	{
		return $this->hasMany(DlApplicationLicenseClass::class);
	}
}
