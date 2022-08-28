<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaseProceeding
 * 
 * @property int $id
 * @property int $case_id
 * @property Carbon $date
 * @property string $comment
 * @property int $case_stage_id
 * @property int|null $case_decision_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CaseDecision|null $case_decision
 * @property CaseStage $case_stage
 *
 * @package App\Models
 */
class CaseProceeding extends Model
{
	protected $table = 'case_proceedings';

	protected $casts = [
		'case_id' => 'int',
		'case_stage_id' => 'int',
		'case_decision_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'case_id',
		'date',
		'comment',
		'case_stage_id',
		'case_decision_id'
	];

	public function case_decision()
	{
		return $this->belongsTo(CaseDecision::class);
	}

	public function case_stage()
	{
		return $this->belongsTo(CaseStage::class);
	}
}
