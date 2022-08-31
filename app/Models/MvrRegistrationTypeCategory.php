<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MvrRegistrationTypeCategory
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class MvrRegistrationTypeCategory extends Model
{
	protected $table = 'mvr_registration_type_categories';

	protected $fillable = [
		'name'
	];
}
