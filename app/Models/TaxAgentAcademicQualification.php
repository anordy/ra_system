<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAgentAcademicQualification extends Model
{
    use HasFactory;
	protected $table = 'tax_agent_academic_qualifications';

	protected $guarded = [];

	public function taxAgent() {
		return $this->belongsTo('App\Models\TaxAgent');
	}
}
