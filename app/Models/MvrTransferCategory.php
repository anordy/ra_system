<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrTransferCategory
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MvrTransferCategory extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'mvr_registration_type_categories';

	protected $fillable = [
		'name'
	];
}
