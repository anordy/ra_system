<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrFeeType
 * 
 * @property int $id
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|MvrFee[] $mvr_fees
 *
 * @package App\Models
 */
class MvrFeeType extends Model
{
	protected $table = 'mvr_fee_types';

	protected $fillable = [
		'type'
	];

	public function mvr_fees()
	{
		return $this->hasMany(MvrFee::class);
	}
}
