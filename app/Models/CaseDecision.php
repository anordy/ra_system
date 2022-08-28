<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaseDecision
 * 
 * @property int $id
 * @property string $name
 * @property int|null $case_stage_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CaseStage|null $case_stage
 * @property Collection|CaseProceeding[] $case_proceedings
 *
 * @package App\Models
 */
class CaseDecision extends Model
{
	protected $table = 'case_decisions';

	protected $casts = [
		'case_stage_id' => 'int'
	];

	protected $fillable = [
		'name',
		'case_stage_id'
	];

	public function case_stage()
	{
		return $this->belongsTo(CaseStage::class);
	}

	public function case_proceedings()
	{
		return $this->hasMany(CaseProceeding::class);
	}
}
