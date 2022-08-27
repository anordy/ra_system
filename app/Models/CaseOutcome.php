<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CaseOutcome
 * 
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|CaseAppeal[] $case_appeals
 * @property Collection|Case[] $cases
 *
 * @package App\Models
 */
class CaseOutcome extends Model
{
	protected $table = 'case_outcomes';

	protected $fillable = [
		'name'
	];

	public function case_appeals()
	{
		return $this->hasMany(CaseAppeal::class);
	}

	public function cases()
	{
		return $this->hasMany(LegalCase::class,'case_id');
	}
}
