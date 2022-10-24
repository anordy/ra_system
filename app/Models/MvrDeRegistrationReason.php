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
 * Class MvrDeRegistrationReason
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MvrDeRegistrationReason extends Model implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_de_registration_reasons';

	protected $fillable = [
		'name'
	];
}
