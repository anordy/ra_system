<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlDriversLicenseOwner
 * 
 * @property int $id
 * @property int $taxpayer_id
 * @property int $dl_blood_group_id
 * @property Carbon $dob
 * @property string $competence_number
 * @property string $certificate_number
 * @property string $confirmation_number
 * @property string $photo_path
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DlBloodGroup $dl_blood_group
 * @property Taxpayer $taxpayer
 * @property Collection|DlDriversLicense[] $dl_drivers_licenses
 * @property Collection|DlLicenseApplication[] $dl_license_applications
 *
 * @package App\Models
 */
class DlDriversLicenseOwner extends Model
{
	protected $table = 'dl_drivers_license_owners';

	protected $casts = [
		'taxpayer_id' => 'int',
		'dl_blood_group_id' => 'int'
	];

	protected $dates = [
		'dob'
	];

	protected $fillable = [
		'taxpayer_id',
		'dl_blood_group_id',
		'dob',
		'competence_number',
		'certificate_number',
		'confirmation_number',
		'photo_path'
	];

	public function dl_blood_group()
	{
		return $this->belongsTo(DlBloodGroup::class);
	}

	public function taxpayer()
	{
		return $this->belongsTo(Taxpayer::class);
	}

	public function drivers_licenses()
	{
		return $this->hasMany(DlDriversLicense::class,'dl_drivers_license_owner_id');
	}

	public function dl_license_applications()
	{
		return $this->hasMany(DlLicenseApplication::class);
	}
}
