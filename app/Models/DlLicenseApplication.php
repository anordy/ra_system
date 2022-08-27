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

    use WorkflowTrait;

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


    public function generateBill(){
        $fee = DlFee::query()->where(['type' => $this->type])->first();
        $exchange_rate = 1;
        $amount = $fee->amount;
        $tax_type = TaxType::query()->where(['name'=>TaxType::PUBLIC_SERVICE])->first();
        return ZmCore::createBill(
            $this->id,
            get_class($this),
            $tax_type->id,
            $this->taxpayer->id,
            get_class($this->taxpayer),
            $this->taxpayer->fullname(),
            $this->taxpayer->email,
            ZmCore::formatPhone($this->taxpayer->mobile),
            Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
            $fee->name,
            ZmCore::PAYMENT_OPTION_EXACT,
            'TZS', //All fees are paid in tzs
            1,
            auth()->user()->id,
            get_class(auth()->user()),
            [
                [
                    'billable_id' => $this->id,
                    'billable_type' => get_class($this),
                    'fee_id' => $fee->id,
                    'fee_type' => get_class($fee),
                    'tax_type_id' => $tax_type->id,
                    'amount' => $amount,
                    'currency' => 'TZS',
                    'exchange_rate' => $exchange_rate,
                    'equivalent_amount' => $exchange_rate * $amount,
                    'gfs_code' => $fee->gfs_code
                ]
            ]
        );
    }
}
