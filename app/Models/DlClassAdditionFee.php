<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class DlClassAdditionFee
 * 
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DlClassAdditionFee extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;

	protected $table = 'dl_class_addition_fees';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'name',
		'amount'
	];
}
