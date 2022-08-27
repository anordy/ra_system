<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RioRegisterOffence
 * 
 * @property int $id
 * @property int $rio_offence_id
 * @property int $rio_register_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property RioOffence $rio_offence
 * @property RioRegister $rio_register
 *
 * @package App\Models
 */
class RioRegisterOffence extends Model
{
	protected $table = 'rio_register_offences';

	protected $casts = [
		'rio_offence_id' => 'int',
		'rio_register_id' => 'int'
	];

	protected $fillable = [
		'rio_offence_id',
		'rio_register_id'
	];

	public function offence()
	{
		return $this->belongsTo(RioOffence::class,'rio_offence_id');
	}

	public function register()
	{
		return $this->belongsTo(RioRegister::class,'rio_register_id');
	}
}
