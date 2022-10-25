<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrDeRegistrationRequest
 * 
 * @property int $id
 * @property int $agent_taxpayer_id
 * @property int $mvr_motor_vehicle_id
 * @property int $mvr_de_registration_reason_id
 * @property string $description
 * @property Carbon $date_received
 * @property string|null $certificate_path
 * @property Carbon|null $certificate_date
 * @property int $mvr_request_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Taxpayer $taxpayer
 * @property MvrMotorVehicle $mvr_motor_vehicle
 * @property MvrRequestStatus $mvr_request_status
 *
 * @package App\Models
 */
class MvrDeRegistrationRequest extends Model implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_de_registration_requests';

	protected $casts = [
		'mvr_agent_id' => 'int',
		'mvr_motor_vehicle_id' => 'int',
		'mvr_de_registration_reason_id' => 'int',
		'mvr_request_status_id' => 'int'
	];

	protected $dates = [
		'date_received',
		'certificate_date'
	];

	protected $fillable = [
		'mvr_agent_id',
		'mvr_motor_vehicle_id',
		'mvr_de_registration_reason_id',
		'description',
		'date_received',
		'inspection_report_path',
		'certificate_path',
		'certificate_date',
		'mvr_request_status_id'
	];

	public function agent()
	{
		return $this->belongsTo(MvrAgent::class, 'mvr_agent_id');
	}

	public function motor_vehicle()
	{
		return $this->belongsTo(MvrMotorVehicle::class,'mvr_motor_vehicle_id');
	}

	public function request_status()
	{
		return $this->belongsTo(MvrRequestStatus::class,'mvr_request_status_id');
	}

    public function de_registration_reason()
    {
        return $this->belongsTo(MvrDeRegistrationReason::class,'mvr_de_registration_reason_id');
    }

    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class,'billable')->latest()->first();
        return $bill_item->bill ??  null;
    }
}
