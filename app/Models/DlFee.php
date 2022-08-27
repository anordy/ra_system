<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DlFee
 * 
 * @property int $id
 * @property string $name
 * @property float $amount
 * @property string $type
 * @property string $gfs_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class DlFee extends Model
{
	protected $table = 'dl_fees';

	protected $casts = [
		'amount' => 'float'
	];

	protected $fillable = [
		'name',
		'amount',
		'type',
		'gfs_code',
	];
}
