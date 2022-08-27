<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaseAppeal
 * 
 * @property int $id
 * @property int $case_id
 * @property string $appeal_details
 * @property int|null $case_outcome_id
 * @property int|null $court_level_id
 * @property Carbon|null $date_closed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property LegalCase $case
 * @property CaseOutcome|null $case_outcome
 * @property CourtLevel|null $court_level
 *
 * @package App\Models
 */
class CaseAppeal extends Model
{
	protected $table = 'case_appeals';

	protected $casts = [
		'case_id' => 'int',
		'case_outcome_id' => 'int',
		'court_level_id' => 'int'
	];

	protected $dates = [
		'date_closed'
	];

	protected $fillable = [
		'case_id',
		'appeal_details',
		'case_outcome_id',
		'court_level_id',
		'date_opened',
		'appeal_number',
		'date_closed',
	];

	public function case()
	{
		return $this->belongsTo(LegalCase::class,'case_id');
	}

	public function case_outcome()
	{
		return $this->belongsTo(CaseOutcome::class);
	}

	public function court_level()
	{
		return $this->belongsTo(CourtLevel::class);
	}
}
