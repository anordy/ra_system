<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaxAgent extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

	protected $table = 'tax_agents';

	protected $guarded = [];

	public function academics() {
		return $this->hasMany('App\Models\TaxAgentAcademicQualification');
	}
	public function professionals()
	{
		return $this->hasMany('App\Models\TaxAgentProfessionals');
	}

	public function trainings()
	{
		return $this->hasMany('App\Models\TaxAgentTrainingExperience');
	}
}
