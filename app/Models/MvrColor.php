<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrColor
 * 
 * @property int $id
 * @property string $name
 * @property string $hex_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MvrColor extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_colors';

	protected $fillable = [
		'name',
		'hex_value'
	];
}
