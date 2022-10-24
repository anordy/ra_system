<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class MvrMake
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|MvrModel[] $mvr_models
 *
 * @package App\Models
 */
class MvrMake extends GenericSettingModel implements Auditable
{
	use SoftDeletes, \OwenIt\Auditing\Auditable;
	protected $table = 'mvr_make';

	protected $fillable = [
		'name'
	];

	public function models()
	{
		return $this->hasMany(MvrModel::class);
	}

    public static function settingTitle()
    {
        return "Motor Vehicle Make";
    }

    public static function validationRules()
    {
        return [];
    }

    public static function settingColumn()
    {
        return null;
    }
}
