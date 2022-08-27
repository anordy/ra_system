<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaseStage
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CaseDecision[] $case_decisions
 * @property Collection|CaseProceeding[] $case_proceedings
 * @property Collection|Case[] $cases
 *
 * @package App\Models
 */
class CaseStage extends Model
{
	protected $table = 'case_stages';

	protected $fillable = [
		'name'
	];

	public function case_decisions()
	{
		return $this->hasMany(CaseDecision::class);
	}

	public function case_proceedings()
	{
		return $this->hasMany(CaseProceeding::class);
	}

	public function cases()
	{
		return $this->hasMany(LegalCase::class,'case_id');
	}
}
