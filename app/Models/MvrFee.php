<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrFee
 * 
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property string $gfs_code
 * @property int $mvr_fee_type_id
 * @property int|null $mvr_registration_type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MvrFeeType $mvr_fee_type
 * @property MvrRegistrationType|null $mvr_registration_type
 *
 * @package App\Models
 */
class MvrFee extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'mvr_fees';

	protected $casts = [
		'amount' => 'float',
		'mvr_fee_type_id' => 'int',
		'mvr_registration_type_id' => 'int',
		'mvr_class_id' => 'int',
	];

	protected $fillable = [
		'name',
		'amount',
		'gfs_code',
		'mvr_fee_type_id',
		'mvr_registration_type_id',
		'mvr_class_id',
		'status',
        'mvr_plate_number_type_id'
	];

	public function fee_type()
	{
		return $this->belongsTo(MvrFeeType::class,'mvr_fee_type_id');
	}

	public function registration_type()
	{
		return $this->belongsTo(MvrRegistrationType::class,'mvr_registration_type_id');
	}

    public function class()
    {
        return $this->belongsTo(MvrClass::class,'mvr_class_id');
    }

    public function plate_type(){
        return $this->belongsTo(MvrPlateNumberType::class, 'mvr_plate_number_type_id');
    }
}
