<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

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
class MvrColor extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_colors';

	protected $guarded = [];

    public function registration_type(){
        return $this->belongsTo(MvrRegistrationType::class, 'mvr_registration_type_id', 'id');
    }
}
