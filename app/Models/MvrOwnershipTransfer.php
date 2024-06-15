<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrOwnershipTransfer
 * 
 * @property int $id
 * @property int $mvr_motor_vehicle_id
 * @property int $mvr_ownership_transfer_reason_id
 * @property int $mvr_transfer_category_id
 * @property float|null $market_value
 * @property Carbon $sale_date
 * @property Carbon $delivered_date
 * @property Carbon $application_date
 * @property string|null $certificate_path
 * @property string|null $agreement_contract_path
 * @property int $agent_taxpayer_id
 * @property int $owner_taxpayer_id
 * @property int $mvr_request_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Taxpayer $taxpayer
 * @property MvrMotorVehicle $mvr_motor_vehicle
 * @property MvrOwnershipTransferReason $mvr_ownership_transfer_reason
 * @property MvrRequestStatus $mvr_request_status
 *
 * @package App\Models
 */
class MvrOwnershipTransfer extends Model
{
	use SoftDeletes, WorkflowTrait;
	protected $table = 'mvr_ownership_transfer';

	protected $casts = [
		'mvr_motor_vehicle_id' => 'int',
		'mvr_ownership_transfer_reason_id' => 'int',
		'mvr_transfer_category_id' => 'int',
		'market_value' => 'float',
		'agent_taxpayer_id' => 'int',
		'owner_taxpayer_id' => 'int',
		'mvr_request_status_id' => 'int'
	];

	protected $dates = [
		'sale_date',
		'delivered_date',
		'application_date'
	];

	protected $fillable = [
		'mvr_motor_vehicle_id',
		'mvr_ownership_transfer_reason_id',
		'mvr_transfer_category_id',
		'market_value',
		'sale_date',
		'delivered_date',
		'application_date',
		'certificate_path',
		'transfer_reason',
		'agreement_contract_path',
		'mvr_agent_id',
		'owner_taxpayer_id',
		'mvr_request_status_id',
        'inspection_report',
        'old_plate_number'
	];

	public function new_owner()
	{
		return $this->belongsTo(Taxpayer::class, 'owner_taxpayer_id');
	}
	public function previous_owner()
	{
		return $this->belongsTo(Taxpayer::class, 'agent_taxpayer_id');
	}
	public function motor_vehicle()
	{
		return $this->belongsTo(MvrRegistration::class,'mvr_motor_vehicle_id');
	}

	public function ownership_transfer_reason()
	{
		return $this->belongsTo(MvrOwnershipTransferReason::class,'mvr_ownership_transfer_reason_id');
	}

    public function transfer_category()
    {
        return $this->belongsTo(MvrTransferCategory::class,'mvr_transfer_category_id');
    }

    public function regtype(){
        return $this->belongsTo(MvrRegistrationType::class, 'mvr_registration_type_id');
    }


	public function request_status()
	{
		return $this->belongsTo(MvrRequestStatus::class,'mvr_request_status_id');
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

    public function ledger()
    {
        return $this->morphOne(TaxpayerLedger::class, 'source');
    }
}
