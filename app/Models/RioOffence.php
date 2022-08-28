<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RioOffence
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|RioRegisterOffence[] $rio_register_offences
 *
 * @package App\Models
 */
class RioOffence extends Model
{
	protected $table = 'rio_offences';

	protected $fillable = [
		'name'
	];

	public function rio_register_offences()
	{
		return $this->hasMany(RioRegisterOffence::class);
	}
}
