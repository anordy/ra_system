<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrRegistrationChangeRequest
 * 
 * @property int $id
 * @property int $agent_taxpayer_id
 * @property int $current_registration_type_id
 * @property int $requested_registration_type_id
 * @property string $custom_plate_number
 * @property Carbon $date
 * @property int $mvr_request_status_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Taxpayer $taxpayer
 * @property MvrRegistrationType $mvr_registration_type
 * @property MvrRequestStatus $mvr_request_status
 *
 * @package App\Models
 */
class MvrRegistrationChangeRequest extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_registration_change_requests';

	protected $casts = [
		'agent_taxpayer_id' => 'int',
		'current_registration_type_id' => 'int',
		'requested_registration_type_id' => 'int',
		'mvr_request_status_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'mvr_agent_id',
		'current_registration_id',
		'mvr_plate_size_id',
		'requested_registration_type_id',
		'mvr_plate_number_size_id',
		'custom_plate_number',
		'date',
		'mvr_request_status_id'
	];

	public function agent()
	{
		return $this->belongsTo(MvrAgent::class, 'mvr_agent_id');
	}

	public function requested_registration_type()
	{
		return $this->belongsTo(MvrRegistrationType::class, 'requested_registration_type_id');
	}

    public function current_registration()
    {
        return $this->belongsTo(MvrMotorVehicleRegistration::class, 'current_registration_id');
    }

	public function request_status()
	{
		return $this->belongsTo(MvrRequestStatus::class,'mvr_request_status_id');
	}

    public function plate_size()
    {
        return $this->belongsTo(MvrPlateSize::class,'mvr_plate_size_id');
    }


    public function get_latest_bill()
    {
        $bill_item = $this->morphOne(ZmBillItem::class,'billable')->latest()->first();
        return $bill_item->bill ??  null;
    }
}
