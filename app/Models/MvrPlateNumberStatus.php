<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrPlateNumberStatus
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MvrPlateNumberStatus extends Model
{
    const STATUS_NOT_ASSIGNED = 'NOT ASSIGNED';
    const STATUS_GENERATED = 'GENERATED';
    const STATUS_PRINTED = 'PRINTED';
    const STATUS_RECEIVED = 'RECEIVED FROM PRINTING';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_LOST = 'LOST';
    const STATUS_DISTORTED = 'DISTORTED';
    const STATUS_RETIRED = 'RETIRED';
    const STATUS_EXPIRED = 'EXPIRED';

	use SoftDeletes;
	protected $table = 'mvr_plate_number_status';

	protected $fillable = [
		'name'
	];
}
