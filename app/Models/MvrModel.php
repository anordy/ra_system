<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrModel
 * 
 * @property int $id
 * @property string $name
 * @property int $mvr_make_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property MvrMake $mvr_make
 *
 * @package App\Models
 */
class MvrModel extends Model
{
	use SoftDeletes;
	protected $table = 'mvr_models';

	protected $casts = [
		'mvr_make_id' => 'int'
	];

	protected $fillable = [
		'name',
		'mvr_make_id'
	];

	public function make()
	{
		return $this->belongsTo(MvrMake::class,'mvr_make_id');
	}
    
}
