<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class MvrRegistrationType
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class MvrRegistrationType extends Model
{
    const TYPE_PRIVATE_PERSONALIZED = 'Personalized Registration';
    const TYPE_DIPLOMATIC = 'Diplomat';
    const TYPE_PRIVATE_GOLDEN = 'Golden Number Registration';

	use SoftDeletes;
	protected $table = 'mvr_registration_types';

	protected $fillable = [
		'name'
	];
}
