<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Investigation\TaxInvestigation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Case
 * 
 * @property int $id
 * @property Carbon $date_opened
 * @property string $case_number
 * @property string $court
 * @property string $case_details
 * @property int $tax_investigation_id
 * @property int $case_stage_id
 * @property int|null $case_outcome_id
 * @property Carbon|null $date_closed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CaseOutcome|null $case_outcome
 * @property CaseStage $case_stage
 * @property TaxInvestigation $tax_investigation
 * @property Collection|CaseAppeal[] $case_appeals
 *
 * @package App\Models
 */
class LegalCase extends Model
{
	protected $table = 'cases';

	protected $casts = [
		'tax_investigation_id' => 'int',
		'case_stage_id' => 'int',
		'case_outcome_id' => 'int'
	];

	protected $dates = [
		'date_opened',
		'date_closed'
	];

	protected $fillable = [
		'date_opened',
		'case_number',
		'court',
		'case_details',
		'tax_investigation_id',
		'case_stage_id',
		'case_outcome_id',
		'date_closed'
	];

	public function case_outcome()
	{
		return $this->belongsTo(CaseOutcome::class);
	}

	public function case_stage()
	{
		return $this->belongsTo(CaseStage::class);
	}

	public function tax_investigation()
	{
		return $this->belongsTo(TaxInvestigation::class);
	}

	public function case_appeals()
	{
		return $this->hasMany(CaseAppeal::class,'case_id');
	}

    public function assiged_officer()
    {
        return $this->belongsTo(User::class,'assigned_officer_id');
    }
}
