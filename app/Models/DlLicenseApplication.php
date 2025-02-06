<?php

namespace App\Models;

use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DlLicenseApplication extends Model implements Auditable
{

    use WorkflowTrait, \OwenIt\Auditing\Auditable;

	protected $table = 'dl_license_applications';

	protected $dates = [
		'dob'
	];

    protected $guarded = [];

    public function getFullNameAttribute() {
        return $this->first_name . ' '. $this->last_name;
    }

	public function application_status()
	{
		return $this->belongsTo(DlApplicationStatus::class,'dl_application_status_id');
	}

	public function blood_group()
	{
		return $this->belongsTo(DlBloodGroup::class,'dl_blood_group_id');
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

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
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

    public function license(){
        return $this->belongsTo(DlDriversLicense::class, 'dl_drivers_license_id');
    }

    public function ledger()
    {
        return $this->morphOne(TaxpayerLedger::class, 'source');
    }
}
